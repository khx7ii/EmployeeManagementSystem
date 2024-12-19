<?php include("config.php");
if (isset($_GET['id'])) {

    $id = $_GET['id'];


    $query = "
            SELECT 
            manager.manager_id, 
            manager.email, 
            manager.password,
            person.first_name, 
            person.last_name, 
            person.phone_number AS phone, 
            person.address, 
            person.job_title AS job, 
            person.salary 
        FROM 
            manager 
        JOIN 
            person 
        ON 
            manager.person_id = person.person_id
        WHERE 
            manager.manager_id =  '$id'
        ";
    $stmt = $database->prepare($query);
    $stmt->execute();


    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        die("Query failed: " . $stmt->errorInfo()[2]);
    } else {
    }



    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
        $updated_fname = $_POST['fname'];
        $updated_lname = $_POST['lname'];
        $updated_phone = $_POST['phone'];
        $updated_address = $_POST['address'];
        $updated_salary = $_POST['salary'];
        $updated_job = $_POST['job'];

        // Update 
        $update_query = "
        UPDATE person
        SET 
            first_name = :first_name, 
            last_name = :last_name, 
            phone_number = :phone, 
            address = :address, 
            salary = :salary,
            job_title= :job
        WHERE 
            person_id = (SELECT person_id FROM manager WHERE manager_id = :manager_id)
    ";

        $update_stmt = $database->prepare($update_query);
        $update_stmt->bindParam(':first_name', $updated_fname);
        $update_stmt->bindParam(':last_name', $updated_lname);
        $update_stmt->bindParam(':phone', $updated_phone);
        $update_stmt->bindParam(':address', $updated_address);
        $update_stmt->bindParam(':salary', $updated_salary);
        $update_stmt->bindParam(':job', $updated_job);
        $update_stmt->bindParam(':manager_id', $id, PDO::PARAM_INT);

        if ($update_stmt->execute()) {
            echo "Profile updated successfully!";

            header("location: ManagersCRUD.php");  // back to crud
        } else {
            echo "Error updating profile.";
        }
    }
}
?>
<?php
// Start the session to track user data (you must include session_start() at the beginning of the file)
session_start();

if (!isset($_SESSION['person_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login_admin.php");
    exit();
}
// Handle logout request
if (isset($_GET['logout'])) {
    // Destroy the session and redirect to the login page
    session_destroy();
    header("Location: login_admin.php");
    exit();
}

// Get the employee's name from the session
$user_name = $_SESSION['user_name']; // Assuming the user's name is stored in the session
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Manager</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="dashboard.css">
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
        <h3>Logged in, <?php echo $user_name; ?></h3>
        <!-- Span for displaying current date -->
        <span id="currentDate"></span>
        <!-- Logout Button with JavaScript Redirect -->
        <button class="btn-signout ms-3" onclick="logout()">Logout</button>
    </div>
    </div>
    <div class="container">
        <div class=" text-center mt-5 ">
            <h1>Update Manager</h1>
        </div>
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="update_manager.php?id=<?php echo $id; ?>">

                    <div class="modal-body">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" name="fname"
                                value="<?php echo htmlspecialchars($result['first_name']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control" name="lname"
                                value="<?php echo htmlspecialchars($result['last_name']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email"
                                value="<?php echo htmlspecialchars($result['email']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Password</label>

                            <input type="text" class="form-control" name="password" id="password" readonly
                                value="<?php echo htmlspecialchars($result['password']); ?>">
                            <span id="eye-icon" class="eye-icon">&#128065;</span>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" class="form-control" name="phone"
                                value="<?php echo htmlspecialchars($result['phone']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" class="form-control" name="address"
                                value="<?php echo htmlspecialchars($result['address']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Job</label>
                            <input type="text" class="form-control" name="job"
                                value="<?php echo htmlspecialchars($result['job']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Salary</label>
                            <input type="number" class="form-control" name="salary"
                                value="<?php echo htmlspecialchars($result['salary']); ?>" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-info" name="save" value="SAVE">
                        <input action="ManagersCRUD.php" type="submit" class="btn btn-info" name="save" value="CANCEL">
                    </div>
                    <div>

                </form>
            </div>

        </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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


    // Function to get the current date in the format: Tue, 3 Dec 2024
    function updateDate() {
        var today = new Date();
        var options = {
            weekday: 'short',
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        };
        var formattedDate = today.toLocaleDateString('en-GB', options);
        document.getElementById('currentDate').textContent = formattedDate;
    }

    // Call the function on page load
    updateDate();

    // JavaScript function to redirect to login page when user logs out
    function logout() {
        window.location.href = 'login_admin.php'; // Redirect to the login page
    }
</script>


</html>