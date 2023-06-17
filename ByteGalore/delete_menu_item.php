<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');

// Check if the item ID is provided
if (isset($_GET['id'])) {
    $itemId = $_GET['id'];

    // Delete the menu item from the database
    $deleteQuery = "DELETE FROM food_menu WHERE id = $itemId";
    mysqli_query($conn, $deleteQuery);

    // Redirect to the edit food menu page
    header('Location: edit_food_menu.php');
    exit();
}

mysqli_close($conn);
?>
