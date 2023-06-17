<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_GET['id'];

    // Delete user
    $deleteQuery = "DELETE FROM users WHERE id = '$userId'";
    mysqli_query($conn, $deleteQuery);

    // Delete associated appointments
    $deleteAppointmentsQuery = "DELETE FROM appointments WHERE user_id = '$userId'";
    mysqli_query($conn, $deleteAppointmentsQuery);

    header('Location: admin_dashboard.php');
    exit();
}

mysqli_close($conn);
?>
