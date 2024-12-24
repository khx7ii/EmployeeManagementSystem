<?php
include("config.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $query1 = "DELETE FROM vacations WHERE employee_id = :id";


    $query2 = "DELETE p, e
               FROM person p
               JOIN employee e
               ON p.person_id = e.person_id
               WHERE e.employee_id = :id";

    try {

        $database->beginTransaction();


        $stmt1 = $database->prepare($query1);
        $stmt1->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt1->execute();

        // Delete from employee and person
        $stmt2 = $database->prepare($query2);
        $stmt2->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt2->execute();


        $database->commit();

        echo "Record deleted successfully.";
        header("location: employeecrud.php");
    } catch (PDOException $e) {

        $database->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No ID provided to delete.";
}
?>