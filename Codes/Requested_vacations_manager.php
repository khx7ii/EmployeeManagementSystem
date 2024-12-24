<?php

include "vacation_requests.php";
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requested Vacations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        .content {
            margin: 30px;
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

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 16px;
            text-align: left;
        }

        thead {
            background-color: #212529;
            color: white;
        }

        thead th {
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }

        tbody tr {
            border-bottom: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody td {
            padding: 10px;
            text-align: center;
        }

        /* Button Styles */
        button.btn-success {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.btn-success:hover {
            background-color: #218838;
        }

        button.btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div class="content">

        <!-- Sidebar -->
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
        <div class="header">
            <h3>Welcome Back, <?php echo $user_name; ?></h3>
            <div>
                <span id="currentDate"></span>
                <button class="btn-signout ms-3" onclick="logout()">Logout</button>
            </div>
        </div>



        <h2>Requested Vacations</h2>

        <div class="card">
            <div class="card-body">

                <?php include "message.php"; ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Employee ID</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Reason</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && !empty($result)):  ?>
                            <?php foreach ($result as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['employee_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['causes']); ?></td>
                                    <td>
                                        <!-- Approve Vacation Request -->
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="vacation_id" value="<?php echo $row['vacation_id']; ?>">
                                            <button name="action" value="approve" class="btn btn-success">Approve</button>
                                        </form>

                                        <!-- Deny Vacation Request -->
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="vacation_id" value="<?php echo $row['vacation_id']; ?>">
                                            <button name="action" value="deny" class="btn btn-danger">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">No vacation requests found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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