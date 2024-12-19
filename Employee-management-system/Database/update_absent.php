<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $person_id = $_POST['person_id'];

    // Query to increment absent_times for the given employee
    $sql = "UPDATE person SET absent_times = COALESCE(absent_times, 0) + 1 WHERE person_id = :person_id";
    $stmt = $database->prepare($sql);
    $stmt->bindParam(':person_id', $person_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect to the employee list page
        header("Location: retrieve_employees.php");
        exit();
    } else {
        echo "Failed to update absent times.";
    }
}
?>