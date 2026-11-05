<?php
include "parent_auth.php";
include "../config/db_connection.php";

$parentId = $_SESSION["parent_id"];

$message = "";
$messageType = "";
$bookingSuccess = false;

// SELECT CHILD DATA
$childSql = "SELECT child_id, child_name FROM child WHERE parent_id = ?";
$childStmt = mysqli_prepare($connect, $childSql);
$childStmt->bind_param("i", $parentId);
$childStmt->execute();
$childResult = $childStmt->get_result();

// SELECT HOSPITAL DATA
$hospitalSql = "SELECT hospital_id, hospital_name FROM hospital";
$hospitalResult = mysqli_query($connect, $hospitalSql);

// SELECT VACCINE DATA
$vaccineSql = "SELECT vaccine_id, vaccine_name FROM vaccines";
$vaccineResult = mysqli_query($connect, $vaccineSql);


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        isset(
            $_POST["child_id"],
            $_POST["hospital_id"],
            $_POST["vaccine_id"],
            $_POST["appointment_date"],
            $_POST["appointment_time"]
        )
    ) {

        $childId = $_POST["child_id"];
        $hospitalId = $_POST["hospital_id"];
        $vaccineId = $_POST["vaccine_id"];
        $appointmentDate = $_POST["appointment_date"];
        $appointmentTime = $_POST["appointment_time"];

        if (
            !empty($childId) &&
            !empty($hospitalId) &&
            !empty($vaccineId) &&
            !empty($appointmentDate) &&
            !empty($appointmentTime)
        ) {

            // CHECK CHILD BELONGS TO LOGGED-IN PARENT
            $checkSql = "SELECT child_id FROM child WHERE child_id = ? AND parent_id = ?";
            $checkStmt = mysqli_prepare($connect, $checkSql);
            $checkStmt->bind_param("ii", $childId, $parentId);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            // CHECK HOSPITAL
            $checkHospital = "SELECT hospital_id FROM hospital WHERE hospital_id = ?";
            $checkHospitalStmt = mysqli_prepare($connect, $checkHospital);
            $checkHospitalStmt->bind_param("i", $hospitalId);
            $checkHospitalStmt->execute();
            $checkHospitalResult = $checkHospitalStmt->get_result();

            // CHECK VACCINE
            $checkVaccine = "SELECT vaccine_id FROM vaccines WHERE vaccine_id = ?";
            $checkVaccineStmt = mysqli_prepare($connect, $checkVaccine);
            $checkVaccineStmt->bind_param("i", $vaccineId);
            $checkVaccineStmt->execute();
            $checkVaccineResult = $checkVaccineStmt->get_result();

            if (
                mysqli_num_rows($checkResult) > 0 &&
                mysqli_num_rows($checkHospitalResult) > 0 &&
                mysqli_num_rows($checkVaccineResult) > 0
            ) {

                // Prevent booking an appointment in the past
                if ($appointmentDate < date("Y-m-d")) {

                    $message = "Please select today or a future date.";
                    $messageType = "error";

                } else {

                    try {

                        // INSERT APPOINTMENT
                        $insertSql = "INSERT INTO appointments
                        (child_id, hospital_id, vaccine_id, appointment_date, appointment_time)
                        VALUES (?, ?, ?, ?, ?)";

                        $insertStmt = mysqli_prepare($connect, $insertSql);

                        $insertStmt->bind_param(
                            "iiiss",
                            $childId,
                            $hospitalId,
                            $vaccineId,
                            $appointmentDate,
                            $appointmentTime
                        );

                        $result = $insertStmt->execute();

                        if ($result) {
                            $message = "Your appointment has been booked successfully!";
                            $messageType = "success";
                            $bookingSuccess = true;
                        }

                        $insertStmt->close();

                    } catch (mysqli_sql_exception $e) {

                        $message = "Unable to book appointment. Please try again.";
                        $messageType = "error";

                    }

                }

            } else {

                $message = "Invalid child, hospital, or vaccine selection.";
                $messageType = "error";

            }

            $checkStmt->close();
            $checkHospitalStmt->close();
            $checkVaccineStmt->close();

        } else {

            $message = "Please fill in all fields.";
            $messageType = "error";

        }

    } else {

        $message = "Invalid form submission.";
        $messageType = "error";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Book Appointment | VaccineCare</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="CSS/parent-app.css">
</head>

<body>

<div class="app">

    <?php $activePage = "book"; include __DIR__ . "/includes/sidebar.php"; ?>

    <div class="app-main">

        <header class="topbar">

            <div class="topbar-left">

                <button type="button" class="icon-btn menu-btn" data-menu-toggle
                        aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-title">
                    <p>Parent Portal</p>
                    <h2>Book Appointment</h2>
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
                    <span class="eyebrow">Appointment Management</span>
                    <h1>Book a Vaccination Appointment</h1>
                    <p>Select your child, hospital, vaccine and preferred schedule.</p>
                </div>

                <a href="my_appointments.php" class="btn btn-ghost">
                    <i class="fa-solid fa-calendar-check"></i>
                    My Appointments
                </a>

            </div>


            <div class="card form-card">

                <div class="form-card-head">

                    <div class="form-icon">
                        <i class="fa-solid fa-calendar-plus"></i>
                    </div>

                    <div>
                        <h3>Appointment Details</h3>
                        <p>Complete the form to request an appointment.</p>
                    </div>

                </div>


                <?php if (!empty($message)): ?>

                    <div class="alert <?php echo $messageType; ?>">

                        <?php if ($messageType === "success"): ?>
                            <i class="fa-solid fa-circle-check"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-circle-exclamation"></i>
                        <?php endif; ?>

                        <?php echo htmlspecialchars($message); ?>

                    </div>

                <?php endif; ?>


                <?php if (mysqli_num_rows($childResult) > 0 && mysqli_num_rows($hospitalResult) > 0 && mysqli_num_rows($vaccineResult) > 0): ?>

                    <form action="" method="POST" class="form-body">

                        <div class="form-group">

                            <label for="child_id">Select Child</label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-child"></i>

                                <select name="child_id" id="child_id" required>

                                    <option value="">Choose your child</option>

                                    <?php while ($row = mysqli_fetch_assoc($childResult)): ?>

                                        <option
                                            value="<?php echo $row["child_id"]; ?>"
                                            <?php echo (isset($_POST["child_id"]) && $_POST["child_id"] == $row["child_id"]) ? "selected" : ""; ?>
                                        >
                                            <?php echo htmlspecialchars($row["child_name"]); ?>
                                        </option>

                                    <?php endwhile; ?>

                                </select>
                            </div>

                        </div>

                        <div class="form-group">

                            <label for="hospital_id">Select Hospital</label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-hospital"></i>

                                <select name="hospital_id" id="hospital_id" required>

                                    <option value="">Choose a hospital</option>

                                    <?php while ($row = mysqli_fetch_assoc($hospitalResult)): ?>

                                        <option
                                            value="<?php echo $row["hospital_id"]; ?>"
                                            <?php echo (isset($_POST["hospital_id"]) && $_POST["hospital_id"] == $row["hospital_id"]) ? "selected" : ""; ?>
                                        >
                                            <?php echo htmlspecialchars($row["hospital_name"]); ?>
                                        </option>

                                    <?php endwhile; ?>

                                </select>
                            </div>

                        </div>

                        <div class="form-group">

                            <label for="vaccine_id">Select Vaccine</label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-syringe"></i>

                                <select name="vaccine_id" id="vaccine_id" required>

                                    <option value="">Choose a vaccine</option>

                                    <?php while ($row = mysqli_fetch_assoc($vaccineResult)): ?>

                                        <option
                                            value="<?php echo $row["vaccine_id"]; ?>"
                                            <?php echo (isset($_POST["vaccine_id"]) && $_POST["vaccine_id"] == $row["vaccine_id"]) ? "selected" : ""; ?>
                                        >
                                            <?php echo htmlspecialchars($row["vaccine_name"]); ?>
                                        </option>

                                    <?php endwhile; ?>

                                </select>
                            </div>

                        </div>


                        <div class="date-time-grid">

                            <div class="form-group">

                                <label for="appointment_date">Appointment Date</label>

                                <div class="input-wrapper">
                                    <i class="fa-solid fa-calendar-days"></i>

                                    <input
                                        type="date"
                                        id="appointment_date"
                                        name="appointment_date"
                                        min="<?php echo date('Y-m-d'); ?>"
                                        value="<?php echo htmlspecialchars($_POST["appointment_date"] ?? ""); ?>"
                                        required
                                    >
                                </div>

                            </div>


                            <div class="form-group">

                                <label for="appointment_time">Appointment Time</label>

                                <div class="input-wrapper">
                                    <i class="fa-solid fa-clock"></i>

                                    <input
                                        type="time"
                                        id="appointment_time"
                                        name="appointment_time"
                                        value="<?php echo htmlspecialchars($_POST["appointment_time"] ?? ""); ?>"
                                        required
                                    >
                                </div>

                            </div>

                        </div>


                        <div class="form-note">
                            <i class="fa-solid fa-circle-info"></i>
                            Your appointment request will initially have Pending status.
                        </div>


                        <div class="form-actions">

                            <a href="dashboard.php" class="btn btn-ghost">Cancel</a>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-calendar-check"></i>
                                Book Appointment
                            </button>

                        </div>

                    </form>

                <?php else: ?>

                    <div class="empty-state">

                        <div class="empty-icon">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>

                        <h3>Booking Not Available</h3>

                        <p>
                            <?php if (mysqli_num_rows($childResult) === 0): ?>
                                Please add a child to your account before booking an appointment.
                            <?php else: ?>
                                Hospitals or vaccines are currently unavailable. Please try again later.
                            <?php endif; ?>
                        </p>

                        <?php if (mysqli_num_rows($childResult) === 0): ?>
                            <a href="add_child.php" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i>
                                Add Child
                            </a>
                        <?php else: ?>
                            <a href="dashboard.php" class="btn btn-ghost">Back to Dashboard</a>
                        <?php endif; ?>

                    </div>

                <?php endif; ?>

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
