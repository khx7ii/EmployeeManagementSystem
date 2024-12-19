<?php
// Ensure the user is logged in
session_start(); // Start the session
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
    $database = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get the logged-in user's person_id
$person_id = $_SESSION['person_id']; // Assuming person_id is the logged-in user's ID
$user_name = $_SESSION['user_name'];

// Fetch the manager_id from the manager table where the person_id matches
$sql_manager = "
SELECT
    manager_id
FROM
    manager
WHERE
    person_id = :person_id"; // We are matching person_id to get the manager_id

$stmt_manager = $database->prepare($sql_manager);
$stmt_manager->bindParam(':person_id', $person_id, PDO::PARAM_INT);
$stmt_manager->execute();
$manager = $stmt_manager->fetch(PDO::FETCH_ASSOC);

if ($manager) {
    // Manager's ID
    $manager_id = $manager['manager_id'];
} else {
    // If the manager is not found for the given person_id
    $_SESSION['error'] = "Manager not found.";
    header("Location: login_admin.php");
    exit();
}

// Fetch the department_id for the logged-in manager from the department table
$sql_department = "
SELECT
    department_id
FROM
    department
WHERE
    manager_id = :manager_id";  // Fetch department_id where manager_id matches

$stmt_department = $database->prepare($sql_department);
$stmt_department->bindParam(':manager_id', $manager_id, PDO::PARAM_INT);
$stmt_department->execute();
$department = $stmt_department->fetch(PDO::FETCH_ASSOC);

if ($department) {
    // Manager's department_id
    $manager_department_id = $department['department_id'];
} else {
    // If the manager doesn't have a department assigned
    $_SESSION['error'] = "Manager's department not found.";
    header("Location: login_admin.php");
    exit();
}

$sql_employees = "
SELECT e.person_id
FROM employee e
WHERE e.department_id = :department_id";  // Fetch person_id for employees in this department

$stmt_employees = $database->prepare($sql_employees);
$stmt_employees->bindParam(':department_id', $manager_department_id, PDO::PARAM_INT);
$stmt_employees->execute();
$employees = $stmt_employees->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html>

<head>
    <title>
        View Employees
    </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="requestedVacation.css">
</head>

<body>
    <div class="sidebar">
        <div class="brand">EMS</div>
        <a href="dashboard_man.php">Dashboard</a>
        <a href="profile.php">View Profile</a>
        <a href="#">View All Employees</a>
        <a href="assignTasks.php">Assign Tasks</a>
        <a href="retrieve_employees.php">Mark Absence</a>
        <a href="Requested_vacations_manager.php">Requested Vacations</a>
        <a href="add_report.php">Generate Reports</a>
    </div>
    <div class="content">
        <div class="header">
            <h3>Welcome Back, <?php echo htmlspecialchars($user_name); ?></h3>
            <div>
                <span id="currentDate"></span>
                <button class="btn-signout" onclick="logout()">Logout</button>
            </div>
        </div>
        <div class="container" style="align-items: start; padding-left: 50px;">
            <h2>Employees</h2>
        </div>
        <table class="table table-striped" style="background-color: #fff;">
            <thead style="background-color: #34495e ; color:#fff">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Employee ID</th>
                    <th scope="col">Employee Name </th>
                    <th scope="col">Phone Number</th>
                    <th scope="col">Job</th>


                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through employees to fetch their details from the person table
                foreach ($employees as $index => $employee) {
                    // Fetch employee details from person table
                    $person_id = $employee['person_id'];
                    $sql_person = "
                    SELECT first_name, last_name, phone_number, job_title
                    FROM person
                    WHERE person_id = :person_id";

                    $stmt_person = $database->prepare($sql_person);
                    $stmt_person->bindParam(':person_id', $person_id, PDO::PARAM_INT);
                    $stmt_person->execute();
                    $person = $stmt_person->fetch(PDO::FETCH_ASSOC);

                    if ($person) {
                        $employee_name = $person['first_name'] . ' ' . $person['last_name'];
                        $phone_number = $person['phone_number'];
                        $job_title = $person['job_title'];
                    } else {
                        $employee_name = "N/A";
                        $phone_number = "N/A";
                        $job_title = "N/A";
                    }
                    ?>
                    <tr>
                        <th scope="row"><?php echo $index + 1; ?></th>
                        <td><?php echo htmlspecialchars($person_id); ?></td>
                        <td><?php echo htmlspecialchars($employee_name); ?></td>
                        <td><?php echo htmlspecialchars($phone_number); ?></td>
                        <td><?php echo htmlspecialchars($job_title); ?></td>
                    </tr>
                <?php } ?>
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