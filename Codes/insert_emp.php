<?php

if (isset($_POST['add_employee'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['loginid'];
    $phone = $_POST['phone'];
    $addr = $_POST['addr'];
    $job = $_POST['job'];
    $salary = $_POST['salary'];
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $age = $_POST['age'];
    $depid = $_POST['depid'];

    try {

        $username = "root";
        $password = "";
        $pdo = new PDO("mysql:host=localhost;dbname=emp;charset=utf8;", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->beginTransaction();


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

        $personid = $pdo->lastInsertId();


        $query2 = "INSERT INTO employee (person_id, logi_id, password,department_id) 
            VALUES (:person_id, :loginid, :password, :depid)";

        $stmt2 = $pdo->prepare($query2);
        $stmt2->execute([
            ':person_id' => $personid,
            ':loginid' => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':depid' => $depid
        ]);


        $pdo->commit();
        header('location:employeecrud.php?inser_msg=your data has been submitted successfully!');
        echo "Data inserted successfully into both tables!";
    } catch (PDOException $e) {

        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>