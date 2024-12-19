<?php
// Start the session to track user data
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['person_id'])) {
  // Redirect to login page if user is not logged in
  header("Location: login_admin.php");
  exit();
}

if (isset($_GET['logout'])) {
  // Destroy the session and redirect to the login page
  session_destroy();
  header("Location: login_admin.php");
  exit();
}

// Database credentials
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$host = "localhost"; // Usually localhost for local development
$dbname = "emp"; // Replace with your database name

// Create a PDO instance for database connection
try {
  $database = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8", $username, $password);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

// Get user data from session
$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['person_id'];

// Query to get department_id and employee_id from the employee table using person_id
$query = "SELECT employee_id, department_id FROM employee WHERE person_id = :person_id";
$stmt = $database->prepare($query);
$stmt->bindParam(':person_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user_data) {
  $employee_id = $user_data['employee_id'];
  $department_id = $user_data['department_id'];
} else {
  die("User not found in the employee table.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Check if form fields are set and not empty
  if (
    !isset($_POST['vacation_reason'], $_POST['start_date'], $_POST['end_date']) ||
    empty($_POST['vacation_reason']) || empty($_POST['start_date']) || empty($_POST['end_date'])
  ) {
    $_SESSION['message'] = "Please fill in all fields.";
    $_SESSION['message_type'] = "alert-danger"; // Bootstrap error class
  } else {
    // Sanitize input values
    $vacation_reason = trim($_POST['vacation_reason']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Insert the vacation request into the database
    try {
      $query = "INSERT INTO vacations (employee_id, causes, start_date, end_date, status, department_id) 
                    VALUES (:employee_id, :causes, :start_date, :end_date, 'Pending' , :department_id)";
      $stmt = $database->prepare($query);
      $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
      $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
      $stmt->bindParam(':causes', $vacation_reason, PDO::PARAM_STR);
      $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
      $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);

      if ($stmt->execute()) {
        $_SESSION['message'] = "Vacation request submitted successfully!";
        $_SESSION['message_type'] = "alert-success"; // Bootstrap success class
      } else {
        $errorInfo = $stmt->errorInfo(); // Get error information
        $_SESSION['message'] = "Error submitting request: " . $errorInfo[2];
        $_SESSION['message_type'] = "alert-danger"; // Bootstrap error class
      }
    } catch (PDOException $e) {
      $_SESSION['message'] = "Database error: " . $e->getMessage();
      $_SESSION['message_type'] = "alert-danger";
    }
  }
  header("Location: requestedVacation.php"); // Redirect after form submission
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request Vacation</title>
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
      margin-left: 20px;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .header .btn-signout:hover {
      background-color: #c0392b;
      margin-left: 20px;
    }


    .form-container {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
    }

    .table-container {
      margin-top: 20px;
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
  </style>
</head>

<body>

  <div class="sidebar">
    <div class="brand">EMS</div>
    <a href="dashboard_emp.php">Dashboard</a>
    <a href="assigned_tasks.php">Assigned Tasks</a>
    <a href="RequestVacation.php">Request Vacation</a>
    <a href="requestedVacation.php">Requested Vacations</a>
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

    <div class="form-container">
      <h4>Submit a Vacation Request</h4>
      <?php if (!empty($message)): ?>
        <!-- Blue rectangle for feedback message -->
        <div id="feedbackMessage" class="alert <?php echo $message_type; ?>" role="alert">
          <?php echo $message; ?>
        </div>
      <?php endif; ?>
      <form method="POST">
        <div class="mb-3">
          <label for="reason" class="form-label">Reason for Vacation</label>
          <textarea class="form-control" id="reason" name="vacation_reason" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label for="start_date" class="form-label">Start Date</label>
          <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>
        <div class="mb-3">
          <label for="end_date" class="form-label">End Date</label>
          <input type="date" class="form-control" id="end_date" name="end_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Request</button>
      </form>
    </div>


  </div>
  <script>
    // Wait until the DOM is fully loaded before executing the script
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

    // Hide feedback message after 5 seconds
    setTimeout(() => {
      const feedbackMessage = document.getElementById('feedbackMessage');
      if (feedbackMessage) {
        feedbackMessage.classList.add('d-none');
      }
    }, 5000);
  </script>

</body>

</html>