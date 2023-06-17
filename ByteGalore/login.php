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

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

// Retrieve user data from the database based on the entered email
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $row['password'])) {
        // Password is correct
        // Set session variables to store user login status
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];

        // Redirect to the user's profile page
        header("Location: profile.php");
        exit();
    } else {
        // Password is incorrect
        $loginError = "Incorrect password";
    }

  }else if ($result->num_rows > 1){    
    //Email already used
    $loginError = "Email aready used";
} else {
    // User does not exist
    $loginError = "User does not exist";
}
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Login | Byte Galore</title>
  <link rel="icon" href="img/BYTE_GALORE_LOGO.png" type="image/x-icon">
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/reg.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    body {
      background-color: #f5f5f5;
    }

    .login-container {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fffbd0;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .login-container img {
      display: block;
      margin: 0 auto;
    }

    /* .login-container h2 {
      text-align: center;
      margin-bottom: 20px;
    } */

    .form_field {
      margin-bottom: 15px;
    }

    .form_field label {
      display: block;
      font-size: 18px;
    }

    .form_field input[type="text"],
    .form_field input[type="password"] {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border-radius:5px;
      border: 1px solid #ccc;
    }

    .submitButton {
      display: block;
      width: 100%;
      padding: 10px;
      font-size: 18px;
      color: #fff;
      background-color: #ff9900;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .text--center {
      text-align: center;
    }
    .register-link {
  text-decoration: underline;
}

  </style>
</head>
<body>
  <div class="login-container">
    <a href="index.html">
    <img class="img-fluid" src="img/BYTE_GALORE_LOGO.png" alt="" width="100" height="100" />
    </a>
    <h2 class="text-center">Login</h2>

    <?php if (isset($loginError)) { ?>
      <p style="color: red;"><?php echo $loginError; ?></p>
    <?php } ?>

    <form method="POST" class="form login">
      <div class="form_field">
        <label><i class="fa fa-envelope" style="color: #fffbd0"></i></label>
        <input type="email" name="email" class="form_input form-control" placeholder="Email" required />
      </div>
      <div class="form_field">
        <label><i class="fa fa-lock" style="color: #fffbd0"></i></label>
        <input type="password" name="password" class="form_input form-control" placeholder="Password" required />
      </div>
      <div class="form_field">
        <button class="submitButton btn btn-primary" type="submit">Submit</button>
      </div>
    </form>
    <p class="text-center">Not a member? <a href="register.php" class="register-link">Register</a></p>
  </div>
</body>
</html>