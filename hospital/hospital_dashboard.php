<?php

include "../config/db_connection.php";
include "hospital_auth.php";

date_default_timezone_set("Asia/Karachi");

// STEP 1: HANDLE HOSPITAL APPOINTMENT STATUS UPDATE

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_POST["appointment_id"], $_POST["appointment_status"])) {
        exit("Appointment details are missing.");
    }

    $hospitalId = (int) $_SESSION["hospitalId"];
    $appointmentId = filter_var($_POST["appointment_id"], FILTER_VALIDATE_INT);
    $updatedStatus = $_POST["appointment_status"];

    // Validate appointment ID
    if ($appointmentId === false || $appointmentId <= 0) {
        exit("Invalid Appointment ID.");
    }

    // Hospital can only cancel or complete an already approved appointment.
    $allowedStatuses = ["Cancelled", "Completed"];

    if (!in_array($updatedStatus, $allowedStatuses, true)) {
        exit("Invalid status selected.");
    }

    try {

        mysqli_begin_transaction($connect);

        // STEP 2: Update only this hospital's Confirmed appointment.
        // This prevents the hospital from changing Pending or other hospitals' appointments.

        $updateSql = "UPDATE appointments
                      SET appointment_status = ?
                      WHERE appointment_id = ?
                      AND hospital_id = ?
                      AND appointment_status = 'Confirmed'";

        $updateStmt = mysqli_prepare($connect, $updateSql);

        $updateStmt->bind_param(
            "sii",
            $updatedStatus,
            $appointmentId,
            $hospitalId
        );

        $updateStmt->execute();

        // Exactly one Confirmed appointment must have been updated.
        if ($updateStmt->affected_rows !== 1) {
            mysqli_rollback($connect);
            $updateStmt->close();
            exit("Appointment not found, not approved, or already processed.");
        }

        $updateStmt->close();

        // STEP 3: Create a vaccination record only when Hospital marks it Completed.

        if ($updatedStatus === "Completed") {

            // Check whether a vaccination record already exists.
            $checkSql = "SELECT record_id
                         FROM vaccination_record
                         WHERE appointment_id = ?";

            $checkStmt = mysqli_prepare($connect, $checkSql);
            $checkStmt->bind_param("i", $appointmentId);
            $checkStmt->execute();

            $checkResult = $checkStmt->get_result();

            if (mysqli_num_rows($checkResult) === 0) {

                $vaccinationDate = date("Y-m-d");
                $vaccinationStatus = "Vaccinated";

                $insertSql = "INSERT INTO vaccination_record
                              (appointment_id, vaccination_status, vaccination_date)
                              VALUES (?, ?, ?)";

                $recordStmt = mysqli_prepare($connect, $insertSql);

                $recordStmt->bind_param(
                    "iss",
                    $appointmentId,
                    $vaccinationStatus,
                    $vaccinationDate
                );

                $recordStmt->execute();
                $recordStmt->close();
            }

            $checkStmt->close();
        }

        // Save the status update and, if completed, its vaccination record.
        mysqli_commit($connect);

        // STEP 4: Redirect after POST so refreshing does not submit the form again.
        header("Location: hospital_dashboard.php");
        exit;

    } catch (mysqli_sql_exception $error) {

        mysqli_rollback($connect);
        error_log($error->getMessage());
        exit("Something went wrong while updating the appointment.");

    }
}


// STEP 5: FETCH ONLY THIS HOSPITAL'S CONFIRMED APPOINTMENTS

try {

    $appointmentSql = "SELECT
        appointments.appointment_id,
        child.child_name,
        vaccines.vaccine_name,
        appointments.appointment_date,
        appointments.appointment_time,
        appointments.appointment_status

        FROM appointments

        JOIN child
        ON appointments.child_id = child.child_id

        JOIN vaccines
        ON appointments.vaccine_id = vaccines.vaccine_id

        WHERE appointments.hospital_id = ?
        AND appointments.appointment_status = 'Confirmed'";

    $appointmentStmt = mysqli_prepare($connect, $appointmentSql);

    $appointmentStmt->bind_param(
        "i",
        $_SESSION["hospitalId"]
    );

    $appointmentStmt->execute();

    $result = $appointmentStmt->get_result();

} catch (mysqli_sql_exception $error) {

    error_log($error->getMessage());
    exit("Unable to load appointments right now.");

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hospital Dashboard | VaccineCare</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="CSS/dashboard.css">
</head>

<body>

<div class="app">

    <div class="sidebar-overlay" data-menu-close></div>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar" aria-label="Main navigation">

        <div class="sidebar-head">
            <a href="hospital_dashboard.php" class="brand">
                <span class="brand-icon"><i class="fa-solid fa-heart-pulse"></i></span>
                <span class="brand-text">
                    <strong>VaccineCare</strong>
                    <small>Hospital Portal</small>
                </span>
            </a>

            <button type="button" class="icon-btn sidebar-close" data-menu-close aria-label="Close menu">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <nav class="sidebar-nav">

            <p class="nav-heading">Menu</p>

            <a href="hospital_dashboard.php" class="nav-link active" aria-current="page">
                <i class="fa-solid fa-table-columns"></i>
                <span>Dashboard</span>
            </a>

            <a href="#appointments" class="nav-link">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Appointments</span>
            </a>

            <a href="logout.php" class="nav-link logout-link">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>

        </nav>

        <div class="sidebar-foot">
            <div class="sidebar-user">
                <span class="avatar"><i class="fa-solid fa-hospital"></i></span>
                <div>
                    <strong>VaccineCare Portal</strong>
                    <small>Hospital Management</small>
                </div>
            </div>
        </div>

    </aside>


    <div class="app-main">

        <!-- TOPBAR -->
        <header class="topbar">

            <div class="topbar-left">

                <button type="button" class="icon-btn menu-btn" data-menu-toggle
                        aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-title">
                    <p>Hospital Portal</p>
                    <h2>Dashboard</h2>
                </div>

            </div>

            <div class="profile-chip">
                <span class="avatar"><i class="fa-solid fa-hospital"></i></span>

                <span class="profile-text">
                    <strong><?php echo htmlspecialchars($_SESSION["hospitalName"] ?? "Hospital Account"); ?></strong>
                    <small><?php echo date("d M Y"); ?></small>
                </span>
            </div>

        </header>


        <main class="page">

            <!-- WELCOME -->
            <div class="welcome">

                <div>
                    <span class="eyebrow">Hospital Workspace</span>

                    <h1>Welcome to your Dashboard</h1>

                    <p>Manage your appointments and keep vaccination records up to date.</p>
                </div>

                <div class="welcome-icon">
                    <i class="fa-solid fa-hospital-user"></i>
                </div>

            </div>


            <!-- APPOINTMENTS -->
            <section id="appointments">

                <div class="section-head">

                    <div>
                        <h2>Appointments</h2>
                        <p>View and manage your hospital appointments.</p>
                    </div>

                    <span class="pill">
                        <i class="fa-solid fa-calendar-days"></i>
                        <?php echo mysqli_num_rows($result); ?> Appointments
                    </span>

                </div>


                <div class="card">

                    <div class="card-head">

                        <div>
                            <h3>All Appointments</h3>
                            <p>Manage appointment status from here.</p>
                        </div>

                        <div class="table-search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" placeholder="Search appointments..." aria-label="Search appointments">
                        </div>

                    </div>


                    <?php if (mysqli_num_rows($result) > 0) { ?>

                        <div class="table-wrap">

                            <table class="data-table appointments-table">

                                <thead>
                                    <tr>
                                        <th>Appointment</th>
                                        <th>Child Name</th>
                                        <th>Vaccine</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Update Status</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                                        <tr>

                                            <td data-label="Appointment">
                                                <span class="id-tag">#<?php echo $row["appointment_id"]; ?></span>
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

                                            <td data-label="Date">
                                                <?php echo htmlspecialchars($row["appointment_date"]); ?>
                                            </td>

                                            <td data-label="Time">
                                                <?php echo htmlspecialchars($row["appointment_time"]); ?>
                                            </td>

                                            <td data-label="Status">
                                                <span class="status-badge status-<?php echo strtolower(htmlspecialchars($row["appointment_status"])); ?>">
                                                    <span class="status-dot"></span>
                                                    <?php echo htmlspecialchars($row["appointment_status"]); ?>
                                                </span>
                                            </td>

                                            <td data-label="Update">

                                                <form action="" method="post" class="status-form">

                                                    <input
                                                        type="hidden"
                                                        name="appointment_id"
                                                        value="<?php echo $row["appointment_id"]; ?>"
                                                    >

                                                    <select name="appointment_status" class="status-select" required>
                                                        <option value="" selected disabled>Choose status</option>
                                                        <option value="Completed">Completed</option>
                                                        <option value="Cancelled">Cancelled</option>
                                                    </select>

                                                    <button type="submit" name="update_status" class="update-btn">
                                                        <i class="fa-solid fa-rotate"></i>
                                                        Update
                                                    </button>

                                                </form>

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

                            <h3>No Appointments Found</h3>

                            <p>You don't have any appointments yet. New appointments will appear here.</p>

                        </div>

                    <?php } ?>

                </div>

            </section>

        </main>

        <footer class="app-footer">
            &copy; <?php echo date("Y"); ?> VaccineCare. Hospital Management System.
        </footer>

    </div>

</div>

<script src="JS/hospital.js"></script>

</body>
</html>