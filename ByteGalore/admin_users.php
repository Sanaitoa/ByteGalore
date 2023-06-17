<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID and action from the form submission
    $userId = $_POST['user_id'];
    $action = $_POST['action'];

    // Perform the action based on the selected option
    if ($action === 'edit') {
        // Redirect to the edit user page
        header("Location: edit_user.php?id=$userId");
        exit();
    } elseif ($action === 'delete') {
        // Display a confirmation dialog before deleting the user
        echo "<script>
                if (confirm('Are you sure you want to delete this user?')) {
                    window.location.href = 'delete_user.php?id=$userId';
                }
            </script>";
    }
}

// Retrieve Users
$searchUsersQuery = isset($_GET['search_users']) ? mysqli_real_escape_string($conn, $_GET['search_users']) : '';
$userQuery = "SELECT * FROM users WHERE id LIKE '%$searchUsersQuery%' OR name LIKE '%$searchUsersQuery%' OR email LIKE '%$searchUsersQuery%'";
$userResult = mysqli_query($conn, $userQuery);
$users = array();

while ($userRow = mysqli_fetch_assoc($userResult)) {
    $users[] = $userRow;
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


        .action-buttons {
            display: flex;
            gap: 10px;
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

        .button {
  padding: 10px 20px;
  background-color: #e25822;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 10px;
}

.button:hover {
  background-color: #45a049;
}

.button:last-child {
  margin-right: 0;
}
    </style>
    <title>Admin Dashboard - Users</title>
</head>
<body>
<div class="navbar">
    <a href="admin_dashboard.php">Appointments</a>
    <a class="active" href="admin_users.php">Users</a>
    <a href="admin_contact_data.php">Messages</a>
    <a href="edit_food_menu.php">Edit Food Menu</a>
    <a href="admin_subscribers.php">Subscribers</a>
    <a href="admin_logout.php">Logout</a>
</div>

<div class="table-container">
   <!-- Search Form -->
   <div class="search-container">
    <form class="search-form" method="GET" action="">
        <input type="text" id="search_users" name="search_users" value="<?php echo isset($_GET['search_users']) ? $_GET['search_users'] : ''; ?>" placeholder="Search ID, name, or email">
        <button type="submit">Search</button>
    </form>
</div>

    <h2>Users</h2>
    <table id="users-table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Contact Number</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['password']; ?></td>
                <td><?php echo $user['contact_number']; ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <div class="action-buttons">
                        <button type="submit" name="action" value="edit" class="button">Edit</button> 
                        <button type="submit" name="action" value="delete" class="button" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                        </div>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
