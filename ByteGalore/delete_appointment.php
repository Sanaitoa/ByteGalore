<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: admin_dashboard.php');
    exit();
}

$appointmentId = $_GET['id'];

// Delete the appointment from the database
$conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');
$deleteQuery = "DELETE FROM appointments WHERE id = '$appointmentId'";
mysqli_query($conn, $deleteQuery);
mysqli_close($conn);

// Redirect back to the admin dashboard
header('Location: admin_dashboard.php');
exit();
?>
