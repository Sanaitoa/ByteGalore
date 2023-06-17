<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_GET['id'];

    // Retrieve user data
    $userQuery = "SELECT * FROM users WHERE id = '$userId'";
    $userResult = mysqli_query($conn, $userQuery);
    $userData = mysqli_fetch_assoc($userResult);

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
            margin-right:20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 3px;
            border: 1px solid #ccc;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #e25822;
            outline: none;
        }

        .field-container {
            margin-bottom: 15px;
        }

        .button-container {
            text-align: center;
        }

        button {
            background-color: #e25822;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #c4411b;
        }
    </style>
    <title>Edit User</title>
</head>
<body>
    <div class="container">
        <h1>Edit User</h1>
        <form method="POST" action="edit_user.php">
            <div class="field-container">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $userData['name']; ?>">
            </div>
            <div class="field-container">
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" value="<?php echo $userData['email']; ?>">
            </div>
            <div class="field-container">
                <label for="password">New Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter new password">
            </div>
            <div class="button-container">
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if a new password is provided
    if (!empty($password)) {
        // Hash the new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Update user data with hashed password
        $updateQuery = "UPDATE users SET name = '$name', email = '$email', password = '$hashedPassword' WHERE id = '$userId'";
        mysqli_query($conn, $updateQuery);
    } else {
        // Update user data without changing the password
        $updateQuery = "UPDATE users SET name = '$name', email = '$email' WHERE id = '$userId'";
        mysqli_query($conn, $updateQuery);
    }

    mysqli_close($conn);

    header('Location: admin_dashboard.php');
    exit();
}
?>
