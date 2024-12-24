<?php
session_start();

if (!isset($_SESSION['person_id'])) {
    header("Location: login_admin.php");
    exit();
}

$username = "root";
$password = "";
$host = "localhost";
$dbname = "emp";

try {
    $database = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$user_name = $_SESSION['user_name'];

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task_id'], $_POST['status'])) {
        $task_id = intval($_POST['task_id']);
        $status = $_POST['status'];

        $allowedStatuses = ['To do', 'In Progress', 'Done'];
        if (!in_array($status, $allowedStatuses)) {
            echo json_encode(["error" => "Invalid status value."]);
            exit();
        }

        $stmt = $database->prepare("UPDATE tasks SET status = ? WHERE task_id = ?");
        $updated = $stmt->execute([$status, $task_id]);

        if ($updated) {
            echo json_encode(["message" => "Status updated successfully."]);
        } else {
            echo json_encode(["error" => "Failed to update status."]);
        }
        exit();
    } else {
        echo json_encode(["error" => "Invalid request data."]);
        exit();
    }
}

$person_id = intval($_SESSION['person_id']);
if (!isset($_SESSION['first_name'], $_SESSION['last_name'])) {
    $stmt = $database->prepare("SELECT first_name, last_name FROM person WHERE person_id = ?");
    $stmt->execute([$person_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
    } else {
        die("User not found.");
    }
}

// Retrieve employee_id based on person_id
$stmt = $database->prepare("SELECT employee_id FROM employee WHERE person_id = ?");
$stmt->execute([intval($_SESSION['person_id'])]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    die("Error: Employee not found.");
}

$employee_id = $employee['employee_id'];

// Retrieve tasks for the employee
$stmt = $database->prepare("SELECT * FROM tasks WHERE employee_id = ?");
$stmt->execute([$employee_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned Tasks</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #34495e;
            color: white;
        }

        .status-select {
            padding: 8px 12px;
            font-size: 16px;
            border: 1px solid #34495e;
            border-radius: 5px;
            background-color: #ecf0f1;
            color: #34495e;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .status-select:hover {
            background-color: #bdc3c7;
        }

        /* Adjust for smaller screens */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 220px;
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
            <button class="btn-signout" onclick="window.location.href='?logout=true'">Logout</button>
        </div>
        <h1>Assigned Tasks</h1>
        <table>
            <thead>
                <tr>
                    <th>Task ID</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tasks) > 0): ?>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['task_id']) ?></td>
                            <td><?= htmlspecialchars($task['description']) ?></td>
                            <td><?= htmlspecialchars($task['start_date']) ?></td>
                            <td><?= htmlspecialchars($task['end_date']) ?></td>
                            <td>
                                <select class="status-select" data-id="<?= $task['task_id'] ?>">
                                    <option value="To do" <?= $task['status'] === 'To do' ? 'selected' : '' ?>>To do</option>
                                    <option value="In Progress" <?= $task['status'] === 'In Progress' ? 'selected' : '' ?>>In
                                        Progress</option>
                                    <option value="Done" <?= $task['status'] === 'Done' ? 'selected' : '' ?>>Done</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No tasks assigned.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script>
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function () {
                const taskId = this.getAttribute('data-id');
                const status = this.value;

                fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `task_id=${taskId}&status=${status}`
                })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message || data.error);
                    });
            });
        });
    </script>
</body>

</html>