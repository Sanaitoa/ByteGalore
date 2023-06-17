<?php
// Assuming you have a database connection established

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointmentId']) && isset($_POST['status'])) {
    $appointmentId = $_POST['appointmentId'];
    $status = $_POST['status'];

    // Update the appointment status in the database
    $sql = "UPDATE appointments SET status = '$status' WHERE id = $appointmentId";
    if (mysqli_query($conn, $sql)) {
        // Status updated successfully
        echo 'success';
    } else {
        // Failed to update status
        echo 'error';
    }
} else {
    // Invalid request
    echo 'Invalid request';
}
?>
