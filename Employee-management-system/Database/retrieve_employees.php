<?php
include 'config.php';

// Query to fetch employee data
$sql = "SELECT person_id, first_name, last_name FROM person WHERE role = 'employee'";
$stmt = $database->prepare($sql);
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style-absent.css">
    <title>Employees Absent</title>
    <link rel="stylesheet" href="style-absent.css">
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
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">EMS</div>
        <a href="dashboard_man.php">Dashboard</a>
        <a href="profile.php">View Profile</a>
        <a href="viewEmployees.php">View All Employees</a>
        <a href="assignTasks.php">Assign Tasks</a>
        <a href="#">Mark Absence</a>
        <a href="Requested_vacations_manager.php">Requested Vacations</a>
        <a href="add_report.php">Generate Reports</a>

    </div>
    <h1 style="text-align: center; margin-bottom: 20px;">Submit Employees Absent</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Full Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?= htmlspecialchars($employee['person_id']) ?></td>
                    <td><?= htmlspecialchars($employee['first_name']) . ' ' . htmlspecialchars($employee['last_name']) ?>
                    </td>
                    <td>
                        <form action="update_absent.php" method="POST">
                            <input type="hidden" name="person_id" value="<?= htmlspecialchars($employee['person_id']) ?>">
                            <button type="submit">Mark Absent</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>