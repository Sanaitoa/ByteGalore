<!DOCTYPE html>
<html>
<head>
    <title>Reference Number Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            text-align: left;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border-radius: 3px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #e25822;
            color: #fff;
            border: none;
            border-radius: 3px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #d73c09;
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

        .btn {
            background-color: #e25822;
            color: white;
        }

        .btn:hover
        {
            background-color: #d73c09;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reference Number Form</h1>

        <?php
        session_start(); // Start the session

        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Establish database connection
            $conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');
            if (!$conn) {
                die("Failed to connect to the database: " . mysqli_connect_error());
            }

            $userId = mysqli_real_escape_string($conn, $_SESSION['user_id']);
            $query = "SELECT * FROM appointments WHERE user_id = '$userId' ORDER BY id DESC LIMIT 1";
            $result = mysqli_query($conn, $query);
            $appointment = mysqli_fetch_assoc($result);

            $appointmentId = $appointment['id'];

            // Retrieve the reference number from the form
            $referenceNumber = mysqli_real_escape_string($conn, $_POST['referenceNumber']);

            // Update the reference number in the appointments table
            $query = "UPDATE appointments SET reference_number = '$referenceNumber' WHERE id = $appointmentId";
            if (!mysqli_query($conn, $query)) {
                die("Error: " . mysqli_error($conn));
            }

            // Close the database connection
            mysqli_close($conn);

            // Redirect to confirmation page
            header('Location: confirmation.php');
            exit();
        }
        ?>

        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="referenceNumber">Reference Number:</label>
            <input type="text" name="referenceNumber" id="referenceNumber" required>
            <br><br>
            <input type="submit" value="Submit">
        </form>
        <br>
    <button class="btn" onclick="showOverlay()">Wanna know how to pay?</button>
    </div>
    <div id="overlay" class="overlay" onclick="hideOverlay()">
        <img src="bayad.png" alt="Payment Information">
    </div>
</body>
</html>

<script>
        function showOverlay() {
            document.getElementById("overlay").style.display = "flex";
        }

        function hideOverlay() {
            document.getElementById("overlay").style.display = "none";
        }
    </script>
