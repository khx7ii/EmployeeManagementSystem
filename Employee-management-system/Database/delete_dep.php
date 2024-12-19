<?php
include("config.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $query2 = "DELETE 
               FROM department
               WHERE department_id = :id";

    try {

        $database->beginTransaction();



        $stmt2 = $database->prepare($query2);
        $stmt2->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt2->execute();


        $database->commit();

        echo "Record deleted successfully.";
        header("location: depcrud.php");
    } catch (PDOException $e) {

        $database->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No ID provided to delete.";
}
?>