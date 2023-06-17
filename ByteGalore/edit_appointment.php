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

// Fetch appointment details from the database
$conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');
$query = "SELECT * FROM appointments WHERE id = '$appointmentId'";
$result = mysqli_query($conn, $query);
$appointment = mysqli_fetch_assoc($result);

if (!$appointment) {
    mysqli_close($conn);
    header('Location: admin_dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the updated appointment details
    
    // Example code to update the appointment in the database
    $numPeople = $_POST['num_people'];
    $specialRequest = $_POST['special_request'];
    $totalOrder = $_POST['total_order'];
    $reservationFee = $_POST['reservation_fee'];
    
    // Update the appointment in the database
    $updateQuery = "UPDATE appointments SET num_people = '$numPeople', special_request = '$specialRequest', 
                    total_order = '$totalOrder', reservation_fee = '$reservationFee' WHERE id = '$appointmentId'";
    mysqli_query($conn, $updateQuery);
    
    mysqli_close($conn);
    
    // Redirect back to the admin dashboard
    header('Location: admin_dashboard.php');
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }

        .custom-save-button {
            background-color: #e25822;
            border-color: #e25822;
        }
        .custom-save-button:hover {
            background-color: white;
            color:#e25822;
            border-color:#e25822;
            
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Appointment</h1>

        <form method="POST" action="edit_appointment.php?id=<?php echo $appointmentId; ?>">
            <div class="form-group">
                <label for="num_people">Number of People:</label>
                <input type="text" class="form-control" name="num_people" id="num_people" value="<?php echo $appointment['num_people']; ?>">
            </div>

            <div class="form-group">
                <label for="special_request">Special Request:</label>
                <input type="text" class="form-control" name="special_request" id="special_request" value="<?php echo $appointment['special_request']; ?>">
            </div>

            <div class="form-group">
                <label for="total_order">Total Order:</label>
                <input type="text" class="form-control" name="total_order" id="total_order" value="<?php echo $appointment['total_order']; ?>">
            </div>

            <div class="form-group">
                <label for="reservation_fee">Reservation Fee:</label>
                <input type="text" class="form-control" name="reservation_fee" id="reservation_fee" value="<?php echo $appointment['reservation_fee']; ?>">
            </div>

            <button type="submit" class="btn btn-primary custom-save-button">Save</button>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
