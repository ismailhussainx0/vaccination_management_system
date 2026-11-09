<?php

include "../../config/db_connection.php";
include "../admin_auth.php";

$selectAppointments = "SELECT 

                    appointments.appointment_id,
                    child.child_name,
                    parents.name AS parent_name,
                    hospital.hospital_name,
                    vaccines.vaccine_name,
                    appointments.appointment_date,
                    appointments.appointment_time,
                    appointments.appointment_status

                    FROM appointments
                    
                    JOIN child
                    ON appointments.child_id = child.child_id
                    
                    JOIN parents
                    ON child.parent_id = parents.parent_id
                    
                    JOIN hospital
                    ON appointments.hospital_id = hospital.hospital_id

                    JOIN vaccines
                    ON appointments.vaccine_id = vaccines.vaccine_id
                    
                    WHERE appointments.appointment_status = 'Pending'
                    ORDER BY appointments.appointment_id  DESC";

$result = mysqli_query($connect, $selectAppointments);

if(!$result){
    die(mysqli_error($connect));
}




?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Appointments | VaccineCare Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="../CSS/admin-app.css">
</head>

<body>

<div class="app">

    <?php $activePage = "appointments"; $adminBase = "../"; include __DIR__ . "/../includes/sidebar.php"; ?>

    <div class="app-main">

        <header class="topbar">

            <div class="topbar-left">

                <button type="button" class="icon-btn menu-btn" data-menu-toggle
                        aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-title">
                    <p>Admin Panel</p>
                    <h2>Manage Appointments</h2>
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

            <div class="page-head">

                <div>
                    <span class="eyebrow">Appointment Management</span>
                    <h1>Manage Appointments</h1>
                    <p>Review and manage pending appointment requests.</p>
                </div>

            </div>


            <div class="card">

                <div class="card-head">

                    <div>
                        <h3>Pending Appointment Requests</h3>
                        <p>Approve requests to send them to the selected hospital, or reject them.</p>
                    </div>

                    <span class="pill">
                        <i class="fa-solid fa-clock"></i>
                        <?php echo mysqli_num_rows($result); ?> Pending
                    </span>

                </div>


                <?php if (mysqli_num_rows($result) > 0): ?>

                    <div class="table-wrap">

                        <table class="data-table">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Child Name</th>
                                    <th>Parent Name</th>
                                    <th>Hospital</th>
                                    <th>Vaccine</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                                    <tr>

                                        <td data-label="ID">
                                            <span class="id-tag">#<?= (int) $row["appointment_id"] ?></span>
                                        </td>

                                        <td data-label="Child">
                                            <div class="cell-person">
                                                <span class="avatar-sm"><i class="fa-solid fa-child"></i></span>
                                                <span><?= htmlspecialchars($row["child_name"]) ?></span>
                                            </div>
                                        </td>

                                        <td data-label="Parent">
                                            <?= htmlspecialchars($row["parent_name"]) ?>
                                        </td>

                                        <td data-label="Hospital">
                                            <span class="cell-icon">
                                                <i class="fa-solid fa-hospital"></i>
                                                <?= htmlspecialchars($row["hospital_name"]) ?>
                                            </span>
                                        </td>

                                        <td data-label="Vaccine">
                                            <span class="cell-icon">
                                                <i class="fa-solid fa-syringe"></i>
                                                <?= htmlspecialchars($row["vaccine_name"]) ?>
                                            </span>
                                        </td>

                                        <td data-label="Date"><?= htmlspecialchars($row["appointment_date"]) ?></td>

                                        <td data-label="Time"><?= htmlspecialchars($row["appointment_time"]) ?></td>

                                        <td data-label="Status">
                                            <span class="status-badge status-pending">
                                                <span class="status-dot"></span>
                                                <?= htmlspecialchars($row["appointment_status"]) ?>
                                            </span>
                                        </td>

                                        <td data-label="Actions">

                                            <div class="row-actions">

                                                <form action="update_appointment_status.php" method="POST">
                                                    <input type="hidden" name="appointment_id" value="<?= (int) $row["appointment_id"] ?>">
                                                    <input type="hidden" name="status" value="Confirmed">
                                                    <button type="submit" class="action-btn approve-btn">
                                                        <i class="fa-solid fa-check"></i>
                                                        Approve
                                                    </button>
                                                </form>

                                                <form action="update_appointment_status.php" method="POST"
                                                      onsubmit="return confirm('Reject this appointment request?');">
                                                    <input type="hidden" name="appointment_id" value="<?= (int) $row["appointment_id"] ?>">
                                                    <input type="hidden" name="status" value="Cancelled">
                                                    <button type="submit" class="action-btn reject-btn">
                                                        <i class="fa-solid fa-xmark"></i>
                                                        Reject
                                                    </button>
                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>

                <?php else: ?>

                    <div class="empty-state">

                        <div class="empty-icon">
                            <i class="fa-regular fa-calendar-check"></i>
                        </div>

                        <h3>All Caught Up</h3>

                        <p>No pending appointments found.</p>

                    </div>

                <?php endif; ?>

            </div>

        </main>

        <footer class="app-footer">
            &copy; 2026 VaccineCare. Vaccination Management System.
        </footer>

    </div>

</div>

<script src="../JS/admin.js"></script>

</body>
</html>
