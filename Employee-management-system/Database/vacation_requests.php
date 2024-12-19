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

// Fetch vacation requests for employees in the manager's department
$sql_vacations = "
SELECT
    v.vacation_id,
    v.employee_id,
    v.status,
    v.start_date,
    v.end_date,
    v.causes,
    p.first_name
FROM
    vacations v
JOIN
    employee e ON v.employee_id = e.employee_id
JOIN
    person p ON e.person_id = p.person_id
WHERE
    v.status = 'pending'
    AND e.department_id = :manager_department_id";  // Filter by manager's department ID

$stmt_vacations = $database->prepare($sql_vacations);
$stmt_vacations->bindParam(':manager_department_id', $manager_department_id, PDO::PARAM_INT);
$stmt_vacations->execute();
$result = $stmt_vacations->fetchAll(PDO::FETCH_ASSOC);

// Handle approval or denial of vacation requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_id = $_POST['vacation_id'];
    $action = $_POST['action']; // 'approve' or 'deny'

    $new_status = $action === 'approve' ? 'Approved' : 'Rejected'; // Correct the rejection status

    // Update the status of the vacation request
    $update_sql = "UPDATE vacations SET status = :status WHERE vacation_id = :vacation_id";
    $update_stmt = $database->prepare($update_sql);

    // Bind parameters using PDO
    $update_stmt->bindParam(':status', $new_status, PDO::PARAM_STR);
    $update_stmt->bindParam(':vacation_id', $request_id, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        $_SESSION['message'] = "Vacation request has been $action successfully.";
    } else {
        $_SESSION['error'] = "Failed to $action the vacation request.";
    }

    // Redirect back to the main page
    header("Location: Requested_vacations_manager.php");
    exit();
}
?>