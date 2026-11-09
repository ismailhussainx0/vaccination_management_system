<?php

include "../config/db_connection.php";
include "admin_auth.php";

$error = "";

// GET HOSPITAL ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: manage_hospitals.php");
    exit;
}

$hospitalId = (int) $_GET["id"];


// FETCH EXISTING HOSPITAL
$selectSql = "SELECT hospital_id, hospital_name, hospital_address,
              hospital_email, hospital_phone
              FROM hospital
              WHERE hospital_id = ?";

$selectStmt = mysqli_prepare($connect, $selectSql);
$selectStmt->bind_param("i", $hospitalId);
$selectStmt->execute();

$selectResult = $selectStmt->get_result();

if (mysqli_num_rows($selectResult) === 0) {
    $selectStmt->close();
    header("Location: manage_hospitals.php");
    exit;
}

$hospital = mysqli_fetch_assoc($selectResult);
$selectStmt->close();


// UPDATE HOSPITAL
if (isset($_POST["update_hospital"])) {

    // GET FORM DATA
    $hospitalName = trim($_POST["hospital_name"]);
    $hospitalAddress = trim($_POST["hospital_address"]);
    $hospitalEmail = trim($_POST["hospital_email"]);
    $hospitalPhone = trim($_POST["hospital_phone"]);
    $hospitalPassword = $_POST["hospital_password"];


    // VALIDATION
    if (
        empty($hospitalName) ||
        empty($hospitalAddress) ||
        empty($hospitalEmail) ||
        empty($hospitalPhone)
    ) {

        $error = "All fields except password are required.";

    } elseif (!filter_var($hospitalEmail, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } else {

        // CHECK DUPLICATE EMAIL
        $checkSql = "SELECT hospital_id
                     FROM hospital
                     WHERE hospital_email = ?
                     AND hospital_id != ?";

        $checkStmt = mysqli_prepare($connect, $checkSql);
        $checkStmt->bind_param("si", $hospitalEmail, $hospitalId);
        $checkStmt->execute();

        $checkResult = $checkStmt->get_result();

        if (mysqli_num_rows($checkResult) > 0) {

            $error = "This email is already registered.";

        } else {

            // UPDATE WITH OR WITHOUT PASSWORD
            if (!empty($hospitalPassword)) {

                $hashedPassword = password_hash(
                    $hospitalPassword,
                    PASSWORD_DEFAULT
                );

                $updateSql = "UPDATE hospital
                              SET hospital_name = ?,
                                  hospital_address = ?,
                                  hospital_email = ?,
                                  hospital_phone = ?,
                                  password = ?
                              WHERE hospital_id = ?";

                $updateStmt = mysqli_prepare($connect, $updateSql);

                $updateStmt->bind_param(
                    "sssssi",
                    $hospitalName,
                    $hospitalAddress,
                    $hospitalEmail,
                    $hospitalPhone,
                    $hashedPassword,
                    $hospitalId
                );

            } else {

                $updateSql = "UPDATE hospital
                              SET hospital_name = ?,
                                  hospital_address = ?,
                                  hospital_email = ?,
                                  hospital_phone = ?
                              WHERE hospital_id = ?";

                $updateStmt = mysqli_prepare($connect, $updateSql);

                $updateStmt->bind_param(
                    "ssssi",
                    $hospitalName,
                    $hospitalAddress,
                    $hospitalEmail,
                    $hospitalPhone,
                    $hospitalId
                );
            }


            if ($updateStmt->execute()) {

                $updateStmt->close();
                $checkStmt->close();

                header("Location: manage_hospitals.php");
                exit;

            } else {

                $error = "Unable to update hospital. Please try again.";
            }

            $updateStmt->close();
        }

        $checkStmt->close();
    }

    // KEEP ENTERED VALUES IF VALIDATION FAILS
    $hospital["hospital_name"] = $hospitalName;
    $hospital["hospital_address"] = $hospitalAddress;
    $hospital["hospital_email"] = $hospitalEmail;
    $hospital["hospital_phone"] = $hospitalPhone;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Hospital | VaccineCare Admin</title>

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
                    <h2>Edit Hospital</h2>
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
                    <span class="eyebrow">Hospital Management</span>
                    <h1>Edit Hospital</h1>
                    <p>Update hospital information.</p>
                </div>

                <a href="manage_hospitals.php" class="btn btn-ghost">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Hospitals
                </a>

            </div>


            <div class="card form-card wide">

                <div class="form-card-head">

                    <div class="form-icon">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>

                    <div>
                        <h3>Hospital Details</h3>
                        <p>Update the information below.</p>
                    </div>

                </div>


                <?php if (!empty($error)) { ?>

                    <div class="alert error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>

                <?php } ?>


                <form action="" method="POST" class="form-body">

                    <div class="form-group">

                        <label for="hospital_name">Hospital Name</label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-hospital"></i>

                            <input
                                type="text"
                                id="hospital_name"
                                name="hospital_name"
                                value="<?php echo htmlspecialchars($hospital["hospital_name"]); ?>"
                                required
                            >
                        </div>

                    </div>


                    <div class="form-group">

                        <label for="hospital_address">Hospital Address</label>

                        <div class="input-wrapper textarea-wrapper">
                            <i class="fa-solid fa-location-dot"></i>

                            <textarea
                                id="hospital_address"
                                name="hospital_address"
                                rows="3"
                                required
                            ><?php echo htmlspecialchars($hospital["hospital_address"]); ?></textarea>
                        </div>

                    </div>


                    <div class="form-group">

                        <label for="hospital_email">Hospital Email</label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-envelope"></i>

                            <input
                                type="email"
                                id="hospital_email"
                                name="hospital_email"
                                value="<?php echo htmlspecialchars($hospital["hospital_email"]); ?>"
                                required
                            >
                        </div>

                    </div>


                    <div class="form-group">

                        <label for="hospital_phone">Hospital Phone</label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-phone"></i>

                            <input
                                type="text"
                                id="hospital_phone"
                                name="hospital_phone"
                                value="<?php echo htmlspecialchars($hospital["hospital_phone"]); ?>"
                                required
                            >
                        </div>

                    </div>


                    <div class="form-group">

                        <label for="hospital_password">New Password (Optional)</label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock"></i>

                            <input
                                type="password"
                                id="hospital_password"
                                name="hospital_password"
                                placeholder="Leave empty to keep current password"
                            >
                        </div>

                    </div>


                    <div class="form-actions">

                        <a href="manage_hospitals.php" class="btn btn-ghost">Cancel</a>

                        <button type="submit" name="update_hospital" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Update Hospital
                        </button>

                    </div>

                </form>

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
