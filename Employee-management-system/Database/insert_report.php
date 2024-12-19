<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $employee_id = $_POST['employee_id'];
    $report_content = $_POST['report_content'];

    // Insert the report into the reports table
    $insert_sql = "INSERT INTO reports (employee_id, report_content) VALUES (:employee_id, :report_content)";
    $insert_stmt = $database->prepare($insert_sql);
    $insert_stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
    $insert_stmt->bindParam(':report_content', $report_content, PDO::PARAM_STR);

    if ($insert_stmt->execute()) {
        echo "<p>Report successfully added for Employee ID: $employee_id</p>";
        echo '<a href="add_report.php">Back to Employee List</a>';
    } else {
        echo "<p>Failed to add the report. Please try again.</p>";
    }
} else {
    // Display the form
    $employee_id = $_GET['employee_id'] ?? null;
    if (!$employee_id) {
        die("Employee ID not provided.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Report</title>
    <style>
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

        /* Center the content on the page */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        /* Center the table */
        table {
            border-collapse: collapse;
            width: 50%;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        button {
            padding: 8px 16px;
            background-color: #34495e;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
        }

        button:hover {
            background-color: rgb(5, 47, 92);
        }

        /* Center the header */
        h1 {
            text-align: center;
            margin-bottom: 20px;
            /* Spacing between header and table */
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="brand">EMS</div>
        <a href="dashboard_man.php">Dashboard</a>
        <a href="profile.php">View Profile</a>
        <a href="viewEmployees.php">View All Employees</a>
        <a href="assignTasks.php">Assign Tasks</a>
        <a href="retrieve_employees.php">Mark Absence</a>
        <a href="Requested_vacations_manager.php">Requested Vacations</a>
        <a href="add_report.php">Generate Reports</a>

    </div>
    <div>
        <h1 class="header">Add Report for Employee ID: <?= htmlspecialchars($employee_id) ?></h1>

        <form action="insert_report.php" method="POST" class="report-form">
            <input type="hidden" name="employee_id" value="<?= htmlspecialchars($employee_id) ?>">
            <label for="report_content">Report Content:</label><br>
            <textarea name="report_content" id="report_content" rows="5" cols="70" required></textarea><br>
            <button type="submit" class="action-button">Save Report</button>
        </form>
    </div>
</body>

</html>