<!DOCTYPE html>
<html>
<head>
    <title>Reservation Summary</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            padding: 40px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 10px;
        }

        ul {
            margin-bottom: 10px;
            padding-left: 20px;
        }

        a {
            display: inline-block;
            margin-top: 20px;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            overflow-y: auto;
        }

        .overlay img {
            margin-top: 50%;
            max-width: 150%;
            max-height: 200%;
        }
    </style>
    <script>
        function showOverlay() {
            document.getElementById("overlay").style.display = "flex";
        }

        function hideOverlay() {
            document.getElementById("overlay").style.display = "none";
        }
    </script>
</head>
<body>

    <?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        // Redirect to the login page if the user is not logged in
        header('Location: login.php');
        exit();
    }

    $conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');
    $userId = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $query = "SELECT * FROM users WHERE id = '$userId'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    $name = $user['name'];
    $email = $user['email'];

    $query = "SELECT * FROM appointments WHERE user_id = '$userId' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    $appointment = mysqli_fetch_assoc($result);

    $appointmentId = $appointment['id'];
    $numPeople = $appointment['num_people'];
    $foodOrder = unserialize($appointment['food_order']);
    $specialRequest = $appointment['special_request'];
    $totalOrder = $appointment['total_order'];
    $reservationFee = $appointment['reservation_fee'];

    // Compute the subtotal (total order - reservation fee)
    $subtotal = $totalOrder + $reservationFee;

    // Retrieve the food item names and quantities from the food_menu table
    $foodDetails = array();
    // echo count($foodOrder);
    foreach ($foodOrder as $foodItem) {
        $foodDetails[] = $foodItem;
    }

    mysqli_close($conn);
    ?>

    <div class="container">
        <h1>Reservation Confirmation</h1>
        <p>Thank you for making a reservation! Your reservation details:</p>

        <p><strong>Name:</strong> <?php echo $name; ?></p>
        <p><strong>Email:</strong> <?php echo $email; ?></p>

        <p><strong>Appointment ID:</strong> <?php echo $appointmentId; ?></p>
        <p><strong>Number of People:</strong> <?php echo $numPeople; ?></p>

        <?php if (!empty($foodDetails)): ?>
            <p><strong>Food Order:</strong></p>
            <ul>
                <?php foreach ($foodDetails as $foodDetail): ?>
                    <li><?php echo $foodDetail['name']; ?> (Quantity: <?php echo $foodDetail['quantity']; ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No food items found for this reservation.</p>
        <?php endif; ?>

        <p><strong>Special Request:</strong> <?php echo $specialRequest; ?></p>
        <p><strong>Total Order:</strong> ₱<?php echo $totalOrder; ?></p>
        <p><strong>Reservation Fee:</strong> ₱<?php echo $reservationFee; ?></p>
        <p><strong>Subtotal:</strong> ₱<?php echo $subtotal; ?></p>

        <?php
            $appointmentDate = date('F j, Y', strtotime($appointment['appointment_date']));
            $appointmentTime = date('h:i A', strtotime($appointment['appointment_time']));
        ?>
        <p><strong>Appointment Date:</strong> <?php echo $appointmentDate; ?></p>
        <p><strong>Appointment Time:</strong> <?php echo $appointmentTime; ?></p>

        <p>Just a few step left! Proceed to payment to complete your order!</p>
        <button onclick="showOverlay()">Wanna know how to pay?</button>
        <br><br>
        <a href="paymentreference.php" class="btn btn-primary">Proceed to Payment</a>
        <br>
        <a href="profile.php" class="btn btn-warning">BACK TO PROFILE</a>
        <br>
        <a href="login.php" class="btn btn-secondary">LOGOUT</a>
        <br><br>
        <button onclick="window.print()" class="btn btn-info">PRINT</button>
    </div>

    <div id="overlay" class="overlay" onclick="hideOverlay()">
        <img src="bayad.png" alt="Payment Information">
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
