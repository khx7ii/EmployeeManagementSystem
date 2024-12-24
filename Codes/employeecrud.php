<?php include("config.php"); ?>
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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee CRUD</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        body {
            color: #566787;
            background: #f5f5f5;
            font-family: 'Varela Round', sans-serif;
            font-size: 13px;
        }

        .table-responsive {
            margin: 30px 0;
        }

        .table-wrapper {
            min-width: 1000px;
            background: #fff;
            padding: 20px 25px;
            border-radius: 3px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }

        .table-title {
            padding-bottom: 15px;
            background: #435d7d;
            color: #fff;
            padding: 16px 30px;
            margin: -20px -25px 10px;
            border-radius: 3px 3px 0 0;
        }

        .table-title h2 {
            margin: 5px 0 0;
            font-size: 24px;
        }

        .table-title .btn-group {
            float: right;
        }

        .table-title .btn {
            color: #fff;
            float: right;
            font-size: 13px;
            border: none;
            min-width: 50px;
            border-radius: 2px;
            border: none;
            outline: none !important;
            margin-left: 10px;
        }

        .table-title .btn i {
            float: left;
            font-size: 21px;
            margin-right: 5px;
        }

        .table-title .btn span {
            float: left;
            margin-top: 2px;
        }

        table.table tr th,
        table.table tr td {
            border-color: #e9e9e9;
            padding: 12px 15px;
            vertical-align: middle;
        }

        table.table tr th:first-child {
            width: 60px;
        }

        table.table tr th:last-child {
            width: 100px;
        }

        table.table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfcfc;
        }

        table.table-striped.table-hover tbody tr:hover {
            background: #f5f5f5;
        }

        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
        }

        table.table td:last-child i {
            opacity: 0.9;
            font-size: 22px;
            margin: 0 5px;
        }

        table.table td a {
            font-weight: bold;
            color: #566787;
            display: inline-block;
            text-decoration: none;
            outline: none !important;
        }

        table.table td a:hover {
            color: #2196F3;
        }

        table.table td a.edit {
            color: #FFC107;
        }

        table.table td a.delete {
            color: #F44336;
        }

        table.table td i {
            font-size: 19px;
        }

        table.table .avatar {
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 10px;
        }

        .pagination {
            float: right;
            margin: 0 0 5px;
        }

        .pagination li a {
            border: none;
            font-size: 13px;
            min-width: 30px;
            min-height: 30px;
            color: #999;
            margin: 0 2px;
            line-height: 30px;
            border-radius: 2px !important;
            text-align: center;
            padding: 0 6px;
        }

        .pagination li a:hover {
            color: #666;
        }

        .pagination li.active a,
        .pagination li.active a.page-link {
            background: #03A9F4;
        }

        .pagination li.active a:hover {
            background: #0397d6;
        }

        .pagination li.disabled i {
            color: #ccc;
        }

        .pagination li i {
            font-size: 16px;
            padding-top: 6px
        }

        .hint-text {
            float: left;
            margin-top: 10px;
            font-size: 13px;
        }

        /* Custom checkbox */
        .custom-checkbox {
            position: relative;
        }

        .custom-checkbox input[type="checkbox"] {
            opacity: 0;
            position: absolute;
            margin: 5px 0 0 3px;
            z-index: 9;
        }

        .custom-checkbox label:before {
            width: 18px;
            height: 18px;
        }

        .custom-checkbox label:before {
            content: '';
            margin-right: 10px;
            display: inline-block;
            vertical-align: text-top;
            background: white;
            border: 1px solid #bbb;
            border-radius: 2px;
            box-sizing: border-box;
            z-index: 2;
        }

        .custom-checkbox input[type="checkbox"]:checked+label:after {
            content: '';
            position: absolute;
            left: 6px;
            top: 3px;
            width: 6px;
            height: 11px;
            border: solid #000;
            border-width: 0 3px 3px 0;
            transform: inherit;
            z-index: 3;
            transform: rotateZ(45deg);
        }

        .custom-checkbox input[type="checkbox"]:checked+label:before {
            border-color: #03A9F4;
            background: #03A9F4;
        }

        .custom-checkbox input[type="checkbox"]:checked+label:after {
            border-color: #fff;
        }

        .custom-checkbox input[type="checkbox"]:disabled+label:before {
            color: #b8b8b8;
            cursor: auto;
            box-shadow: none;
            background: #ddd;
        }

        /* Modal styles */
        .modal .modal-dialog {
            max-width: 400px;
        }

        .modal .modal-header,
        .modal .modal-body,
        .modal .modal-footer {
            padding: 20px 30px;
        }

        .modal .modal-content {
            border-radius: 3px;
        }

        .modal .modal-footer {
            background: #ecf0f1;
            border-radius: 0 0 3px 3px;
        }

        .modal .modal-title {
            display: inline-block;
        }

        .modal .form-control {
            border-radius: 2px;
            box-shadow: none;
            border-color: #dddddd;
        }

        .modal textarea.form-control {
            resize: vertical;
        }

        .modal .btn {
            border-radius: 2px;
            min-width: 100px;
        }

        .modal form label {
            font-weight: normal;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>Welcome Back, <?php echo $user_name; ?></h3>
        <div>
            <span id="currentDate"></span>
            <button class="btn-signout ms-3" onclick="logout()">Logout</button>
        </div>
    </div>
    <div class="container">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h2>Manage <b>Employees</b></h2>
                        </div>
                        <div class="col-xs-6">
                            <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i
                                    class="material-icons">&#xE147;</i> <span>Add New Employee</span></a>
                            <a href="dashboard.php" class="btn btn-secondary"><i class="fa fa-home"></i></a>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>
                                <span class="custom-checkbox">
                                    <input type="checkbox" id="selectAll">
                                    <label for="selectAll"></label>
                                </span>
                            </th>
                            <th>Employee ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Login ID</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Job</th>
                            <th>Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "
            SELECT 
            employee.employee_id, 
            employee.logi_id, 
            person.first_name, 
            person.last_name, 
            person.phone_number AS phone, 
            person.address, 
            person.job_title AS job, 
            person.salary 
        FROM 
            employee 
        JOIN 
            person 
        ON 
            employee.person_id = person.person_id 
        ";
                        $result = $database->prepare($query);
                        if (!$result) {
                            die("query failed" . mysqli_error($mysql));
                        } else {
                            $stmt = $database->prepare($query);
                            $stmt->execute();

                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                ?>
                                <tr>
                                    <td>
                                        <span class="custom-checkbox">
                                            <input type="checkbox" id="checkbox1" name="options[]" value="1">
                                            <label for="checkbox1"></label>
                                        </span>
                                    </td>
                                    <td><?php echo $row['employee_id']; ?></td>
                                    <td><?php echo $row['first_name']; ?></td>
                                    <td><?php echo $row['last_name']; ?></td>
                                    <td><?php echo $row['logi_id']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td><?php echo $row['address']; ?></td>
                                    <td><?php echo $row['job']; ?></td>
                                    <td><?php echo $row['salary']; ?></td>
                                    <td>
                                        <a href="update_emp.php?id=<?php echo $row['employee_id']; ?>" class="edit"><i
                                                class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                        <a href="delete_emp.php?id=<?php echo $row['employee_id']; ?>" class="delete"
                                            data-toggle="modal"><i class="material-icons" data-toggle="tooltip"
                                                title="Delete">&#xE872;</i></a>
                                    </td>
                                </tr>

                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div id="addEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="insert_emp.php" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Employee</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="fname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="lname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Login ID</label>
                            <input type="int" name="loginid" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea class="form-control" name="addr" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Job</label>
                            <input type="text" name="job" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Department ID</label>
                            <input type="text" id="depid" name="depid" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Salary</label>
                            <input type="number" name="salary" class="form-control" required min="0" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="number" name="age" class="form-control" required>
                        </div>
                        <!--gender checkbox-->
                        <div class="form-group">
                            <label for="gender">Gender</label><br>
                            <input type="radio" id="male" name="gender" value="male" required>
                            <label for="male">Male</label><br>
                            <input type="radio" id="female" name="gender" value="female" required>
                            <label for="female">Female</label><br>
                        </div>
                        <div class="form-group">
                            <!--role dropdown list-->
                            <label for="role">Role</label>
                            <select id="role" name="role" class="form-control" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="employee">Employee</option>
                                <option value="manager">Manager</option>
                            </select>

                        </div>

                        <!-- add and cancel buttons -->
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-toggle="modal" value="Cancel">
                            <input type="submit" class="btn btn-success" name="add_employee" value="Add">
                        </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Function to get the current date in the format
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
    <script>
        $(document).ready(function () {
            // Activate tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // Select/Deselect checkboxes
            var checkbox = $('table tbody input[type="checkbox"]');
            $("#selectAll").click(function () {
                if (this.checked) {
                    checkbox.each(function () {
                        this.checked = true;
                    });
                } else {
                    checkbox.each(function () {
                        this.checked = false;
                    });
                }
            });
            checkbox.click(function () {
                if (!this.checked) {
                    $("#selectAll").prop("checked", false);
                }
            });
        });
    </script>
</body>

</html>