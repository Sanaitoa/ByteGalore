<?php
// Check if email is submitted
if (isset($_POST['email'])) {
    // Retrieve the email from the form
    $email = $_POST['email'];

    // Database connection details
    $servername = 'localhost';
    $username = 'root';
    $password = 'root';
    $database = 'bytegalore';

    // Create a new mysqli instance
    $mysqli = new mysqli($servername, $username, $password, $database);

    // Check for connection errors
    if ($mysqli->connect_error) {
        die('Connection failed: ' . $mysqli->connect_error);
    }

    // Prepare the SQL statement to insert the email into the database
    $sql = "INSERT INTO subscribers (email) VALUES ('$email')";

    // Execute the SQL statement
    if ($mysqli->query($sql) === true) {
        // Success message
        echo "<script>alert('Thank you for subscribing!');</script>";
    } else {
        // Error message
        echo "<script>alert('Error: " . $mysqli->error . "');</script>";
    }

    // Close the database connection
    $mysqli->close();
}
?>
