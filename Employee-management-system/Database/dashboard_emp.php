<?php
// Start the session to track user data (you must include session_start() at the beginning of the file)
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['person_id'])) {
  // Redirect to login page if user is not logged in
  header("Location: login_admin.php");
  exit();
}

// Handle logout request
if (isset($_GET['logout'])) {
  // Destroy the session and redirect to the login page
  session_destroy();
  header("Location: login_admin.php");
  exit();
}

// Assuming database connection is already established
// require_once "connection.php"; // Include your database connection file

// Get the employee's name from the session
$user_name = $_SESSION['user_name']; // Assuming the user's name is stored in the session
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f6f9;
      color: #333;
    }

    /* Sidebar */
    .sidebar {
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      background-color: #34495e;
      color: white;
      padding-top: 30px;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      font-size: 18px;
      padding: 15px 20px;
      display: block;
      transition: all 0.3s ease;
    }

    .sidebar a:hover {
      background-color: #2c3e50;
      border-radius: 5px;
      transform: scale(1.05);
    }

    .sidebar .brand {
      font-size: 24px;
      font-weight: bold;
      color: #ecf0f1;
      text-align: left;
      margin-left: 20px;
      margin-bottom: 30px;
    }

    .content {
      margin-left: 270px;
      padding: 30px;
      margin-top: 30px;
    }

    /* Header Section */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
    }

    .header h3 {
      font-size: 22px;
      color: #2c3e50;
    }

    .header .btn-signout {
      background-color: #e74c3c;
      color: white;
      border-radius: 5px;
      font-size: 14px;
      padding: 8px 12px;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .header .btn-signout:hover {
      background-color: #c0392b;
    }

    /* Breadcrumb */
    .breadcrumb {
      background-color: transparent;
      padding: 0;
      margin-bottom: 20px;
    }

    .breadcrumb-item a {
      color: #3498db;
    }

    .breadcrumb-item.active {
      color: #2c3e50;
    }

    /* Navigation Cards */
    .card {
      border-radius: 10px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
    }

    .card .card-body {
      padding: 30px;
      text-align: center;
    }

    .card .card-title {
      font-size: 20px;
      font-weight: bold;
      color: white;
    }

    /* Stats Section */
    .stat-card {
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      color: white;
    }

    .stat-card h5 {
      font-size: 20px;
      font-weight: bold;
    }

    .stat-card p {
      font-size: 24px;
      font-weight: bold;
    }

    .bg-info {
      background-color: #3498db !important;
    }

    .bg-success {
      background-color: #27ae60 !important;
    }

    .bg-warning {
      background-color: #f39c12 !important;
    }

    .bg-danger {
      background-color: #e74c3c !important;
    }

    /* Responsive Layout */
    @media (max-width: 768px) {
      .sidebar {
        width: 200px;
      }

      .content {
        margin-left: 220px;
      }

      .header {
        flex-direction: column;
        align-items: flex-start;
      }

      .header h3 {
        font-size: 20px;
      }

      .header .btn-signout {
        margin-top: 10px;
      }
    }

    .row {
      margin-top: 30px;
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="brand">EMS</div>
    <a href="#">Dashboard</a>
    <a href="assigned_tasks.php">Assigned Tasks</a>
    <a href="RequestVacation.php">Request Vacation</a>
    <a href="requestedVacation.php">Requested Vacations</a>
    <a href="profile.php">View Profile</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <!-- Header Section -->
    <div class="header">
      <h3>Welcome Back, <?php echo $user_name; ?></h3> <!-- Displaying the employee's name -->
      <div>
        <!-- Span for displaying current date -->
        <span id="currentDate"></span>
        <!-- Logout Button with JavaScript Redirect -->
        <button class="btn-signout ms-3" onclick="logout()">Logout</button>
      </div>
    </div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
      </ol>
    </nav>

    <!-- Navigation Cards -->
    <div class="row">

      <div class="col-md-3 mb-4">
        <a href="assigned_tasks.php" class="card bg-danger text-decoration-none">
          <div class="card-body">
            <h5 class="card-title">Assigned Tasks</h5>
          </div>
        </a>
      </div>

      <div class="col-md-3 mb-4">
        <a href="RequestVacation.php" class="card bg-warning text-decoration-none">
          <div class="card-body">
            <h5 class="card-title">Request Vacation</h5>
          </div>
        </a>
      </div>

      <div class="col-md-3 mb-4">
        <a href="requestedVacation.php" class="card bg-success text-decoration-none">
          <div class="card-body">
            <h5 class="card-title">Requested Vacations</h5>
          </div>
        </a>
      </div>


      <div class="col-md-3 mb-4">
        <a href="profile.php" class="card bg-info text-decoration-none">
          <div class="card-body">
            <h5 class="card-title">View Profile</h5>
          </div>
        </a>
      </div>
    </div>


  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Function to get the current date in the format: Tue, 3 Dec 2024
    function updateDate() {
      var today = new Date();
      var options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
      var formattedDate = today.toLocaleDateString('en-GB', options);
      document.getElementById('currentDate').textContent = formattedDate;
    }

    // Call the function on page load
    updateDate();

    // JavaScript function to redirect to login page when user logs out
    function logout() {
      window.location.href = 'login_admin.php'; // Redirect to the login page
    }
  </script>

</body>

</html>