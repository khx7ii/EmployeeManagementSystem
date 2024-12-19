<?php
//include("config.php"); // Ensure config.php initializes the PDO connection in $pdo

if (isset($_POST['add_manager'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $addr = $_POST['addr'];
    $job = $_POST['job'];
    $salary = $_POST['salary'];
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $age = $_POST['age'];

    try {
        // Start a transaction
        $username = "root";
        $password = "";
        $pdo = new PDO("mysql:host=localhost;dbname=emp;charset=utf8;", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->beginTransaction();

        // Insert into the `person` table
        $query1 = "INSERT INTO person 
            (first_name, last_name, age, gender, address, phone_number, date_of_birth, salary, job_title, role) 
            VALUES (:fname, :lname, :age, :gender, :addr, :phone, :dob, :salary, :job, :role)";

        $stmt1 = $pdo->prepare($query1);
        $stmt1->execute([
            ':fname' => $fname,
            ':lname' => $lname,
            ':age' => $age,
            ':gender' => $gender,
            ':addr' => $addr,
            ':phone' => $phone,
            ':dob' => $dob,
            ':salary' => $salary,
            ':job' => $job,
            ':role' => $role
        ]);

        // Get the last inserted person_id
        $personid = $pdo->lastInsertId();

        // Insert into the `manager` table
        $query2 = "INSERT INTO manager (person_id, email, password) 
            VALUES (:person_id, :email, :password)";

        $stmt2 = $pdo->prepare($query2);
        $stmt2->execute([
            ':person_id' => $personid,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT) // Securely hash passwords
        ]);

        // Commit the transaction
        $pdo->commit();
        header('location:ManagersCRUD.php?inser_msg=your data has been submitted successfully!');
        echo "Data inserted successfully into both tables!";
    } catch (PDOException $e) {
        // Rollback the transaction on error
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>