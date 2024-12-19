<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "emp";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $task_description = $_POST['task_description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = 'To Do';

    $sql_check_employee = "SELECT * FROM employee WHERE employee_id = ?";
    $stmt_check = $conn->prepare($sql_check_employee);
    $stmt_check->bind_param("i", $employee_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {

        $sql_insert = "INSERT INTO tasks (employee_id, description, status, start_date, end_date) 
                       VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("issss", $employee_id, $task_description, $status, $start_date, $end_date);

        if ($stmt_insert->execute()) {
            echo "Task assigned successfully!";
        } else {
            echo "Error: " . $stmt_insert->error;
        }

        $stmt_insert->close();
    } else {
        echo "Error: Employee ID does not exist.";
    }

    $stmt_check->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Task</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
        }

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

        h1 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 400px;
            margin: 0 auto;
            font-weight: bold;
        }

        label {
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        textarea {
            height: 100px;
        }

        button {
            background-color: #34495e;
            color: white;
            font-size: 16px;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #2980b9;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 220px;
            }

            h1 {
                font-size: 24px;
            }

            form {
                width: 100%;
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="brand">EMS</div>
        <a href="dashboard_man.php">Dashboard</a>
        <a href="profile.php">View Profile</a>
        <a href="viewEmployees.php">View All Employees</a>
        <a href="#">Assign Tasks</a>
        <a href="retrieve_employees.php">Mark Absence</a>
        <a href="Requested_vacations_manager.php">Requested Vacations</a>
        <a href="add_report.php">Generate Reports</a>

    </div>
    <h1>Assign Task to Employee</h1>
    <form action="assignTasks.php" method="POST">
        <label for="employee_id">Enter Employee ID:</label>
        <input type="number" name="employee_id" id="employee_id" required>
        <br><br>

        <label for="task_description">Enter Task Description:</label>
        <textarea name="task_description" id="task_description" required></textarea>
        <br><br>

        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date" required>
        <br><br>

        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date" required>
        <br><br>

        <button type="submit">Assign Task</button>
    </form>
</body>

</html>