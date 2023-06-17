<!DOCTYPE html>
<html>
<head>
<link rel="icon" href="img/BYTE_GALORE_LOGO.png" type="image/x-icon">
    <title>Admin Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom Styles -->
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #e25822;
        }

        .login-container {
            max-width: 400px;
            padding: 20px;
            background-color: #f7f7f7;
            border: 1px solid #e5e5e5;
            border-radius: 5px;
        }

        .login-container h1 {
            margin-bottom: 30px;
            text-align: center;
        }

        .login-container .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .login-container .logo img {
            width: 150px;
            height: auto;
        }

        .login-container form label {
            font-weight: bold;
        }

        .login-container form button[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 3px;
            font-size: 16px;
            cursor: pointer;
        }

        .login-container form button[type="submit"]:hover {
            background-color: #111;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="img/BYTE_GALORE_LOGO.png" alt="Byte Galore Logo">
        </div>
        <h1>Admin Login</h1>
        <?php
        session_start();
        if (isset($_SESSION['login_error'])) {
            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['login_error'] . '</div>';
            unset($_SESSION['login_error']);
        }
        ?>
        <form action="admin_authenticate.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <center>
            <a href="index.html" style= "color:#e25822;">Back to ByteGalore</a>
            </center>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
