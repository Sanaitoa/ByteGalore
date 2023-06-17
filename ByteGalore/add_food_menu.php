<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the name and price are provided
    if (isset($_POST['new_item_name'], $_POST['new_item_price'])) {
        $name = $_POST['new_item_name'];
        $price = $_POST['new_item_price'];

        // Insert the new menu item into the database
        $insertQuery = "INSERT INTO food_menu (name, price) VALUES ('$name', '$price')";
        mysqli_query($conn, $insertQuery);

        // Redirect back to the edit_food_menu.php page
        header('Location: edit_food_menu.php');
        exit();
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        /* Styles for the form */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .form-container {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 3px;
            border: 1px solid #ccc;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #e25822;
            outline: none;
        }

        button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #e25822;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #c4411b;
        }

        .cancel-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #888;
            text-decoration: none;
        }
    </style>
    <title>Add Menu Item</title>
</head>
<body>
    <div class="form-container">
        <h2>Add Menu Item</h2>
        
        <form method="post">
            <label for="new-item-name">Name:</label>
            <input type="text" id="new-item-name" name="new_item_name">
            <label for="new-item-price">Price:</label>
            <input type="text" id="new-item-price" name="new_item_price">
            <button type="submit">Add</button>
        </form>
        
        <a class="cancel-link" href="edit_food_menu.php">Cancel</a>
    </div>
</body>
</html>
