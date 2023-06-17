<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the appointment ID and status from the form submission
    $appointmentId = $_POST['appointment_id'];
    $status = $_POST['status'];

    // Update the appointment with the new status
    $updateQuery = "UPDATE appointments SET status = '$status' WHERE id = $appointmentId";
    mysqli_query($conn, $updateQuery);
}

// Retrieve Appointments
$searchQueryAppointments = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$queryAppointments = "SELECT appointments.*, users.name AS user_name FROM appointments
          INNER JOIN users ON appointments.user_id = users.id
          WHERE appointments.id LIKE '%$searchQueryAppointments%' OR users.name LIKE '%$searchQueryAppointments%'";
$resultAppointments = mysqli_query($conn, $queryAppointments);
$appointments = array();

while ($row = mysqli_fetch_assoc($resultAppointments)) {
    $userId = $row['user_id'];
    $userQuery = "SELECT name FROM users WHERE id = '$userId'";
    $userResult = mysqli_query($conn, $userQuery);
    $userData = mysqli_fetch_assoc($userResult);
    $row['user_name'] = $userData['name'];

    $foodOrder = unserialize($row['food_order']);
    $foodIds = array_keys($foodOrder);
    $foodIdsString = implode(',', $foodIds);
    $foodQuery = "SELECT * FROM food_menu WHERE id IN ($foodIdsString)";
    $foodResult = mysqli_query($conn, $foodQuery);
    $foodNames = array();
    // while ($foodData = mysqli_fetch_assoc($foodResult)) {
    //     $foodId = $foodData['id'];
    //     $quantity = 0;
    //     $foodNames[] = $foodData['name'] . " (Quantity: $quantity)";
    // }
    foreach ($foodOrder as $foodItem) {
        $quantity = $foodItem['quantity'];
        $foodNames[] = $foodItem['name'] . " (Quantity: $quantity)";
    }
    $row['food_order'] = implode(', ', $foodNames);

    $appointmentDateTime = strtotime($row['appointment_date'] . ' ' . $row['appointment_time']);
    $formattedDateTime = date('F j, Y h:i A', $appointmentDateTime);
    $row['appointment_datetime'] = $formattedDateTime;

    $appointments[] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
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
        .button-container {
        display: inline-block;
        margin-right: 10px; /* Add right margin for spacing */
    }

    .btn {
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 4px;
        background-color: #e25822;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin: 3px;
    }

    .btn:hover {
        background-color: #d73c09;
    }
    </style>
    <title>Admin Dashboard - Appointments</title>
</head>
<body>
    <div class="navbar">
        <a href="admin_dashboard.php" class="active">Appointments</a>
        <a href="admin_users.php">Users</a>
        <a href="admin_contact_data.php">Messages</a>
        <a href="edit_food_menu.php">Edit Food Menu</a>
        <a href="admin_subscribers.php">Subscribers</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <div class="search-container">
        <form class="search-form" method="GET">
            <input type="text" name="search" placeholder="Search Appointments by ID or Name">
            <button type="submit">Search Appointments</button>
        </form>
    </div>

    <div class="table-container">
        <h2>Appointments</h2>
        <table id="appointments-table">
            <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>Number of People</th>
                <th>Food Order</th>
                <th>Special Request</th>
                <th>Total Order</th>
                <th>Reservation Fee</th>
                <th>Appointment Date/Time</th>
                <th>Booking Date/Time</th>
                <th>Reference Number</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?php echo $appointment['id']; ?></td>
                    <td><?php echo $appointment['user_name']; ?></td>
                    <td><?php echo $appointment['num_people']; ?></td>
                    <td><?php echo $appointment['food_order']; ?></td>
                    <td><?php echo $appointment['special_request']; ?></td>
                    <td><?php echo $appointment['total_order']; ?></td>
                    <td><?php echo $appointment['reservation_fee']; ?></td>
                    <td><?php echo $appointment['appointment_datetime']; ?></td>
                    <td><?php echo $appointment['booking_datetime']; ?></td>
                    <td><?php echo $appointment['reference_number']; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                            <select name="status">
                                <option value="Pending" <?php if ($appointment['status'] === 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Approve" <?php if ($appointment['status'] === 'Approve') echo 'selected'; ?>>Approve</option>
                                <option value="Decline" <?php if ($appointment['status'] === 'Decline') echo 'selected'; ?>>Decline</option>
                                <option value="Done" <?php if ($appointment['status'] === 'Done') echo 'selected'; ?>>Done</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>
                        <button class="btn" onclick="handleEditAppointment(<?php echo $appointment['id']; ?>)">Edit</button>
                        <button class="btn" onclick="handleDeleteAppointment(<?php echo $appointment['id']; ?>)">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script>
    function handleEditAppointment(appointmentId) {
        // Redirect to the edit appointment page
        window.location.href = 'edit_appointment.php?id=' + appointmentId;
    }

    function handleDeleteAppointment(appointmentId) {
        // Show an alert before deleting the appointment
        if (confirm('Are you sure you want to delete this appointment?')) {
            // Call the delete appointment API or perform necessary actions
            // You can use JavaScript's Fetch API or make an AJAX request here
            // Example:
            fetch('delete_appointment.php?id=' + appointmentId)
                .then(response => {
                    // Handle the response accordingly
                    // Reload the page or update the UI if needed
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }
</script>

</body>
</html>