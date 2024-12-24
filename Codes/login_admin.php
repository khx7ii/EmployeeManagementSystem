<?php
session_start();

$username = "root";
$password = "";
$database = new PDO("mysql:host=localhost; dbname=emp; charset=utf8;", $username, $password);

if (!$database) {
    die("Connection failed: " . $database->errorInfo());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Determine the role and set the query accordingly
    $roleQuery = "
        SELECT role 
        FROM person
        WHERE 
            person_id IN (
                SELECT person_id FROM admin WHERE email = :email
                UNION 
                SELECT person_id FROM manager WHERE email = :email
                UNION 
                SELECT person_id FROM employee WHERE logi_id = :email
            )
    ";

    try {
        $stmt = $database->prepare($roleQuery);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $roleResult = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($roleResult) {
            $role = $roleResult['role'];

            // Define the query based on the role
            switch ($role) {
                case 'admin':
                    $query = "
                        SELECT 
                            person.person_id, 
                            person.first_name, 
                            person.last_name, 
                            person.role, 
                            admin.password AS admin_password
                        FROM 
                            person 
                        LEFT JOIN admin ON admin.person_id = person.person_id
                        WHERE admin.email = :email
                    ";
                    break;

                case 'manager':
                    $query = "
                        SELECT 
                            person.person_id, 
                            person.first_name, 
                            person.last_name, 
                            person.role, 
                            manager.password AS manager_password
                        FROM 
                            person 
                        LEFT JOIN manager ON manager.person_id = person.person_id
                        WHERE manager.email = :email
                    ";
                    break;

                case 'employee':
                    $query = "
                        SELECT 
                            person.person_id, 
                            person.first_name, 
                            person.last_name, 
                            person.role, 
                            employee.password AS employee_password,
                            employee.logi_id
                        FROM 
                            person 
                        LEFT JOIN employee ON employee.person_id = person.person_id
                        WHERE employee.logi_id = :email
                    ";
                    break;

                default:
                    $error_message = "Role not recognized.";
                    return;
            }

            // Execute the specific query for the role
            $stmt = $database->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Verify password
                $isPasswordValid = false;

                if ($role == 'admin' && $user['admin_password'] == $password) {
                    $isPasswordValid = true;
                } elseif ($role == 'manager' && $user['manager_password'] == $password) {
                    $isPasswordValid = true;
                } elseif ($role == 'employee' && $user['employee_password'] == $password) {
                    $isPasswordValid = true;
                }

                if ($isPasswordValid) {
                    // Store session data
                    $_SESSION['person_id'] = $user['person_id'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['role'] = $role;

                    // Redirect based on role
                    switch ($role) {
                        case 'admin':
                            header("Location: dashboard.php");
                            break;
                        case 'manager':
                            header("Location: dashboard_man.php");
                            break;
                        case 'employee':
                            header("Location: dashboard_emp.php");
                            break;
                    }
                    exit();
                } else {
                    $error_message = "Invalid password.";
                }
            } else {
                $error_message = "User not found.";
            }
        } else {
            $error_message = "Role not found.";
        }
    } catch (PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login_admin.php" method="POST">
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" required>
            </div>

            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="button-group">
                <button type="submit">Login</button>
            </div>

        </form>

        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>
</body>

</html>