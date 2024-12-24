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
<?php include("config.php");
if (isset($_GET['id'])) {

    $id = $_GET['id'];


    $query = "
            SELECT 
            *
        FROM 
            department 
        WHERE 
            department.department_id =  '$id'
        ";
    $stmt = $database->prepare($query);
    $stmt->execute();


    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        die("Query failed: " . $stmt->errorInfo()[2]);
    } else {

    }



    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
        $updated_manid = $_POST['manager_id'];
        $updated_name = $_POST['name'];
        $updated_location = $_POST['location'];
        $updated_empnum = $_POST['emps_number'];

        // Update 
        $update_query = "
        UPDATE department
        SET 
            manager_id = :manager_id, 
            name = :name, 
            location = :location, 
            emps_number = :emps_number
        WHERE 
             department.department_id =  '$id'
    ";

        $update_stmt = $database->prepare($update_query);
        $update_stmt->bindParam(':manager_id', $updated_manid);
        $update_stmt->bindParam(':name', $updated_name);
        $update_stmt->bindParam(':location', $updated_location);
        $update_stmt->bindParam(':emps_number', $updated_empnum);

        if ($update_stmt->execute()) {
            echo "Profile updated successfully!";

            header("location: depcrud.php");  // back to crud
        } else {
            echo "Error updating profile.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Department</title>
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
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            cursor: pointer;
        }

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
        <div class=" text-center mt-5 ">
            <h1>Update Department</h1>
        </div>
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="update_dep.php?id=<?php echo $id; ?>">

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Manager ID</label>
                            <input type="number" class="form-control" name="manager_id"
                                value="<?php echo htmlspecialchars($result['manager_id']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name"
                                value="<?php echo htmlspecialchars($result['name']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" class="form-control" name="location"
                                value="<?php echo htmlspecialchars($result['location']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Number of Employees</label>
                            <input type="number" class="form-control" name="emps_number"
                                value="<?php echo htmlspecialchars($result['emps_number']); ?>" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-info" name="save" value="SAVE">
                        <input action="depcrud.php" type="submit" class="btn btn-info" name="save" value="CANCEL">
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
        const eyeIcon = document.getElementById('eye-icon');
        const passwordInput = document.getElementById('password');

        // Add event listener to the eye icon to toggle password visibility
        eyeIcon.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '&#128064;'; // Open eye icon
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '&#128065;'; // Closed eye icon
            }
        });

    </script>
</body>

</html>