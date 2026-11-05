<?php

include "../config/db_connection.php";
include "parent_auth.php";


$showRecordSql = "SELECT

                    vaccination_record.record_id,
                    child.child_name,
                    vaccines.vaccine_name,
                    hospital.hospital_name,
                    vaccination_record.vaccination_status,
                    vaccination_record.vaccination_date

                    FROM vaccination_record

                    JOIN appointments
                    ON vaccination_record.appointment_id = appointments.appointment_id

                    JOIN child
                    ON appointments.child_id = child.child_id

                    JOIN vaccines
                    ON appointments.vaccine_id = vaccines.vaccine_id

                    JOIN hospital
                    ON appointments.hospital_id = hospital.hospital_id

                    WHERE child.parent_id = ?";


$showRecordStmt = mysqli_prepare($connect, $showRecordSql);

$showRecordStmt->bind_param("i", $_SESSION["parent_id"]);

$showRecordStmt->execute();

$result = $showRecordStmt->get_result();

$totalRecords = mysqli_num_rows($result);


// Vaccination status badge
function getVaccinationStatusClass($status) {

    switch (strtolower($status)) {

        case "vaccinated":
            return "status-vaccinated";

        case "pending":
            return "status-pending";

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

    <title>Vaccination Records | VaccineCare</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="CSS/parent-app.css">
</head>

<body>

<div class="app">

    <?php $activePage = "records"; include __DIR__ . "/includes/sidebar.php"; ?>

    <div class="app-main">

        <header class="topbar">

            <div class="topbar-left">

                <button type="button" class="icon-btn menu-btn" data-menu-toggle
                        aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-title">
                    <p>Parent Portal</p>
                    <h2>Vaccination Records</h2>
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
                    <span class="eyebrow">Health Records</span>
                    <h1>Vaccination Records</h1>
                    <p>Track the vaccination records of all your children.</p>
                </div>

                <a href="book_appointment.php" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Book Appointment
                </a>

            </div>


            <div class="card stat">

                <div class="stat-icon">
                    <i class="fa-solid fa-shield-heart"></i>
                </div>

                <div>
                    <p>Total Vaccination Records</p>
                    <h2><?php echo $totalRecords; ?></h2>
                </div>

            </div>


            <div class="card">

                <div class="card-head">

                    <div>
                        <h3>Vaccination Records</h3>
                        <p>A record of vaccines administered to your children.</p>
                    </div>

                    <span class="pill"><?php echo $totalRecords; ?> Records</span>

                </div>


                <?php if ($totalRecords > 0) { ?>

                    <div class="table-wrap">

                        <table class="data-table">

                            <thead>
                                <tr>
                                    <th>Record ID</th>
                                    <th>Child</th>
                                    <th>Vaccine</th>
                                    <th>Hospital</th>
                                    <th>Status</th>
                                    <th>Vaccination Date</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                                    <tr>

                                        <td data-label="Record">
                                            <span class="id-tag">#<?php echo htmlspecialchars($row["record_id"]); ?></span>
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

                                        <td data-label="Status">
                                            <span class="status-badge <?php echo getVaccinationStatusClass($row["vaccination_status"]); ?>">
                                                <span class="status-dot"></span>
                                                <?php echo htmlspecialchars($row["vaccination_status"]); ?>
                                            </span>
                                        </td>

                                        <td data-label="Date">
                                            <?php
                                            if (!empty($row["vaccination_date"])) {
                                                echo date("d M Y", strtotime($row["vaccination_date"]));
                                            } else {
                                                echo "Not available";
                                            }
                                            ?>
                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                <?php } else { ?>

                    <div class="empty-state">

                        <div class="empty-icon">
                            <i class="fa-solid fa-syringe"></i>
                        </div>

                        <h3>No Vaccination Records Yet</h3>

                        <p>Vaccination records will appear here once your children's vaccinations have been completed and recorded by the hospital.</p>

                        <a href="my_appointments.php" class="btn btn-primary">
                            <i class="fa-solid fa-calendar-check"></i>
                            View My Appointments
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

$showRecordStmt->close();

?>
