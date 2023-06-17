<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the item ID, new name, and new price are provided
    if (isset($_POST['item_id'], $_POST['new_name'], $_POST['new_price'])) {
        $itemId = $_POST['item_id'];
        $newName = $_POST['new_name'];
        $newPrice = $_POST['new_price'];

        // Update the menu item with the new name and price
        $updateQuery = "UPDATE food_menu SET name = '$newName', price = '$newPrice' WHERE id = $itemId";
        mysqli_query($conn, $updateQuery);
    }
}

// Retrieve menu items
$query = "SELECT * FROM food_menu";
$result = mysqli_query($conn, $query);
$menuItems = array();

while ($row = mysqli_fetch_assoc($result)) {
    $menuItems[] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<style>
        /* Styles for the navigation bar */
        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .navbar a.active {
            background-color: #e25822;
            color: white;
        }

        /* Styles for the table */
.table-container {
  margin-top: 20px;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  text-align: left;
  padding: 8px;
  transition: background-color 0.3s ease;
}

th {
  background-color: #e25822;
  color: white;
}

tr:nth-child(odd) {
  background-color: #f5f5f5;
  transition: background-color 0.3s ease;
}

tr:nth-child(even) {
  background-color: #eaeaea;
  transition: background-color 0.3s ease;
}

tr:hover {
  background-color: #f1f1f1;
  animation: hover-animation 0.3s ease;
}

th:hover {
  background-color: #d73c09;
  animation: hover-animation 0.3s ease;
}

@keyframes hover-animation {
  0% {
    background-color: #f1f1f1;
  }
  100% {
    background-color: #faa082;
    color: white;
  }
}

        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .edit-button {
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 4px;
        background-color: #e25822;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-right: 10px;
    }

    .edit-button:hover {
        background-color: #d73c09;
    }

    .delete-button {
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 4px;
        background-color: #e25822;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .delete-button:hover {
        background-color: #d73c09;
    }

    .add {
        text-align: center;
        margin-top: 20px;
    }

    .add a {
        display: inline-block;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 4px;
        background-color: #e25822;
        color: white;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .add a:hover {
        background-color: #d73c09;
    }

        /* Styles for the search bar */
        .search-container {
            margin-top: 20px;
            text-align: center;
        }

        .search-form input[type=text] {
            padding: 6px;
            width: 300px;
            font-size: 16px;
        }

        .search-form button {
            padding: 6px 12px;
            background-color: #e25822;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }
    </style>
    <title>Edit Food Menu</title>
</head>
<body>
    
    <div class="navbar">
        <a href="admin_dashboard.php" onclick="showAppointments()">Appointments</a>
        <a href="admin_dashboard.php#users" onclick="showUsers()">Users</a>
        <a href="admin_contact_data.php">Messages</a>
        <a href="edit_food_menu.php" class="active">Edit Food Menu</a>
        <a href="admin_subscribers.php">Subscribers</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <div class="add">
        <br><br>
        <a href="add_food_menu.php">Add Food Menu</a>
    </div>

    <div class="table-container">
        <h2>Edit Food Menu</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php foreach ($menuItems as $menuItem): ?>
                <tr>
                    <td><?php echo $menuItem['id']; ?></td>
                    <td><?php echo $menuItem['name']; ?></td>
                    <td><?php echo $menuItem['price']; ?></td>
                    <td>
                        <button class="edit-button" onclick="showEditForm(<?php echo $menuItem['id']; ?>, '<?php echo $menuItem['name']; ?>', '<?php echo $menuItem['price']; ?>')">Edit</button>
                        <button class="delete-button" onclick="handleDeleteMenuItem(<?php echo $menuItem['id']; ?>)">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div id="edit-form" style="display: none;">
            <h3>Edit Menu Item</h3>
            <form method="post" action="edit_food_menu.php">
                <input type="hidden" id="item-id" name="item_id">
                <label for="new-name">Name:</label>
                <input type="text" id="new-name" name="new_name">
                <label for="new-price">Price:</label>
                <input type="text" id="new-price" name="new_price">
                <button type="submit">Save</button>
                <button type="button" onclick="cancelEditForm()">Cancel</button>
            </form>
        </div>
    </div>

    <script>

function showEditForm(itemId, itemName, itemPrice) {
    Swal.fire({
        title: 'Edit Menu Item',
        html:
            '<div class="swal2-content">' +
                '<div class="swal2-input-group">' +
                    '<label for="swal-input1">Name:</label>' +
                    '<input id="swal-input1" class="swal2-input" value="' + itemName + '">' +
                '</div>' +
                '<div class="swal2-input-group">' +
                    '<label for="swal-input2">Price:</label>' +
                    '<input id="swal-input2" class="swal2-input" value="' + itemPrice + '">' +
                '</div>' +
            '</div>',
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Save',
        confirmButtonColor: '#e25822', 
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const updatedName = Swal.getPopup().querySelector('#swal-input1').value;
            const updatedPrice = Swal.getPopup().querySelector('#swal-input2').value;

            // Check if the input fields are not empty
            if (!updatedName.trim() || !updatedPrice.trim()) {
                Swal.showValidationMessage('Please enter both name and price');
                return false;
            }

            // Update the menu item details
            document.getElementById('item-id').value = itemId;
            document.getElementById('new-name').value = updatedName;
            document.getElementById('new-price').value = updatedPrice;

            // Submit the form (optional)
            document.querySelector('#edit-form form').submit();

            return true;
        }
    });
}



        function cancelEditForm() {
            document.getElementById('edit-form').style.display = 'none';
        }

        function handleDeleteMenuItem(itemId) {
    // Show an alert confirmation
    Swal.fire({
        title: 'Delete Menu Item',
        text: 'Are you sure you want to delete this item?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e25822',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Call the delete menu item API or perform necessary actions
            // You can use JavaScript's Fetch API or make an AJAX request here
            // Example:
            fetch('delete_menu_item.php?id=' + itemId)
                .then(response => {
                    // Handle the response accordingly
                    // Reload the page or update the UI if needed
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    });
}

    </script>
</body>
</html>
