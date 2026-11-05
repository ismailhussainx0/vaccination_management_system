
<?php

include "../config/db_connection.php";
include "admin_auth.php";

// Check whether hospital ID is provided
if (!isset($_POST["hospital_id"]) || !is_numeric($_POST["hospital_id"])) {
    header("Location: manage_hospitals.php");
    exit;
}

$hospitalId = (int) $_POST["hospital_id"];

// Check whether this hospital has appointments
$checkSql = "SELECT COUNT(*) AS total_appointments
             FROM appointments
             WHERE hospital_id = ?";

$checkStmt = mysqli_prepare($connect, $checkSql);

if (!$checkStmt) {
    die("Error preparing appointment check: " . mysqli_error($connect));
}

$checkStmt->bind_param("i", $hospitalId);
$checkStmt->execute();

$checkResult = $checkStmt->get_result();
$appointmentData = mysqli_fetch_assoc($checkResult);

$checkStmt->close();

$totalAppointments = $appointmentData["total_appointments"];

// Do not delete hospital if appointments exist
if ($totalAppointments > 0) {
    $errorMessage = "This hospital cannot be deleted because it has existing appointments.";
} else {

    // Delete hospital
    $deleteSql = "DELETE FROM hospital WHERE hospital_id = ?";

    $deleteStmt = mysqli_prepare($connect, $deleteSql);

    if (!$deleteStmt) {
        die("Error preparing delete query: " . mysqli_error($connect));
    }

    $deleteStmt->bind_param("i", $hospitalId);
    $deleteStmt->execute();

    if ($deleteStmt->affected_rows > 0) {
        $deleteStmt->close();

        header("Location: manage_hospitals.php");
        exit;
    } else {
        $errorMessage = "Hospital not found or could not be deleted.";
    }

    $deleteStmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Hospital</title>

        <link rel="stylesheet" href="CSS/manage-hospitals.css">

</head>
<body>

    <div class="hospital-form">

        <h2>Delete Hospital</h2>

        <p class="error-message">
            <?php echo htmlspecialchars($errorMessage); ?>
        </p>

        <div class="form-actions">
            <a href="manage_hospitals.php" class="btn btn-secondary">
                Back to Hospitals
            </a>
        </div>

    </div>

</body>
</html>
