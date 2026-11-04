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

    <title>Admin Dashboard | VMS</title>

    <link rel="stylesheet" href="CSS/admin.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="dashboard-container">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <div class="sidebar-header">
                <div class="logo-icon">
                    <i class="fa-solid fa-hospital"></i>
                </div>

                <div>
                    <h2>VMS Admin</h2>
                    <p>Management Panel</p>
                </div>
            </div>

            <div class="sidebar-menu">

                <p class="menu-heading">MAIN MENU</p>

                <a href="dashboard.php" class="menu-link active">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>

            </div>

            <div class="sidebar-bottom">

                <div class="sidebar-admin">
                    <div class="admin-avatar">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>

                    <div class="admin-details">
                        <h4><?php echo htmlspecialchars($_SESSION["adminName"]); ?></h4>
                        <p>Administrator</p>
                    </div>
                </div>

                <a href="admin_logout.php" class="logout-link">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>

            </div>

        </aside>


        <!-- MAIN CONTENT -->
        <main class="main-content">

            <!-- TOPBAR -->
            <header class="topbar">

                <div class="page-heading">
                    <h1>Dashboard</h1>
                    <p>Overview of your vaccination management system.</p>
                </div>

                <div class="topbar-profile">
                    <div class="profile-icon">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>

                    <div>
                        <h4><?php echo htmlspecialchars($_SESSION["adminName"]); ?></h4>
                        <p>Admin</p>
                    </div>
                </div>

            </header>


            <!-- DASHBOARD CONTENT -->
            <section class="dashboard-content">

                <div class="section-heading">
                    <div>
                        <h2>System Overview</h2>
                        <p>Here is your system's current summary.</p>
                    </div>
                </div>


                <!-- STATISTICS CARDS -->
                <div class="stats-grid">

                    <!-- PARENTS -->
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon parents-icon">
                                <i class="fa-solid fa-users"></i>
                            </div>

                            <span class="stat-label">Registered</span>
                        </div>

                        <div class="stat-info">
                            <p>Total Parents</p>
                            <h3><?php echo $totalParents; ?></h3>
                        </div>

                    </div>


                    <!-- CHILDREN -->
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon children-icon">
                                <i class="fa-solid fa-child-reaching"></i>
                            </div>

                            <span class="stat-label">Registered</span>
                        </div>

                        <div class="stat-info">
                            <p>Total Children</p>
                            <h3><?php echo $totalChildren; ?></h3>
                        </div>

                    </div>


                    <!-- HOSPITALS -->
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon hospitals-icon">
                                <i class="fa-solid fa-hospital"></i>
                            </div>

                            <span class="stat-label">Registered</span>
                        </div>

                        <div class="stat-info">
                            <p>Total Hospitals</p>
                            <h3><?php echo $totalHospitals; ?></h3>
                        </div>

                    </div>


                    <!-- VACCINES -->
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon vaccines-icon">
                                <i class="fa-solid fa-syringe"></i>
                            </div>

                            <span class="stat-label">Available</span>
                        </div>

                        <div class="stat-info">
                            <p>Total Vaccines</p>
                            <h3><?php echo $totalVaccines; ?></h3>
                        </div>

                    </div>


                    <!-- APPOINTMENTS -->
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon appointments-icon">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>

                            <span class="stat-label">Overall</span>
                        </div>

                        <div class="stat-info">
                            <p>Total Appointments</p>
                            <h3><?php echo $totalAppointments; ?></h3>
                        </div>

                    </div>


                    <!-- PENDING -->
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon pending-icon">
                                <i class="fa-solid fa-clock"></i>
                            </div>

                            <span class="stat-label">Awaiting</span>
                        </div>

                        <div class="stat-info">
                            <p>Pending Appointments</p>
                            <h3><?php echo $totalPending; ?></h3>
                        </div>

                    </div>


                    <!-- COMPLETED -->
                    <div class="stat-card">

                        <div class="stat-top">
                            <div class="stat-icon completed-icon">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>

                            <span class="stat-label">Finished</span>
                        </div>

                        <div class="stat-info">
                            <p>Completed Appointments</p>
                            <h3><?php echo $totalCompleted; ?></h3>
                        </div>

                    </div>

                </div>

            </section>

            <footer class="dashboard-footer">
                Vaccination Management System &copy; 2026
            </footer>

        </main>

    </div>

</body>
</html>
