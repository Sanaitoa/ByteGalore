<?php
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the entered username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the database
    $conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');

    // Prepare the query to fetch the admin details
    $query = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    // Check if the query returned a row
    if (mysqli_num_rows($result) == 1) {
        // Fetch the admin data
        $admin = mysqli_fetch_assoc($result);

        // Store the admin ID in the session
        $_SESSION['admin_id'] = $admin['id'];

        // Redirect to the admin dashboard
        header('Location: admin_dashboard.php');
        exit();
    } else {
        // Invalid credentials, store an alert message in the session
        $_SESSION['login_error'] = 'Invalid username or password.';
        
        // Redirect back to the login page
        header('Location: admin_login.php');
        exit();
    }

    mysqli_close($conn);
}
