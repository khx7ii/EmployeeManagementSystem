<?php
//include("config.php"); // Ensure config.php initializes the PDO connection in $pdo

if (isset($_POST['add_dep'])) {
    $manager_id = $_POST['manager_id'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $emps_number = $_POST['emps_number'];

    try {

        $username = "root";
        $password = "";
        $pdo = new PDO("mysql:host=localhost;dbname=emp;charset=utf8;", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->beginTransaction();




        $query2 = "INSERT INTO department (manager_id, name, location,emps_number) 
            VALUES (:manager_id, :name, :location, :emps_number)";

        $stmt2 = $pdo->prepare($query2);
        $stmt2->execute([
            ':manager_id' => $manager_id,
            ':name' => $name,
            ':location' => $location,
            ':emps_number' => $emps_number
        ]);


        $pdo->commit();
        header('location:depcrud.php?inser_msg=your data has been submitted successfully!');
        echo "Data inserted successfully into both tables!";
    } catch (PDOException $e) {

        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>