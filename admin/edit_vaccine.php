<?php
include "../config/db_connection.php";
include "admin_auth.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: manage_vaccines.php");
    exit;
}
$vaccineId = (int) $_GET["id"];
$errorMessage = "";

$fetchSql = "SELECT vaccine_id, vaccine_name FROM vaccines WHERE vaccine_id = ?";
$fetchStmt = mysqli_prepare($connect, $fetchSql);
if (!$fetchStmt) {
    die("Error preparing fetch query: " . mysqli_error($connect));
}
$fetchStmt->bind_param("i", $vaccineId);
$fetchStmt->execute();
$fetchResult = $fetchStmt->get_result();
$vaccine = mysqli_fetch_assoc($fetchResult);
$fetchStmt->close();

if (!$vaccine) {
    header("Location: manage_vaccines.php");
    exit;
}

if (isset($_POST["update_vaccine"])) {
    $vaccineName = trim($_POST["vaccine_name"] ?? "");

    if ($vaccineName === "") {
        $errorMessage = "Please enter the vaccine name.";
    } else {
        $checkSql = "SELECT vaccine_id FROM vaccines WHERE vaccine_name = ? AND vaccine_id != ?";
        $checkStmt = mysqli_prepare($connect, $checkSql);
        if (!$checkStmt) {
            die("Error preparing duplicate check: " . mysqli_error($connect));
        }
        $checkStmt->bind_param("si", $vaccineName, $vaccineId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $duplicate = mysqli_num_rows($checkResult) > 0;
        $checkStmt->close();

        if ($duplicate) {
            $errorMessage = "Another vaccine already uses this name.";
        } else {
            $updateSql = "UPDATE vaccines SET vaccine_name = ? WHERE vaccine_id = ?";
            $updateStmt = mysqli_prepare($connect, $updateSql);
            if (!$updateStmt) {
                die("Error preparing update query: " . mysqli_error($connect));
            }
            $updateStmt->bind_param("si", $vaccineName, $vaccineId);
            if ($updateStmt->execute()) {
                $updateStmt->close();
                header("Location: manage_vaccines.php?message=updated");
                exit;
            }
            $errorMessage = "Unable to update vaccine. Please try again.";
            $updateStmt->close();
        }
    }
    $vaccine["vaccine_name"] = $vaccineName;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Vaccine | VaccineCare Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="CSS/admin-app.css">
</head>

<body>

<div class="app">

    <?php $activePage = "vaccines"; $adminBase = ""; include __DIR__ . "/includes/sidebar.php"; ?>

    <div class="app-main">

        <header class="topbar">

            <div class="topbar-left">

                <button type="button" class="icon-btn menu-btn" data-menu-toggle
                        aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-title">
                    <p>Admin Panel</p>
                    <h2>Edit Vaccine</h2>
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
                    <span class="eyebrow">Vaccine Management</span>
                    <h1>Edit Vaccine</h1>
                    <p>Update the vaccine name.</p>
                </div>

                <a href="manage_vaccines.php" class="btn btn-ghost">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Vaccines
                </a>

            </div>


            <div class="card form-card">

                <div class="form-card-head">

                    <div class="form-icon">
                        <i class="fa-solid fa-syringe"></i>
                    </div>

                    <div>
                        <h3>Vaccine Information</h3>
                        <p>Make changes to the vaccine name.</p>
                    </div>

                </div>


                <?php if ($errorMessage !== ""): ?>
                    <div class="alert error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                <?php endif; ?>


                <form method="POST" action="" class="form-body">

                    <div class="form-group">

                        <label for="vaccine_name">Vaccine Name</label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-syringe"></i>

                            <input
                                type="text"
                                id="vaccine_name"
                                name="vaccine_name"
                                maxlength="150"
                                value="<?php echo htmlspecialchars($vaccine["vaccine_name"]); ?>"
                                required
                            >
                        </div>

                    </div>


                    <div class="form-actions">

                        <a href="manage_vaccines.php" class="btn btn-ghost">Cancel</a>

                        <button type="submit" name="update_vaccine" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Update Vaccine
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
