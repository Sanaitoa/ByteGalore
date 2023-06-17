<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form values
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Get the current timestamp
    $timestamp = date("Y-m-d H:i:s");

    // Do something with the retrieved values and timestamp
    // For example, you can save them to a file, send an email, or store in a database

    // Save the data to a file
    $data = [
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
        'timestamp' => $timestamp
    ];

    $dataEntry = json_encode($data) . PHP_EOL;
    file_put_contents('data/contact_data.txt', $dataEntry, FILE_APPEND);

    // Display a success message
    $successMessage = "Thank you for contacting us! We will get back to you soon.";
    echo "<script>alert('$successMessage');</script>";

    header("Location: index.html");
    exit();
}
?>
