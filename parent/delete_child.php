<?php

include "parent_auth.php";
include "../config/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["child_id"])) {

    $childId = filter_var($_POST["child_id"], FILTER_VALIDATE_INT);
    $parentId = $_SESSION["parent_id"];

    if (!$childId || $childId <= 0) {
        die("Invalid child ID.");
    }

    try {

        // Start transaction
        $connect->begin_transaction();

        // STEP 1: Verify child belongs to logged-in parent
        $checkSql = "SELECT child_id
                     FROM child
                     WHERE child_id = ?
                     AND parent_id = ?";

        $checkStmt = $connect->prepare($checkSql);
        $checkStmt->bind_param("ii", $childId, $parentId);
        $checkStmt->execute();

        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows === 0) {
            throw new Exception("Child not found.");
        }

        $checkStmt->close();


        // STEP 2: Get appointment IDs
        $appointmentSql = "SELECT appointment_id
                           FROM appointments
                           WHERE child_id = ?";

        $appointmentStmt = $connect->prepare($appointmentSql);
        $appointmentStmt->bind_param("i", $childId);
        $appointmentStmt->execute();

        $appointmentResult = $appointmentStmt->get_result();

        $appointmentIds = [];

        while ($row = $appointmentResult->fetch_assoc()) {
            $appointmentIds[] = $row["appointment_id"];
        }

        $appointmentStmt->close();


        // STEP 3: Delete vaccination records
        if (!empty($appointmentIds)) {

            $recordSql = "DELETE FROM vaccination_record
                          WHERE appointment_id = ?";

            $recordStmt = $connect->prepare($recordSql);

            foreach ($appointmentIds as $appointmentId) {
                $recordStmt->bind_param("i", $appointmentId);
                $recordStmt->execute();
            }

            $recordStmt->close();


            // STEP 4: Delete appointments
            $deleteAppointments = "DELETE FROM appointments
                                   WHERE child_id = ?";

            $deleteAppointmentStmt = $connect->prepare($deleteAppointments);
            $deleteAppointmentStmt->bind_param("i", $childId);
            $deleteAppointmentStmt->execute();
            $deleteAppointmentStmt->close();
        }


        // STEP 5: Delete child
        $deleteChildSql = "DELETE FROM child
                           WHERE child_id = ?
                           AND parent_id = ?";

        $deleteChildStmt = $connect->prepare($deleteChildSql);
        $deleteChildStmt->bind_param("ii", $childId, $parentId);
        $deleteChildStmt->execute();
        $deleteChildStmt->close();


        // Complete transaction
        $connect->commit();

        header("Location: dashboard.php");
        exit;


    } catch (Throwable $e) {

        // Undo all changes if something fails
        $connect->rollback();



        die("Unable to delete child. Please try again.");
    }

}

header("Location: dashboard.php");
exit;

?>