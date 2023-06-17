<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit();
}

date_default_timezone_set('Asia/Manila'); // Set the timezone to Philippine Standard Time

// Connect to the database
$conn = mysqli_connect('localhost', 'root', 'root', 'bytegalore');

if (!$conn) {
    die("Failed to connect to the database: " . mysqli_connect_error());
}

$userId = $_SESSION['user_id'];

// Retrieve user information from the database
$query = "SELECT * FROM users WHERE id = '$userId'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);

// Assign user information to variables
$userName = $user['name'];
$userEmail = $user['email'];
$userContactNumber = $user['contact_number'];

// Retrieve food menu from the database
$query = "SELECT * FROM food_menu";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$foodMenu = mysqli_fetch_all($result, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and process reservation
    $numPeople = mysqli_real_escape_string($conn, $_POST['numPeople']);
    $foodOrder = $_POST['foodOrder'];
    $quantity = $_POST['quantity'];
    $specialRequest = mysqli_real_escape_string($conn, $_POST['specialRequest']);
    $appointmentDate = mysqli_real_escape_string($conn, $_POST['appointmentDate']);
    $appointmentTime = mysqli_real_escape_string($conn, $_POST['appointmentTime']);

    // Compute the total order and quantity
    $totalOrder = 0;
    $quantityTotal = 0;
    $selectedFoodDetails = [];

    foreach ($foodOrder as $index => $foodId) {
        // Find the selected food item in the food menu
        foreach ($foodMenu as $foodItem) {
            if ($foodItem['id'] == $foodId) {
                // Add the price of the selected food item multiplied by the quantity to the total order
                $price = $foodItem['price'];
                $selectedFoodDetails[] = [
                    'name' => $foodItem['name'],
                    'quantity' => $quantity[$foodId - 1]
                ];
                $totalOrder += $price * $quantity[$foodId - 1];
                $quantityTotal += $quantity[$foodId - 1];
                break;
            }
        }
    }

    // Calculate the reservation fee (20% of the total order)
    $reservationFee = $totalOrder * 0.2;

    // Serialize the food order array
    $foodOrderSerialized = serialize($selectedFoodDetails);

    // Get the current booking date and time
    $bookingDatetime = date('Y-m-d H:i:s');

    // Save appointment data to the database
    $query = "INSERT INTO appointments (user_id, num_people, food_order, quantity, special_request, total_order, reservation_fee, appointment_date, appointment_time, booking_datetime) VALUES ('$userId', '$numPeople', '$foodOrderSerialized', '$quantityTotal', '$specialRequest', '$totalOrder', '$reservationFee', '$appointmentDate', '$appointmentTime', '$bookingDatetime')"; // Added reference number

    if (!mysqli_query($conn, $query)) {
        die("Error: " . mysqli_error($conn));
    }

    // Redirect to a confirmation page or display a success message
    header('Location: confirmationBR.php');
    exit();
}

// Retrieve previous appointment records
$query = "SELECT * FROM appointments WHERE user_id = '$userId' ORDER BY appointment_date DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$appointments = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($conn);

$currentDate = date('F j, Y'); // Get the current date in the format: Month Day, Year
$currentTime = date('h:i A'); // Get the current time in the format: Hour:Minutes AM/PM

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
    <title>Byte Galore</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="keywords" />
    <meta content="" name="description" />

    <link rel="icon" href="img/BYTE_GALORE_LOGO.png" type="image/x-icon">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon" />

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lily+Script+One&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet"

    />

    <!-- Icon Font Stylesheet -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" 
    />


    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet" />
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />
    <link
      href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css"
      rel="stylesheet"
    />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet" />
    <style>
        .hidden {
            display: none;
        }

        .text-primary{
            color: #e25822;
        }
    </style>
</head>
<body>
      <!-- Navbar & Hero Start -->
      <div class="container-xxl position-relative p-0">
        <nav
          class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0"
        >
          <a href="" class="navbar-brand p-0">
            <h1 class="text-primary m-0">Byte Galore
            </h1>
            <!-- <img src="img/logo.png" alt="Logo"> -->
          </a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarCollapse"
          >
            <span class="fa fa-bars"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0 pe-4">
              <!-- <a href="login.html" class="nav-item nav-link">Login</a> -->
              <a href="indexlogged.html" class="nav-item nav-link">Home</a>
              <a href="aboutlogged.html" class="nav-item nav-link">About</a>
              <a href="servicelogged.html" class="nav-item nav-link">Service</a>
              <a href="menulogged.html" class="nav-item nav-link">Menu</a>
              <div class="nav-item dropdown">
                <a
                  href="#"
                  class="nav-link dropdown-toggle active"
                  data-bs-toggle="dropdown"
                  >Pages</a
                >
                <div class="dropdown-menu m-0">
                  <a href="profile.php" class="dropdown-item">Booking</a>
                  <a href="teamlogged.html" class="dropdown-item">Our Team</a>
                  <a href="testimoniallogged.html" class="dropdown-item"
                    >Testimonial</a
                  >
                  <a href="login.php" class="dropdown-item">Log out</a>
                </div>
              </div>
              <a href="contact.php" class="nav-item nav-link">Contact</a>
            </div>
            <a href="profile.php" class="btn btn-primary py-2 px-4"
              >Book A Table</a
            >
          </div>
        </nav>

        <div class="container-xxl py-5 bg-dark hero-header mb-5">
          <div class="container text-center my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">
              Profile|Booking
            </h1>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a href="indexlogged.html">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li
                  class="breadcrumb-item text-white active"
                  aria-current="page"
                >
                  Booking
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
      <!-- Navbar & Hero End -->


   
    <div class="container";>
       <center>
        <?php
        $hour = date('H');
        if ($hour < 12) {
            $greeting = 'Good morning';
        } elseif ($hour < 18) {
            $greeting = 'Good afternoon';
        } else {
            $greeting = 'Good evening';
        }
        ?>
            <h2><?php echo $greeting; ?>, <?php echo $userName; ?>!</h2>
    <p>Current date: <?php echo $currentDate; ?></p>
    <p>Current time: <?php echo $currentTime; ?></p>
    </center>
    <button id="bookAppointmentBtn" onclick="toggleForm()" class="btn btn-primary">Book an Appointment</button>
    <div id="appointmentForm" class="hidden">
        <form method="POST" action="profile.php">
            <p>Name: <?php echo $userName; ?></p>
            <p>Email: <?php echo $userEmail; ?></p>
            <p>Contact Number: <?php echo $userContactNumber; ?></p>
            <div class="form-group">
                <input type="number" name="numPeople" placeholder="Number of People" required class="form-control">
            </div>
            <p>Food Order:</p>
            <?php foreach ($foodMenu as $foodItem) : ?>
              <div class="form-check" style="width:100%">
                <input type="checkbox" name="foodOrder[]" value="<?php echo $foodItem['id']; ?>" data-price="<?php echo $foodItem['price']; ?>" class="form-check-input">
                <label class="form-check-label"><?php echo $foodItem['name']; ?> (PHP<?php echo $foodItem['price']; ?>)</label>
                <input type="number" name="quantity[]" value="0" placeholder="Quantity" required class="form-control">
            </div>
            <br>
              <?php endforeach; ?>
            <div class="form-group">
                <textarea name="specialRequest" placeholder="Special Request" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>
                    Appointment Date:
                    <input type="date" name="appointmentDate" required class="form-control">
                </label>
            </div>
            <div class="form-group">
                <label>
                    Appointment Time:
                    <input type="time" name="appointmentTime" required class="form-control">
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Make Reservation</button>
        </form>

        <p>Total Order: PHP <span id="totalOrder">0.00</span></p>
        <p>Reservation Fee (20%): PHP <span id="reservationFee">0.00</span></p>
    </div>

    <a href="login.php" class="btn btn-primary">Logout</a>

    <h2>Previous Appointments</h2>
    <button id="showAppointmentsBtn" onclick="toggleAppointments()" class="btn btn-primary">Show Previous Appointments</button>
    <table id="appointmentsTable" class="hidden table">
        <thead>
            <tr>
                <th>Appointment Date</th>
                <th>Appointment Time</th>
                <th>Number of People</th>
                <th>Total Order</th>
                <th>Reservation Fee</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appointment) : ?>
                <tr>
                    <td><?php echo date('F j, Y', strtotime($appointment['appointment_date'])); ?></td>
                    <td><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></td>
                    <td><?php echo $appointment['num_people']; ?></td>
                    <td>PHP <?php echo $appointment['total_order']; ?></td>
                    <td>PHP <?php echo $appointment['reservation_fee']; ?></td>
                    <td><?php echo $appointment['status']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function toggleForm() {
        var form = document.getElementById('appointmentForm');
        form.classList.toggle('hidden');
    }

    function toggleAppointments() {
        var table = document.getElementById('appointmentsTable');
        table.classList.toggle('hidden');
    }

    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', updateTotalOrder);
    });

    function updateTotalOrder() {
        var totalOrder = 0;
        var quantityInputs = document.querySelectorAll('input[name="quantity[]"]');
        checkboxes.forEach(function(checkbox, index) {
            if (checkbox.checked) {
                var price = parseFloat(checkbox.getAttribute('data-price'));
                var quantity = parseInt(quantityInputs[index].value);
                totalOrder += price * quantity;
            }
        });

        document.getElementById('totalOrder').textContent = totalOrder.toFixed(2);

        var reservationFee = totalOrder * 0.2;
        document.getElementById('reservationFee').textContent = reservationFee.toFixed(2);
    }
</script>

      <!-- Footer Start -->
      <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
  <div class="container py-5">
    <div class="row g-5">
      <div class="col-lg-3 col-md-6">
        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Company</h4>
        <a class="btn btn-link" href="aboutlogged.html">About Us</a>
        <a class="btn btn-link" href="contact.php">Contact Us</a>
        <a class="btn btn-link" href="profile.php">Reservation</a>
        <a class="btn btn-link" href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>
        <a class="btn btn-link" href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms & Conditions</a>
      </div>
      <div class="col-lg-3 col-md-6">
        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Contact</h4>
        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>General Luna, corner Muralla St, Intramuros, Manila, 1002 Metro Manila</p>
        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>0915 528 9644</p>
        <p class="mb-2"><i class="fa fa-envelope me-3"></i>galorebyte@gmail.com</p>
        <div class="d-flex pt-2">
          <a class="btn btn-outline-light btn-social" href="https://twitter.com/Byte_Galore"><i class="fab fa-twitter"></i></a>
          <a class="btn btn-outline-light btn-social" href="https://www.facebook.com/bytegalore"><i class="fab fa-facebook-f"></i></a>
          <a class="btn btn-outline-light btn-social" href="https://www.youtube.com/@ByteGalore"><i class="fab fa-youtube"></i></a>
          <a class="btn btn-outline-light btn-social" href="https://github.com/ByteGalore"><i class="fab fa-github"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Opening</h4>
        <h5 class="text-light fw-normal">Monday - Saturday</h5>
        <p>09AM - 09PM</p>
        <h5 class="text-light fw-normal">Sunday</h5>
        <p>10AM - 08PM</p>
      </div>
      <div class="col-lg-3 col-md-6">
        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Subscribe</h4>
        <p>Subscribe to our newsletter for updates and promotions:</p>
        <form class="subscribe-form" method="post" action="subscribe.php">
          <div class="input-group">
            <input type="email" class="form-control" name="email" placeholder="Your email address" aria-label="Your email address" aria-describedby="subscribe-btn">
            <button class="btn btn-primary" type="submit" id="subscribe-btn">Subscribe</button>
          </div>
        </form>
      </div>      
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
        &copy; <a class="border-bottom" href="#">2023 Byte Galore</a>, All Rights Reserved.
      </div>
      <div class="col-md-6 text-center text-md-end">
        <div class="footer-menu">
          <a href="indexlogged.html">Home</a>
          <a href="">Cookies</a>
          <a href="">Help</a>
          <a href="admin_login.php">Admin</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsModalLabel">Terms & Conditions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6>1. Introduction</h6>
        <p>Welcome to Byte Galore! These terms and conditions outline the rules and regulations for the use of our website.</p>
        
        <h6>2. Intellectual Property Rights</h6>
        <p>Unless otherwise stated, we own the intellectual property rights for all the material on our website. All intellectual property rights are reserved.</p>
        
        <h6>3. Restrictions</h6>
        <p>You are specifically restricted from the following:</p>
        <ul>
          <li>publishing any website material in any other media;</li>
          <li>selling, sublicensing, and/or otherwise commercializing any website material;</li>
          <li>publicly performing and/or showing any website material;</li>
          <li>using our website in any way that is or may be damaging to this website;</li>
          <li>using our website contrary to applicable laws and regulations, or in a way that causes, or may cause, harm to the website, or to any person or business entity;</li>
          <li>engaging in any data mining, data harvesting, data extracting, or any other similar activity in relation to our website;</li>
          <li>using our website to engage in any advertising or marketing.</li>
        </ul>
        
        <h6>4. Limitation of Liability</h6>
        <p>In no event shall Byte Galore, nor any of its officers, directors, and employees, be held liable for anything arising out of or in any way connected with your use of this website.</p>
        
        <h6>5. Indemnification</h6>
        <p>You hereby indemnify to the fullest extent Byte Galore from and against any and all liabilities, costs, demands, causes of action, damages, and expenses arising in any way related to your breach of any of the provisions of these terms and conditions.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Privacy Policy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Insert your privacy policy content here -->
        <h6>Information We Collect</h6>
        <p>We collect information from you when you subscribe to our newsletter or fill out a form. The information we collect may include your name and email address.</p>
        
        <h6>How We Use Your Information</h6>
        <p>We may use the information we collect from you to send periodic emails regarding updates, promotions, and other relevant information.</p>
        
        <h6>How We Protect Your Information</h6>
        <p>We implement a variety of security measures to maintain the safety of your personal information when you enter, submit, or access your personal information.</p>
        
        <h6>Third Party Disclosure</h6>
        <p>We do not sell, trade, or otherwise transfer your personally identifiable information to outside parties.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Footer End -->

      <!-- Back to Top -->
      <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"
        ><i class="bi bi-arrow-up"></i
      ></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
  </body>
</html>
