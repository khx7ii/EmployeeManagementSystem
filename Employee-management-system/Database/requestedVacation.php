<?php
// Start the session
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['person_id'])) {
  header("Location: login_admin.php");
  exit();
}

$username = "root";
$password = "";
$host = "localhost";
$dbname = "emp";

try {
  $database = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8", $username, $password);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['person_id'];

// Step 1: Fetch employee_id from employees table using person_id
$query = "
    SELECT employee_id
    FROM employee
    WHERE person_id = :person_id
";
$stmt = $database->prepare($query);
$stmt->bindParam(':person_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$employee_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($employee_data) {
  $employee_id = $employee_data['employee_id'];
} else {
  die("Employee not found.");
}

// Step 2: Fetch vacation data for the employee using employee_id
$query = "
    SELECT 
        vac.vacation_id,
        vac.causes,
        vac.start_date,
        vac.end_date,
        vac.status
    FROM vacations vac
    WHERE vac.employee_id = :employee_id
";
$stmt = $database->prepare($query);
$stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
$stmt->execute();

// Fetch vacation data
$vacations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
  <title>
    Requested Vacations
  </title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="requestedVacation.css">
</head>

<body>
  <div class="sidebar">
    <div class="brand">EMS</div>
    <a href="dashboard_emp.php">Dashboard</a>
    <a href="assigned_tasks.php">Assigned Tasks</a>
    <a href="RequestVacation.php">Request Vacation</a>
    <a href="#">Requested Vacations</a>
    <a href="profile.php">View Profile</a>
  </div>
  <div class="content">
    <div class="header">
      <h3>Welcome Back, <?php echo htmlspecialchars($user_name); ?></h3>
      <div>
        <span id="currentDate"></span>
        <button class="btn-signout" onclick="logout()">Logout</button>
      </div>
    </div>
    <div class="container" style="align-items: center; padding-left: 50px;">
      <h2>Requested Vacations</h2>
    </div>
    <table class="table table-striped" style="background-color: #fff;">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Reason</th>
          <th scope="col">Start Date</th>
          <th scope="col">End Date </th>
          <th scope="col">Status </th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (count($vacations) > 0) {
          $count = 1;
          foreach ($vacations as $vacation) {
            echo "<tr>";
            echo "<td>" . $count . "</td>";
            echo "<td>" . htmlspecialchars($vacation['causes']) . "</td>";
            echo "<td>" . htmlspecialchars($vacation['start_date']) . "</td>";
            echo "<td>" . htmlspecialchars($vacation['end_date']) . "</td>";
            echo "<td>" . htmlspecialchars($vacation['status']) . "</td>";
            echo "</tr>";
            $count++;
          }
        } else {
          echo "<tr><td colspan='5' class='text-center'>No vacation requests found</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <script>
    // Display the current date
    function updateDate() {
      const today = new Date();
      const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
      document.getElementById('currentDate').textContent = today.toLocaleDateString('en-GB', options);
    }
    updateDate();

    // Handle logout
    function logout() {
      window.location.href = 'login_admin.php';
    }
  </script>
</body>

</html>