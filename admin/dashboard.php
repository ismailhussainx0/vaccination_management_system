<?php

include "../config/db_connection.php";
include "admin_auth.php";

// PARENT COUNT 
$parentSql = "SELECT COUNT(*) AS total_parents FROM parents";
$parentResult = mysqli_query($connect, $parentSql);
$parentData = mysqli_fetch_assoc($parentResult);

$totalParents = $parentData["total_parents"];


// CHILD COUNT 
$childSql = "SELECT COUNT(*) AS total_child FROM child";
$childResult = mysqli_query($connect, $childSql);
$childData = mysqli_fetch_assoc($childResult);

$totalChildren = $childData["total_child"];



// HOSPITAL COUNT 
$hospitalSql = "SELECT COUNT(*) AS total_hospital FROM hospital";
$hospitalResult = mysqli_query($connect, $hospitalSql);
$hospitalData = mysqli_fetch_assoc($hospitalResult);

$totalHospitals = $hospitalData["total_hospital"];


// HOSPITAL COUNT 
$vaccineSql = "SELECT COUNT(*) AS total_vaccines FROM vaccines";
$vaccineResult = mysqli_query($connect, $vaccineSql);
$vaccinesData = mysqli_fetch_assoc($vaccineResult);

$totalVaccines = $vaccinesData["total_vaccines"];



// TOTAL APPOINTMENTS
$appointmentSql = "SELECT COUNT(*) AS total_appointments FROM appointments";
$appointmentResult = mysqli_query($connect, $appointmentSql);
$appointmentData = mysqli_fetch_assoc($appointmentResult);

$totalAppointments = $appointmentData["total_appointments"];



// PENDING APPOINTMENTS
$pendingSql = "SELECT COUNT(*) AS pending_appointments
               FROM appointments
               WHERE appointment_status = 'Pending'";

$pendingResult = mysqli_query($connect, $pendingSql);
$pendingData = mysqli_fetch_assoc($pendingResult);

$totalPending = $pendingData["pending_appointments"];


// COMPLETED APPOINTMENTS
$completedSql = "SELECT COUNT(*) AS completed_appointments
                 FROM appointments
                 WHERE appointment_status = 'Completed'";

$completedResult = mysqli_query($connect, $completedSql);
$completedData = mysqli_fetch_assoc($completedResult);

$totalCompleted = $completedData["completed_appointments"];








?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | VaccineCare Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="CSS/admin-app.css">
</head>

<body>

<div class="app">

    <?php $activePage = "dashboard"; $adminBase = ""; include __DIR__ . "/includes/sidebar.php"; ?>

    <div class="app-main">

        <header class="topbar">

            <div class="topbar-left">

                <button type="button" class="icon-btn menu-btn" data-menu-toggle
                        aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-title">
                    <p>Admin Panel</p>
                    <h2>Dashboard</h2>
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

            <!-- WELCOME -->
            <div class="welcome">

                <div>
                    <span class="eyebrow">Admin Dashboard</span>

                    <h1>Welcome, <?php echo htmlspecialchars($_SESSION["adminName"]); ?>!</h1>

                    <p>Overview of your vaccination management system.</p>
                </div>

                <div class="welcome-icon">
                    <i class="fa-solid fa-user-shield"></i>
                </div>

            </div>


            <!-- QUICK ACTIONS -->
            <div class="quick-grid">

                <a href="manage_hospitals.php" class="quick-card">
                    <span class="quick-icon"><i class="fa-solid fa-hospital"></i></span>
                    <strong>Manage Hospitals</strong>
                    <span class="sub">Add, edit or remove</span>
                </a>

                <a href="manage_vaccines.php" class="quick-card">
                    <span class="quick-icon"><i class="fa-solid fa-syringe"></i></span>
                    <strong>Manage Vaccines</strong>
                    <span class="sub">Maintain vaccine list</span>
                </a>

                <a href="manage_appointments/manage_appointment.php" class="quick-card">
                    <span class="quick-icon"><i class="fa-solid fa-calendar-check"></i></span>
                    <strong>Manage Appointments</strong>
                    <span class="sub">Approve or reject requests</span>
                </a>

                <a href="add_hospital.php" class="quick-card">
                    <span class="quick-icon"><i class="fa-solid fa-plus"></i></span>
                    <strong>Add Hospital</strong>
                    <span class="sub">Register a new hospital</span>
                </a>

            </div>


            <!-- SYSTEM OVERVIEW -->
            <div class="section-head">
                <div>
                    <h2>System Overview</h2>
                    <p>Here is your system's current summary.</p>
                </div>
            </div>

            <div class="stats-grid">

                <div class="stat-card">

                    <div class="stat-top">
                        <div class="stat-icon icon-teal">
                            <i class="fa-solid fa-users"></i>
                        </div>

                        <span class="stat-tag">Registered</span>
                    </div>

                    <p>Total Parents</p>
                    <h3><?php echo $totalParents; ?></h3>

                </div>

                <div class="stat-card">

                    <div class="stat-top">
                        <div class="stat-icon icon-blue">
                            <i class="fa-solid fa-child-reaching"></i>
                        </div>

                        <span class="stat-tag">Registered</span>
                    </div>

                    <p>Total Children</p>
                    <h3><?php echo $totalChildren; ?></h3>

                </div>

                <div class="stat-card">

                    <div class="stat-top">
                        <div class="stat-icon icon-purple">
                            <i class="fa-solid fa-hospital"></i>
                        </div>

                        <span class="stat-tag">Registered</span>
                    </div>

                    <p>Total Hospitals</p>
                    <h3><?php echo $totalHospitals; ?></h3>

                </div>

                <div class="stat-card">

                    <div class="stat-top">
                        <div class="stat-icon icon-rose">
                            <i class="fa-solid fa-syringe"></i>
                        </div>

                        <span class="stat-tag">Available</span>
                    </div>

                    <p>Total Vaccines</p>
                    <h3><?php echo $totalVaccines; ?></h3>

                </div>

                <div class="stat-card">

                    <div class="stat-top">
                        <div class="stat-icon icon-slate">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>

                        <span class="stat-tag">Overall</span>
                    </div>

                    <p>Total Appointments</p>
                    <h3><?php echo $totalAppointments; ?></h3>

                </div>

                <div class="stat-card">

                    <div class="stat-top">
                        <div class="stat-icon icon-amber">
                            <i class="fa-solid fa-clock"></i>
                        </div>

                        <span class="stat-tag">Awaiting</span>
                    </div>

                    <p>Pending Appointments</p>
                    <h3><?php echo $totalPending; ?></h3>

                </div>

                <div class="stat-card">

                    <div class="stat-top">
                        <div class="stat-icon icon-green">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>

                        <span class="stat-tag">Finished</span>
                    </div>

                    <p>Completed Appointments</p>
                    <h3><?php echo $totalCompleted; ?></h3>

                </div>

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
