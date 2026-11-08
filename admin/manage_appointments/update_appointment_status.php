
<?php

include "../../config/db_connection.php";
include "../admin_auth.php";

// STEP 1: Check request method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: manage_appointment.php");
    exit;
}

// STEP 2: Get data from POST
$appointment_id = $_POST["appointment_id"] ?? "";
$status = $_POST["status"] ?? "";

// STEP 3: Validate appointment ID
if (!filter_var($appointment_id, FILTER_VALIDATE_INT) || $appointment_id <= 0) {
    die("Invalid appointment ID.");
}

// STEP 4: Allow only Confirmed or Cancelled
$allowedStatuses = ["Confirmed", "Cancelled"];

if (!in_array($status, $allowedStatuses, true)) {
    die("Invalid appointment status.");
}

// STEP 5: Update appointment
$sql = "UPDATE appointments
        SET appointment_status = ?
        WHERE appointment_id = ?
        AND appointment_status = 'Pending'";

$stmt = mysqli_prepare($connect, $sql);

if (!$stmt) {
    error_log(mysqli_error($connect));
    die("Something went wrong. Please try again.");
}

// STEP 6: Bind parameters
$stmt->bind_param("si", $status, $appointment_id);

// STEP 7: Execute query
if (!$stmt->execute()) {
    error_log($stmt->error);
    $stmt->close();
    die("Unable to update appointment.");
}

// STEP 8: Check whether appointment was updated
if ($stmt->affected_rows === 1) {
    $stmt->close();

    header("Location: manage_appointment.php");
    exit;
}

$stmt->close();

die("Appointment not found or it has already been processed.");

?>
