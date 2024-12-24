<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];

    // Query to get person_id for the given employee_id
    $sql = "SELECT person_id FROM employee WHERE employee_id = :employee_id";
    $stmt = $database->prepare($sql);
    $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $person_id = $result['person_id'];

        // Query to increment absent_times for the given person_id
        $updateSql = "UPDATE person SET absent_times = COALESCE(absent_times, 0) + 1 WHERE person_id = :person_id";
        $updateStmt = $database->prepare($updateSql);
        $updateStmt->bindParam(':person_id', $person_id, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            // Redirect to the employee list page
            header("Location: retrieve_employees.php");
            exit();
        } else {
            echo "Failed to update absent times.";
        }
    } else {
        echo "Employee not found.";
    }
}
?>
