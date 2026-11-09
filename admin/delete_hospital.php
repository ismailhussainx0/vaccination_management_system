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

    <title>Delete Hospital | VaccineCare Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="CSS/admin-app.css">
</head>

<body>

<div class="app">

    <?php $activePage = "hospitals"; $adminBase = ""; include __DIR__ . "/includes/sidebar.php"; ?>

    <div class="app-main">

        <header class="topbar">

            <div class="topbar-left">

                <button type="button" class="icon-btn menu-btn" data-menu-toggle
                        aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-title">
                    <p>Admin Panel</p>
                    <h2>Delete Hospital</h2>
                </div>

            </div>

            <div class="profile-chip">
                <span class="avatar"><?php echo htmlspecialchars(strtoupper(substr($_SESSION["adminName"], 0, 1))); ?></span>

                <span class="profile-text">
                    <strong><?php echo htmlspecialchars($_SESSION["adminName"]); ?></strong>
                    <small>Administrator</small>
                </span>
            </div>

        </header>

        <main class="page">

            <div class="card not-found empty-state">

                <div class="empty-icon" style="background: var(--danger-bg); color: var(--danger);">
                    <i class="fa-solid fa-hospital"></i>
                </div>

                <h3>Hospital Not Deleted</h3>

                <p><?php echo htmlspecialchars($errorMessage); ?></p>

                <a href="manage_hospitals.php" class="btn btn-primary">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Hospitals
                </a>

            </div>

        </main>

        <footer class="app-footer">
            &copy; 2026 VaccineCare. Vaccination Management System.
        </footer>

    </div>

</div>

<script src="JS/admin.js"></script>

</body>
</html>
