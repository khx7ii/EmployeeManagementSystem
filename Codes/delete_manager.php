<?php
include("config.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE p,m
              FROM person p
              JOIN manager m
              on p.person_id=m.person_id
              WHERE m.manager_id = :id";

    try {

        $stmt = $database->prepare($query);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);


        if ($stmt->execute()) {
            echo "Record deleted successfully.";
            header("location: ManagersCRUD.php");

        } else {
            echo "Error deleting record.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No ID provided to delete.";
}
?>