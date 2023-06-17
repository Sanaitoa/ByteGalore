<?php
session_start();

// Destroy the admin session
session_destroy();

// Redirect to the admin login page
header('Location: admin_login.php');
exit();
?>
