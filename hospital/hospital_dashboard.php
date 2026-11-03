<?php

include "../config/db_connection.php";
include "hospital_auth.php";

date_default_timezone_set("Asia/Karachi");

// STEP 1: HANDLE APPOINTMENT STATUS UPDATE

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["appointment_id"], $_POST["appointment_status"])) {

        $hospitalId = $_SESSION["hospitalId"];
        $appointmentId = filter_var($_POST["appointment_id"], FILTER_VALIDATE_INT);
        $updatedStatus = $_POST["appointment_status"];

        // Validate appointment ID
        if ($appointmentId === false || $appointmentId <= 0) {
            exit("Invalid Appointment ID");
        }

        // Validate appointment status
        $allowedStatuses = ["Pending", "Confirmed", "Cancelled", "Completed"];

        if (!in_array($updatedStatus, $allowedStatuses, true)) {
            exit("Wrong Status Chosen");
        }

        try {

            // STEP 2: UPDATE APPOINTMENT STATUS

            $updateSql = "UPDATE appointments
                          SET appointment_status = ?
                          WHERE appointment_id = ?
                          AND hospital_id = ?";

            $updateStmt = mysqli_prepare($connect, $updateSql);

            $updateStmt->bind_param(
                "sii",
                $updatedStatus,
                $appointmentId,
                $hospitalId
            );

            $updateStmt->execute();

            // STEP 3: CREATE VACCINATION RECORD IF COMPLETED

            if ($updatedStatus === "Completed") {

                // Verify appointment belongs to logged-in hospital
                // and its status is Completed

                $verifySql = "SELECT appointment_id
                              FROM appointments
                              WHERE appointment_id = ?
                              AND hospital_id = ?
                              AND appointment_status = 'Completed'";

                $verifyStmt = mysqli_prepare($connect, $verifySql);

                $verifyStmt->bind_param(
                    "ii",
                    $appointmentId,
                    $hospitalId
                );

                $verifyStmt->execute();

                $verifyResult = $verifyStmt->get_result();

                if (mysqli_num_rows($verifyResult) > 0) {

                    // Check whether vaccination record already exists

                    $checkSql = "SELECT record_id
                                 FROM vaccination_record
                                 WHERE appointment_id = ?";

                    $checkStmt = mysqli_prepare($connect, $checkSql);

                    $checkStmt->bind_param("i", $appointmentId);

                    $checkStmt->execute();

                    $checkResult = $checkStmt->get_result();

                    // Insert only if record does not exist

                    if (mysqli_num_rows($checkResult) === 0) {

                        $vaccinationDate = date("Y-m-d");
                        $vaccinationStatus = "Vaccinated";

                        $vaccinationRecordSql = "INSERT INTO vaccination_record
                        (appointment_id, vaccination_status, vaccination_date)
                        VALUES (?, ?, ?)";

                        $recordStmt = mysqli_prepare(
                            $connect,
                            $vaccinationRecordSql
                        );

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

                $verifyStmt->close();
            }

            $updateStmt->close();

            // STEP 4: REDIRECT AFTER POST

            header("Location: hospital_dashboard.php");
            exit;

        } catch (mysqli_sql_exception $error) {

            echo "Something Went Wrong!";

        }

    } else {

        echo "Values are not set properly";

    }
}


// STEP 5: FETCH APPOINTMENTS FOR DASHBOARD

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

        WHERE appointments.hospital_id = ?";

    $appointmentStmt = mysqli_prepare($connect, $appointmentSql);

    $appointmentStmt->bind_param(
        "i",
        $_SESSION["hospitalId"]
    );

    $appointmentStmt->execute();

    $result = $appointmentStmt->get_result();

} catch (mysqli_sql_exception $error) {

    exit("Something Went Wrong!");

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hospital Dashboard | VaxCare</title>

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Hospital Custom CSS -->
    <link rel="stylesheet" href="hospital.css">
</head>

<body>

    <div class="dashboard-wrapper">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <div class="brand">
                <div class="brand-icon">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>

                <span>Vax<span class="brand-highlight">Care</span></span>
            </div>

            <div class="sidebar-label">MENU</div>

            <nav class="sidebar-nav">

                <a href="hospital_dashboard.php" class="nav-link active">
                    <i class="fa-solid fa-table-columns"></i>
                    <span>Dashboard</span>
                </a>

                <a href="#appointments" class="nav-link">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Appointments</span>
                </a>

            </nav>

            <div class="sidebar-bottom">
                <div class="sidebar-help-icon">
                    <i class="fa-solid fa-shield-heart"></i>
                </div>

                <div>
                    <h6>VaxCare Portal</h6>
                    <p>Hospital Management</p>
                </div>
            </div>

        </aside>


        <!-- MAIN CONTENT -->
        <main class="main-content">

            <!-- TOPBAR -->
            <header class="topbar">

                <div class="topbar-left">
                    <button class="mobile-menu-btn" type="button" aria-label="Open menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <div>
                        <p class="topbar-subtitle">Hospital Portal</p>
                        <h5>Dashboard</h5>
                    </div>
                </div>

                <div class="topbar-right">
                    <div class="topbar-date">
                        <i class="fa-regular fa-calendar"></i>
                        <span><?php echo date("d M Y"); ?></span>
                    </div>

                    <div class="profile-avatar">
                        <i class="fa-solid fa-hospital"></i>
                    </div>
                </div>

            </header>


            <!-- PAGE CONTENT -->
            <section class="page-content">

                <!-- WELCOME SECTION -->
                <div class="welcome-section">

                    <div>
                        <span class="welcome-tag">
                            <i class="fa-solid fa-circle-check"></i>
                            Hospital Workspace
                        </span>

                        <h1>Welcome to your Dashboard</h1>

                        <p>
                            Manage your appointments and keep vaccination records up to date.
                        </p>
                    </div>

                    <div class="welcome-icon">
                        <i class="fa-solid fa-hospital-user"></i>
                    </div>

                </div>


                <!-- APPOINTMENTS SECTION -->
                <section class="appointments-section" id="appointments">

                    <div class="section-heading">

                        <div>
                            <h2>Appointments</h2>
                            <p>View and manage your hospital appointments.</p>
                        </div>

                        <div class="appointment-count">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span>
                                <?php echo mysqli_num_rows($result); ?> Appointments
                            </span>
                        </div>

                    </div>


                    <div class="table-card">

                        <div class="table-card-header">

                            <div>
                                <h5>All Appointments</h5>
                                <p>Manage appointment status from here.</p>
                            </div>

                            <div class="table-search">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" placeholder="Search appointments...">
                            </div>

                        </div>


                        <?php if (mysqli_num_rows($result) > 0) { ?>

                            <div class="table-responsive">

                                <table class="table appointments-table align-middle">

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

                                                <td>
                                                    <span class="appointment-id">
                                                        #<?php echo $row["appointment_id"]; ?>
                                                    </span>
                                                </td>

                                                <td>
                                                    <div class="child-info">
                                                        <div class="child-avatar">
                                                            <i class="fa-solid fa-child"></i>
                                                        </div>

                                                        <span>
                                                            <?php echo htmlspecialchars($row["child_name"]); ?>
                                                        </span>
                                                    </div>
                                                </td>

                                                <td>
                                                    <span class="vaccine-name">
                                                        <?php echo htmlspecialchars($row["vaccine_name"]); ?>
                                                    </span>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($row["appointment_date"]); ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($row["appointment_time"]); ?>
                                                </td>

                                                <td>
                                                    <span class="status-badge status-<?php echo strtolower(htmlspecialchars($row["appointment_status"])); ?>">
                                                        <span class="status-dot"></span>
                                                        <?php echo htmlspecialchars($row["appointment_status"]); ?>
                                                    </span>
                                                </td>

                                                <td>

                                                    <form action="" method="post" class="status-form">

                                                        <input
                                                            type="hidden"
                                                            name="appointment_id"
                                                            value="<?php echo $row["appointment_id"]; ?>"
                                                        >

                                                        <select name="appointment_status" class="form-select status-select" required>

                                                            <option value="Pending"
                                                                <?php if ($row["appointment_status"] === "Pending") echo "selected"; ?>>
                                                                Pending
                                                            </option>

                                                            <option value="Confirmed"
                                                                <?php if ($row["appointment_status"] === "Confirmed") echo "selected"; ?>>
                                                                Confirmed
                                                            </option>

                                                            <option value="Cancelled"
                                                                <?php if ($row["appointment_status"] === "Cancelled") echo "selected"; ?>>
                                                                Cancelled
                                                            </option>

                                                            <option value="Completed"
                                                                <?php if ($row["appointment_status"] === "Completed") echo "selected"; ?>>
                                                                Completed
                                                            </option>

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

                                <h5>No Appointments Found</h5>

                                <p>
                                    You don't have any appointments yet.
                                    New appointments will appear here.
                                </p>

                            </div>

                        <?php } ?>

                    </div>

                </section>

                <footer class="dashboard-footer">
                    <p>© <?php echo date("Y"); ?> VaxCare. Hospital Management System.</p>
                    <p>Healthcare Management Portal</p>
                </footer>

            </section>

        </main>

    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- MY JS -->
    <script src="hospital.js"></script>

</body>
</html>