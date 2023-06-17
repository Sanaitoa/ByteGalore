<?php
// Establish database connection
$host = "localhost";
$username = "root";
$password = "root";
$database = "bytegalore";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contactNumber = $_POST['contact_number'];

    // Validate form data (e.g., check for empty fields, email format, password strength)

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $sql = "INSERT INTO users (name, email, password, contact_number) 
            VALUES ('$name', '$email', '$hashedPassword', '$contactNumber')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to login page or display a success message
        header("Location: login.php");
        exit();
    } else {
        // Handle the error
        $registrationError = "Registration failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="img/BYTE_GALORE_LOGO.png" type="image/x-icon">
    <title>User Registration</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/reg.css" rel="stylesheet" type="text/css" />
    <style>
        .submitButton {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 18px;
            color: #fff;
            background-color: #ff9900;
            /* margin-left: 8px; */
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
   
    <div class="container">
        <center><img class="img-fluid" src="img/BYTE_GALORE_LOGO.png" alt="" width="100" height="100" /></center>
        <h2 class="text-center">Registration Form</h2>

        <?php if (isset($registrationError)) { ?>
            <p style="color: red;"><?php echo $registrationError; ?></p>
        <?php } ?>

        <form method="POST" class="form">
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Name" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email address" required>
            </div>
            <div class="form-group">
                <input type="tel" class="form-control" name="contact_number" placeholder="Contact Number" required>
            </div>
            <button type="submit" class="submitButton btn btn-primary">Register</button>
        </form>
        <center><p class="text-center"><a href="login.php"> Already have an account</a></p></center>
    </div>
  
    <script src="js/bootstrap.min.js"></script>
</body>
</html>