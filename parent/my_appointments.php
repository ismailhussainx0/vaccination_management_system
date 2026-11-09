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

                        WHERE child.parent_id = ?
                        ORDER BY appointments.appointment_id DESC";

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

    <title>My Appointments | VaccineCare</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="CSS/parent-app.css">
</head>

<body>

<div class="app">

    <?php $activePage = "appointments"; include __DIR__ . "/includes/sidebar.php"; ?>

    <div class="app-main">

        <header class="topbar">

            <div class="topbar-left">

                <button type="button" class="icon-btn menu-btn" data-menu-toggle
                        aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-title">
                    <p>Parent Portal</p>
                    <h2>My Appointments</h2>
                </div>

            </div>

            <div class="profile-chip">
                <span class="avatar"><?php echo htmlspecialchars(strtoupper(substr($_SESSION["parent_name"], 0, 1))); ?></span>

                <span class="profile-text">
                    <strong><?php echo htmlspecialchars($_SESSION["parent_name"]); ?></strong>
                    <small>Parent Account</small>
                </span>
            </div>

        </header>

        <main class="page">

            <div class="page-head">

                <div>
                    <span class="eyebrow">Appointments</span>
                    <h1>Your Appointments</h1>
                    <p>View and track all vaccination appointments booked for your children.</p>
                </div>

                <a href="book_appointment.php" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Book Appointment
                </a>

            </div>


            <div class="card stat">

                <div class="stat-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>

                <div>
                    <p>Total Appointments</p>
                    <h2><?php echo $totalAppointments; ?></h2>
                </div>

            </div>


            <div class="card">

                <div class="card-head">

                    <div>
                        <h3>Appointment Records</h3>
                        <p>All appointments booked for your children.</p>
                    </div>

                    <span class="pill"><?php echo $totalAppointments; ?> Records</span>

                </div>


                <?php if ($totalAppointments > 0) { ?>

                    <div class="table-wrap">

                        <table class="data-table">

                            <thead>
                                <tr>
                                    <th>ID</th>
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

                                        <td data-label="Appointment">
                                            <span class="id-tag">#<?php echo htmlspecialchars($row["appointment_id"]); ?></span>
                                        </td>

                                        <td data-label="Child">
                                            <div class="cell-person">
                                                <span class="avatar-sm"><i class="fa-solid fa-child"></i></span>
                                                <span><?php echo htmlspecialchars($row["child_name"]); ?></span>
                                            </div>
                                        </td>

                                        <td data-label="Vaccine">
                                            <span class="cell-icon">
                                                <i class="fa-solid fa-syringe"></i>
                                                <?php echo htmlspecialchars($row["vaccine_name"]); ?>
                                            </span>
                                        </td>

                                        <td data-label="Hospital">
                                            <span class="cell-icon">
                                                <i class="fa-solid fa-hospital"></i>
                                                <?php echo htmlspecialchars($row["hospital_name"]); ?>
                                            </span>
                                        </td>

                                        <td data-label="Date">
                                            <?php
                                            $appointmentDateTimestamp = strtotime($row["appointment_date"]);
                                            if ($appointmentDateTimestamp !== false && $row["appointment_date"] !== "0000-00-00") {
                                                echo date("d M Y", $appointmentDateTimestamp);
                                            } else {
                                                echo "Date unavailable";
                                            }
                                            ?>
                                        </td>

                                        <td data-label="Time">
                                            <?php
                                            $appointmentTimeTimestamp = strtotime($row["appointment_time"]);
                                            if ($appointmentTimeTimestamp !== false && !empty($row["appointment_time"])) {
                                                echo date("h:i A", $appointmentTimeTimestamp);
                                            } else {
                                                echo "Time unavailable";
                                            }
                                            ?>
                                        </td>

                                        <td data-label="Status">
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

                    <div class="empty-state">

                        <div class="empty-icon">
                            <i class="fa-regular fa-calendar-xmark"></i>
                        </div>

                        <h3>No Appointments Yet</h3>

                        <p>You haven't booked any vaccination appointments. Book your first appointment to get started.</p>

                        <a href="book_appointment.php" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i>
                            Book Your First Appointment
                        </a>

                    </div>

                <?php } ?>

            </div>

        </main>

        <footer class="app-footer">
            &copy; 2026 VaccineCare. All rights reserved.
        </footer>

    </div>

</div>

<script src="JS/parent.js"></script>

</body>
</html>

<?php

$showAppointmentStmt->close();

?>