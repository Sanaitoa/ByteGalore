<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');

// Retrieve Subscribers
$querySubscribers = "SELECT * FROM subscribers";
$resultSubscribers = mysqli_query($conn, $querySubscribers);
$subscribers = array();

while ($subscriberRow = mysqli_fetch_assoc($resultSubscribers)) {
    $subscribers[] = $subscriberRow;
}

// Search functionality
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $querySearch = "SELECT * FROM subscribers WHERE id LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%'";
    $resultSearch = mysqli_query($conn, $querySearch);
    $subscribers = array();

    while ($searchRow = mysqli_fetch_assoc($resultSearch)) {
        $subscribers[] = $searchRow;
    }
}

mysqli_close($conn);
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

        /* Styles for the copy button */
        .copy-button {
            margin-bottom: 20px;
            text-align: center;
        }

        .copy-button button {
            padding: 6px 12px;
            background-color: #e25822;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }
    </style>
    <title>Admin Subscribers</title>
    <script>
        function copyEmails() {
            const emails = Array.from(document.querySelectorAll("#subscribers-table td:nth-child(2)")).map(td => td.innerText);
            const textarea = document.createElement("textarea");
            textarea.value = emails.join("\n");
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand("copy");
            document.body.removeChild(textarea);
            alert("Emails copied to clipboard!");
        }
    </script>
</head>
<body>
<div class="navbar">
    <a href="admin_dashboard.php">Appointments</a>
    <a href="admin_users.php">Users</a>
    <a href="admin_contact_data.php">Messages</a>
    <a href="edit_food_menu.php">Edit Food Menu</a>
    <a class="active" href="admin_subscribers.php">Subscribers</a>
    <a href="admin_logout.php">Logout</a>
</div>

<br>
<div class="copy-button">
    <button onclick="copyEmails()">Copy Emails</button>
</div>

<div class="search-container">
    <form class="search-form" method="GET" action="admin_subscribers.php">
        <input type="text" name="search" placeholder="Search by ID or Email">
        <button type="submit">Search</button>
    </form>
</div>

<div id="subscribers" class="table-container">
    <h2>Subscribers</h2>
    <table id="subscribers-table">
    <tr>
      <th>ID</th>
      <th>Email</th>
      <th>Action</th>
    </tr>
    <?php foreach ($subscribers as $subscriber): ?>
    <tr>
        <td><?php echo $subscriber['id']; ?></td>
        <td><?php echo $subscriber['email']; ?></td>
        <td>
            <form method="POST" action="delete_subscriber.php">
                <input type="hidden" name="subscriber_id" value="<?php echo $subscriber['id']; ?>">
                <button type="submit" onclick="return confirm('Are you sure you want to delete this subscriber?')" style="padding: 10px 20px; background-color: #e25822; color: white; border: none; border-radius: 4px; cursor: pointer;">Delete</button>
            </form>
        </td>
    </tr>
<?php endforeach; ?>
    </table>
</div>

</body>
</html>

