<?php
include "parent_auth.php";
include "../config/db_connection.php";


$showAppointmentsSql = "SELECT 

                        appointments.appointment_id,
                        child.child_name,
                        vaccines.vaccine_name,
                        hospital.hospital_name,
                        appointments.appointment_date,
                        appointments.appointment_time,
                        appointments.appointment_status

                        FROM appointments
                        JOIN child
                        ON appointments.child_id = child.child_id

                        JOIN vaccines
                        ON appointments.vaccine_id = vaccines.vaccine_id

                        JOIN hospital
                        ON appointments.hospital_id = hospital.hospital_id

                        WHERE child.parent_id = ?";

$showAppointmentStmt = mysqli_prepare($connect, $showAppointmentsSql);
$showAppointmentStmt->bind_param("i", $_SESSION["parent_id"]);
$showAppointmentStmt->execute();

$result = $showAppointmentStmt->get_result();

$totalAppointments = mysqli_num_rows($result);


// Status badge class
function getStatusClass($status) {

    switch (strtolower($status)) {

        case "pending":
            return "status-pending";

        case "confirmed":
            return "status-confirmed";

        case "cancelled":
            return "status-cancelled";

        case "completed":
            return "status-completed";

        default:
            return "status-default";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Appointments | Vaccination System</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="CSS/my-appointments.css">
</head>

<body>

    <div class="dashboard-container">

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">

            <div class="sidebar-brand">
                <div class="brand-icon">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>

                <div class="brand-text">
                    <h2>VaxCare</h2>
                    <span>Parent Portal</span>
                </div>
            </div>


            <div class="sidebar-menu">

                <p class="menu-heading">MAIN MENU</p>

                <a href="dashboard.php" class="nav-link">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>

                <a href="add_child.php" class="nav-link">
                    <i class="fa-solid fa-child"></i>
                    <span>Add Child</span>
                </a>

                <a href="book_appointment.php" class="nav-link">
                    <i class="fa-solid fa-calendar-plus"></i>
                    <span>Book Appointment</span>
                </a>

                <a href="my_appointments.php" class="nav-link active">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>My Appointments</span>
                </a>

                <a href="#" class="nav-link disabled-link">
                    <i class="fa-solid fa-syringe"></i>
                    <span>Vaccination History</span>
                    <span class="coming-soon">Soon</span>
                </a>

            </div>


            <div class="sidebar-bottom">

                <div class="sidebar-help">
                    <div class="help-icon">
                        <i class="fa-solid fa-circle-question"></i>
                    </div>

                    <div>
                        <h4>Need Help?</h4>
                        <p>Contact support for assistance.</p>
                    </div>
                </div>

                <a href="logout.php" class="nav-link logout-link"
                   onclick="return confirm('Are you sure you want to logout?');">

                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>

                </a>

            </div>

        </aside>


        <!-- Main Content -->
        <main class="main-content">

            <!-- Topbar -->
            <header class="topbar">

                <div class="topbar-left">

                    <button class="menu-toggle" id="menuToggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <div>
                        <p class="breadcrumb">Parent Portal / Appointments</p>
                        <h2>My Appointments</h2>
                    </div>

                </div>


                <div class="topbar-right">

                    <div class="topbar-icon">
                        <i class="fa-regular fa-bell"></i>
                    </div>

                    <div class="profile-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>

                </div>

            </header>


            <!-- Page Content -->
            <section class="page-content">

                <!-- Welcome Section -->
                <div class="page-heading">

                    <div>
                        <h1>Your Appointments</h1>

                        <p>
                            View and track all vaccination appointments
                            booked for your children.
                        </p>
                    </div>

                    <a href="book_appointment.php" class="book-btn">
                        <i class="fa-solid fa-plus"></i>
                        Book Appointment
                    </a>

                </div>


                <!-- Appointment Summary -->
                <div class="summary-card">

                    <div class="summary-icon">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>

                    <div class="summary-info">
                        <p>Total Appointments</p>
                        <h2><?php echo $totalAppointments; ?></h2>
                    </div>

                </div>


                <!-- Appointments Table -->
                <div class="appointments-card">

                    <div class="card-header">

                        <div>
                            <h3>Appointment Records</h3>

                            <p>
                                All appointments booked for your children.
                            </p>
                        </div>

                        <span class="record-count">
                            <?php echo $totalAppointments; ?> Records
                        </span>

                    </div>


                    <?php if ($totalAppointments > 0) { ?>

                        <div class="table-wrapper">

                            <table>

                                <thead>
                                    <tr>
                                        <th>Appointment ID</th>
                                        <th>Child</th>
                                        <th>Vaccine</th>
                                        <th>Hospital</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>


                                <tbody>

                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                                        <tr>

                                            <td>
                                                <span class="appointment-id">
                                                    #<?php echo htmlspecialchars($row["appointment_id"]); ?>
                                                </span>
                                            </td>


                                            <td>
                                                <div class="child-info">

                                                    <div class="child-icon">
                                                        <i class="fa-solid fa-child"></i>
                                                    </div>

                                                    <span>
                                                        <?php echo htmlspecialchars($row["child_name"]); ?>
                                                    </span>

                                                </div>
                                            </td>


                                            <td>
                                                <div class="vaccine-info">
                                                    <i class="fa-solid fa-syringe"></i>

                                                    <?php echo htmlspecialchars($row["vaccine_name"]); ?>
                                                </div>
                                            </td>


                                            <td>
                                                <div class="hospital-info">
                                                    <i class="fa-solid fa-hospital"></i>

                                                    <?php echo htmlspecialchars($row["hospital_name"]); ?>
                                                </div>
                                            </td>


                                            <td>
                                                <?php
                                                echo date(
                                                    "d M Y",
                                                    strtotime($row["appointment_date"])
                                                );
                                                ?>
                                            </td>


                                            <td>
                                                <?php
                                                echo date(
                                                    "h:i A",
                                                    strtotime($row["appointment_time"])
                                                );
                                                ?>
                                            </td>


                                            <td>
                                                <span class="status-badge <?php echo getStatusClass($row["appointment_status"]); ?>">

                                                    <span class="status-dot"></span>

                                                    <?php echo htmlspecialchars($row["appointment_status"]); ?>

                                                </span>
                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    <?php } else { ?>

                        <!-- Empty State -->
                        <div class="empty-state">

                            <div class="empty-icon">
                                <i class="fa-regular fa-calendar-xmark"></i>
                            </div>

                            <h3>No Appointments Yet</h3>

                            <p>
                                You haven't booked any vaccination appointments.
                                Book your first appointment to get started.
                            </p>

                            <a href="book_appointment.php" class="empty-btn">
                                <i class="fa-solid fa-plus"></i>
                                Book Your First Appointment
                            </a>

                        </div>

                    <?php } ?>

                </div>

            </section>

        </main>

    </div>


    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>


    <!-- Sidebar JavaScript -->
    <script>

        const menuToggle = document.getElementById("menuToggle");

        const sidebar = document.getElementById("sidebar");

        const sidebarOverlay = document.getElementById("sidebarOverlay");


        menuToggle.addEventListener("click", function () {

            sidebar.classList.toggle("show");

            sidebarOverlay.classList.toggle("active");

        });


        sidebarOverlay.addEventListener("click", function () {

            sidebar.classList.remove("show");

            sidebarOverlay.classList.remove("active");

        });

    </script>

</body>
</html>

<?php

$showAppointmentStmt->close();

?>