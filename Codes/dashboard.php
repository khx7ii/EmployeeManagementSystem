<?php
session_start();

if (!isset($_SESSION['person_id'])) {
  header("Location: login_admin.php");
  exit();
}
if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: login_admin.php");
  exit();
}

$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="dashboard.css">
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="brand">EMS</div>
    <a href="#">Dashboard</a>
    <a href="ManagersCRUD.php">Manage Managers</a>
    <a href="employeecrud.php">Manage Employees</a>
    <a href="depcrud.php">Manage Departments</a>
    <a href="profile.php">View Profile</a>
  </div>

  <div class="content">
    <div class="header">
      <h3>Welcome Back, <?php echo $user_name; ?></h3> 
      <div>
        <span id="currentDate"></span>
        <button class="btn-signout ms-3" onclick="logout()">Logout</button>
      </div>
    </div>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
      </ol>
    </nav>

    <div class="row">
      <div class="col-md-3 mb-4">
        <a href="ManagersCRUD.php" class="card bg-warning text-decoration-none">
          <div class="card-body">
            <h5 class="card-title">Manage Managers</h5>
          </div>
        </a>
      </div>

      <div class="col-md-3 mb-4">
        <a href="employeecrud.php" class="card bg-success text-decoration-none">
          <div class="card-body">
            <h5 class="card-title">Manage Employees</h5>
          </div>
        </a>
      </div>

      <div class="col-md-3 mb-4">
        <a href="depcrud.php" class="card bg-danger text-decoration-none">
          <div class="card-body">
            <h5 class="card-title">Manage Departments</h5>
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

    <!-- Employee Stats -->
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="stat-card bg-info">
          <h5>Total Employees</h5>
          <p>120</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="stat-card bg-success">
          <h5>Active Employees</h5>
          <p>100</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="stat-card bg-warning">
          <h5>On Leave</h5>
          <p>20</p>
        </div>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function updateDate() {
      var today = new Date();
      var options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
      var formattedDate = today.toLocaleDateString('en-GB', options);
      document.getElementById('currentDate').textContent = formattedDate;
    }

    updateDate();

    function logout() {
      window.location.href = 'login_admin.php';
    }
  </script>

</body>

</html>