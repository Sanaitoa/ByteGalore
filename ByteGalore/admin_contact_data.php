<?php
$contactData = file_get_contents('data/contact_data.txt');
$contactEntries = explode(PHP_EOL, $contactData);

// Update status and save contact data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['entryIndex']) && isset($_POST['status'])) {
    $entryIndex = $_POST['entryIndex'];
    $status = $_POST['status'];

    if (isset($contactEntries[$entryIndex])) {
        $entryData = json_decode($contactEntries[$entryIndex], true);
        $entryData['read'] = $status === 'read' ? true : false;
        $contactEntries[$entryIndex] = json_encode($entryData);
        file_put_contents('data/contact_data.txt', implode(PHP_EOL, $contactEntries));
    }
}

// Delete contact entry
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteEntryIndex'])) {
    $deleteEntryIndex = $_POST['deleteEntryIndex'];

    if (isset($contactEntries[$deleteEntryIndex])) {
        unset($contactEntries[$deleteEntryIndex]);
        file_put_contents('data/contact_data.txt', implode(PHP_EOL, $contactEntries));
    }
}

// Search contact data by name or email
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search = $_POST['search'];
    $filteredEntries = array();

    foreach ($contactEntries as $entry) {
        if (!empty($entry)) {
            $data = json_decode($entry, true);
            $name = $data['name'];
            $email = $data['email'];

            if (stripos($name, $search) !== false || stripos($email, $search) !== false) {
                $filteredEntries[] = $entry;
            }
        }
    }

    $contactEntries = $filteredEntries;
}
?>

<!DOCTYPE html>
<html>
<head>
<style>
        /* Styles for the navigation bar */
        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .navbar a.active {
            background-color: #e25822;
            color: white;
        }

        /* Styles for the table */
        .table-container {
          margin-top: 20px;
        }

        table {
          border-collapse: collapse;
          width: 100%;
        }

        th, td {
          text-align: left;
          padding: 8px;
          transition: background-color 0.3s ease;
        }

        th {
          background-color: #e25822;
          color: white;
        }

        tr:nth-child(odd) {
          background-color: #f5f5f5;
          transition: background-color 0.3s ease;
        }

        tr:nth-child(even) {
          background-color: #eaeaea;
          transition: background-color 0.3s ease;
        }

        tr:hover {
          background-color: #f1f1f1;
          animation: hover-animation 0.3s ease;
        }

        th:hover {
          background-color: #d73c09;
          animation: hover-animation 0.3s ease;
        }

        @keyframes hover-animation {
          0% {
            background-color: #f1f1f1;
          }
          100% {
            background-color: #faa082;
            color: white;
          }
        }

       /* style for buttons */

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .status-read {
            background-color: green;
            color: white;
        }

        .status-unread {
            background-color: red;
            color: white;
        }

        /* Styles for the search bar */
        .search-container {
            margin-top: 20px;
            text-align: center;
        }

        .search-form input[type=text] {
            padding: 6px;
            width: 300px;
            font-size: 16px;
        }

        .search-form button {
            padding: 6px 12px;
            background-color: #e25822;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        .delete-button {
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 4px;
            background-color: #e25822;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-button:hover {
            background-color: #d73c09;
        }
        
    </style>
    <title>Contact Data</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <script>
        function updateStatus(entryIndex, status) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Refresh the page to update the table
                    location.reload();
                }
            };
            xhr.open("POST", "");
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("entryIndex=" + entryIndex + "&status=" + status);
        }

        function deleteEntry(entryIndex) {
            Swal.fire({
                title: 'Delete Entry',
                text: 'Are you sure you want to delete this entry?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e25822',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Refresh the page to update the table
                            location.reload();
                        }
                    };
                    xhr.open("POST", "");
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.send("deleteEntryIndex=" + entryIndex);
                }
            });
        }
    </script>
</head>
<link rel="icon" href="img/BYTE_GALORE_LOGO.png" type="image/x-icon">
<body>
<div class="navbar">
    <a href="admin_dashboard.php">Appointments</a>
    <a href="admin_users.php">Users</a>
    <a href="admin_contact_data.php" class="active">Messages</a>
    <a href="edit_food_menu.php">Edit Food Menu</a>
    <a href="admin_subscribers.php">Subscribers</a>
    <a href="admin_logout.php">Logout</a>
</div>
<div class="search-container">
    <form class="search-form" method="POST" action="">
        <input type="text" name="search" placeholder="Search by name or email">
        <button type="submit">Search</button>
    </form>
</div>
<h2>Contact Data</h2>
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Subject</th>
        <th>Message</th>
        <th>Timestamp</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php foreach ($contactEntries as $index => $entry): ?>
        <?php if (!empty($entry)): ?>
            <?php $data = json_decode($entry, true); ?>
            <tr>
                <td><?php echo $data['name']; ?></td>
                <td><?php echo $data['email']; ?></td>
                <td><?php echo $data['subject']; ?></td>
                <td><?php echo $data['message']; ?></td>
                <td><?php echo $data['timestamp']; ?></td>
                <td>
                    <?php if (isset($data['read']) && $data['read']): ?>
                        <button class="status-read" onclick="updateStatus(<?php echo $index; ?>, 'unread')">Mark Unread</button>
                    <?php else: ?>
                        <button class="status-unread" onclick="updateStatus(<?php echo $index; ?>, 'read')">Mark Read</button>
                    <?php endif; ?>
                </td>
                <td>
                    <button class="delete-button" onclick="deleteEntry(<?php echo $index; ?>)">Delete</button>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>
</body>
</html>
