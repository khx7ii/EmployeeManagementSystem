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

// Database connection
$username = "root";
$password = "";
$host = "localhost"; 
$dbname = "emp"; 

try {
    $database = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$user_id = $_SESSION['person_id'];
$query = "
    SELECT 
        person.person_id, 
        person.first_name, 
        person.last_name, 
        person.role, 
        person.phone_number AS phone, 
        person.address, 
        person.job_title AS job, 
        person.salary,
        admin.email AS admin_email,
        employee.logi_id AS employee_email,
        manager.email AS manager_email,
        CASE 
            WHEN person.role = 'admin' THEN admin.email
            WHEN person.role = 'employee' THEN employee.logi_id
            WHEN person.role = 'manager' THEN manager.email
        END AS email
    FROM person
    LEFT JOIN admin ON person.person_id = admin.person_id
    LEFT JOIN employee ON person.person_id = employee.person_id
    LEFT JOIN manager ON manager.person_id = person.person_id
    WHERE person.person_id = :person_id
";
$stmt = $database->prepare($query);
$stmt->bindParam(':person_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

// Fetch admin data
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $fname = $user['first_name'];
    $lname = $user['last_name'];
    $email = $user['email'];
    $phone = $user['phone'];
    $address = $user['address'];
    $job = $user['job'];
    $salary = $user['salary'];
    $role = $user['role'];

} else {
    echo "User not found.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile']) && $role == 'admin') {
    $updated_fname = $_POST['fname'];
    $updated_lname = $_POST['lname'];
    $updated_email = $_POST['email'];
    $updated_phone = $_POST['phone'];
    $updated_address = $_POST['address'];
    $updated_salary = $_POST['salary'];

    try {
        // Start a transaction
        $database->beginTransaction();

        // Update person table
        $update_query = "
            UPDATE person
            SET 
                first_name = :first_name, 
                last_name = :last_name, 
                phone_number = :phone, 
                address = :address, 
                salary = :salary
            WHERE 
                person_id = :person_id
        ";

        $update_person_stmt = $database->prepare($update_query);
        $update_person_stmt->bindParam(':first_name', $updated_fname);
        $update_person_stmt->bindParam(':last_name', $updated_lname);
        $update_person_stmt->bindParam(':phone', $updated_phone);
        $update_person_stmt->bindParam(':address', $updated_address);
        $update_person_stmt->bindParam(':salary', $updated_salary);
        $update_person_stmt->bindParam(':person_id', $user_id, PDO::PARAM_INT);
        $update_person_stmt->execute();

        // Update admin table
        $update_admin_query = "
            UPDATE admin
            SET 
                email = :email
            WHERE 
                admin_id = :person_id
        ";

        $update_admin_stmt = $database->prepare($update_admin_query);
        $update_admin_stmt->bindParam(':email', $updated_email);
        $update_admin_stmt->bindParam(':person_id', $user_id, PDO::PARAM_INT);
        $update_admin_stmt->execute();

        // Commit the transaction if both updates succeed
        $database->commit();

        echo "Profile updated successfully!";
        header("Refresh: 1");  // Refresh page to see updated values
    } catch (PDOException $e) {
        // Rollback the transaction if something goes wrong
        $database->rollBack();
        echo "Error updating profile: " . $e->getMessage();
    }
}
$user_name = $_SESSION['user_name']; 
?>
<!DOCTYPE html>
<html>

<head>
    <title>
        My profile
    </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="profile.css">
</head>

<body>
    <div class="row">

        <div class="sidebar">
            <div class="brand">EMS</div>
            <?php if ($role == 'admin'): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="ManagersCRUD.php">Manage Managers</a>
                <a href="employeecrud.php">Manage Employees</a>
                <a href="depcrud.php">Manage Departments</a>
                <a href="#">View Profile</a>
            <?php elseif ($role == 'manager'): ?>
                <!-- Manager Sidebar Links -->
                <a href="dashboard_man.php">Dashboard</a>
                <a href="#">View Profile</a>
                <a href="viewEmployees.php">View All Employees</a>
                <a href="assignTasks.php">Assign Tasks</a>
                <a href="retrieve_employees.php">Mark Absence</a>
                <a href="Requested_vacations_manager.php">Requested Vacations</a>
                <a href="add_report.php">Generate Reports</a>
            <?php elseif ($role == 'employee'): ?>
                <!-- Employee Sidebar Links -->
                <a href="dashboard_emp.php">Dashboard</a>
                <a href="assigned_tasks.php">Assigned Tasks</a>
                <a href="RequestVacation.php">Request Vacation</a>
                <a href="requestedVacation.php">Requested Vacations</a>
                <a href="#">View Profile</a>
            <?php endif; ?>
        </div>

        <div class="col-md-8 mt-1" style="padding-top: 3%;">
            <div class="header">
                <h3>Welcome Back, <?php echo $user_name; ?></h3>
                <div>

                    <span id="currentDate"></span>

                    <button class="btn-signout ms-3" onclick="logout()">Logout</button>
                </div>
            </div>
            <div class="card mb-3 content">
                <h1 class="m-3 pt-3">About</h1>
                <div class="card-body">
                    <form id="profileForm" action="profile.php" method="POST">
                        <div class="row-1">
                            <div class="col-md-3">
                                <h5>ID Number</h5>
                            </div>
                            <div class="col-md-9 text-secondary">
                                <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($user_id); ?>"
                                    readonly>

                            </div>
                        </div>
                        <hr>
                        <div class="row-1">
                            <div class="col-md-3">
                                <h5>First Name</h5>
                            </div>
                            <div class="col-md-9 text-secondary">
                                <input type="text" id="fname" name="fname"
                                    value="<?php echo htmlspecialchars($fname); ?>" readonly>
                            </div>
                        </div>
                        <hr>
                        <div class="row-1">
                            <div class="col-md-3">
                                <h5>Last Name</h5>
                            </div>
                            <div class="col-md-9 text-secondary">
                                <input type="text" id="lname" name="lname"
                                    value="<?php echo htmlspecialchars($lname); ?>" readonly>
                            </div>
                        </div>
                        <hr>
                        <div class="row-1">
                            <div class="col-md-3">
                                <h5>Email</h5>
                            </div>
                            <div class="col-md-9 text-secondary">
                                <input type="email" id="email" name="email"
                                    value="<?php echo htmlspecialchars($email); ?>" readonly
                                    style="width: 100%; padding: 10px; font-size: 16px;">
                            </div>
                        </div>
                        <hr>
                        <div class="row-1">
                            <div class="col-md-3">
                                <h5>Phone</h5>
                            </div>
                            <div class="col-md-9 text-secondary">
                                <input type="tel" id="phone" name="phone"
                                    value="<?php echo htmlspecialchars($phone); ?>" readonly>
                            </div>
                        </div>
                        <hr>
                        <div class="row-1">
                            <div class="col-md-3">
                                <h5>Address</h5>
                            </div>
                            <div class="col-md-9 text-secondary">
                                <input type="text" id="address" name="address"
                                    value="<?php echo htmlspecialchars($address); ?>" readonly>
                            </div>
                        </div>
                        <hr>
                        <div class="row-1">
                            <div class="col-md-3">
                                <h5>Job title</h5>
                            </div>
                            <div class="col-md-9 text-secondary">
                                <input type="text" id="job" name="job" value="<?php echo htmlspecialchars($job); ?>"
                                    readonly>
                            </div>
                        </div>
                        <hr>
                        <div class="row-1">
                            <div class="col-md-3">
                                <h5>Salary</h5>
                            </div>
                            <div class="col-md-9 text-secondary">
                                <input type="text" id="salary" name="salary"
                                    value="<?php echo htmlspecialchars($salary); ?>" readonly>
                            </div>
                        </div>
                        <?php if ($role == 'admin'): ?>
                            <hr>
                            <button type="submit" id="saveButton" name="update_profile" style="display: none;">Save</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <?php if ($role == 'admin'): ?>
                <button id="editButton" onclick="toggleEdit()">Edit Profile</button>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function toggleEdit() {
            let inputs = document.querySelectorAll('#profileForm input');
            let saveButton = document.getElementById('saveButton');
            let editButton = document.getElementById('editButton');
            let isReadOnly = inputs[1].hasAttribute('readonly');

            inputs.forEach(input => {
                // Ensure that ID and Job Title fields are never editable
                if (input.id !== 'id' && input.id !== 'job') {
                    input.readOnly = !isReadOnly;
                }
            });

            saveButton.style.display = isReadOnly ? 'inline-block' : 'none';
            editButton.textContent = isReadOnly ? 'Cancel' : 'Edit Profile';
        }


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
</body>

</html>