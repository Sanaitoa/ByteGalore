<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subscriber_id'])) {
    $subscriberId = $_POST['subscriber_id'];

    $conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');

    // Delete the subscriber
    $queryDeleteSubscriber = "DELETE FROM subscribers WHERE id = '$subscriberId'";
    mysqli_query($conn, $queryDeleteSubscriber);

    mysqli_close($conn);

    // Redirect back to the subscribers page
    header('Location: admin_subscribers.php');
    exit();
} else {
    header('Location: admin_subscribers.php');
    exit();
}
?>