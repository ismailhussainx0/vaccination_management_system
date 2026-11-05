<?php
include "../config/db_connection.php";
include "admin_auth.php";

if (!isset($_POST["vaccine_id"]) || !is_numeric($_POST["vaccine_id"])) {
    header("Location: manage_vaccines.php");
    exit;
}
$vaccineId = (int) $_POST["vaccine_id"];

// Preserve appointment history: do not delete a vaccine used by an appointment.
$checkSql = "SELECT COUNT(*) AS total_appointments FROM appointments WHERE vaccine_id = ?";
$checkStmt = mysqli_prepare($connect, $checkSql);
if (!$checkStmt) {
    die("Error preparing appointment check: " . mysqli_error($connect));
}
$checkStmt->bind_param("i", $vaccineId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();
$appointmentData = mysqli_fetch_assoc($checkResult);
$checkStmt->close();

if ((int) $appointmentData["total_appointments"] > 0) {
    $errorMessage = "This vaccine cannot be deleted because it is linked to existing appointments.";
} else {
    $deleteSql = "DELETE FROM vaccines WHERE vaccine_id = ?";
    $deleteStmt = mysqli_prepare($connect, $deleteSql);
    if (!$deleteStmt) {
        die("Error preparing delete query: " . mysqli_error($connect));
    }
    $deleteStmt->bind_param("i", $vaccineId);
    $deleteStmt->execute();
    if ($deleteStmt->affected_rows > 0) {
        $deleteStmt->close();
        header("Location: manage_vaccines.php?message=deleted");
        exit;
    }
    $errorMessage = "Vaccine not found or could not be deleted.";
    $deleteStmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Vaccine</title>
    <link rel="stylesheet" href="CSS/manage-vaccines.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <nav class="module-nav">
        <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
        <a href="manage_hospitals.php"><i class="fa-solid fa-hospital"></i> Manage Hospitals</a>
        <a href="manage_vaccines.php" class="active"><i class="fa-solid fa-syringe"></i> Manage Vaccines</a>
    </nav>
<main class="page-container form-page">
    <section class="content-card form-card">
        <p class="eyebrow">Admin Panel</p>
        <h1>Vaccine Not Deleted</h1>
        <div class="alert alert-error"><?php echo htmlspecialchars($errorMessage); ?></div>
        <a href="manage_vaccines.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Vaccines</a>
    </section>
</main>
</body>
</html>
