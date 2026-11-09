<?php
include "../config/db_connection.php";
include "admin_auth.php";

$vaccineName = "";
$errorMessage = "";

if (isset($_POST["add_vaccine"])) {
    $vaccineName = trim($_POST["vaccine_name"] ?? "");

    if ($vaccineName === "") {
        $errorMessage = "Please enter the vaccine name.";
    } else {
        $checkSql = "SELECT vaccine_id FROM vaccines WHERE vaccine_name = ?";
        $checkStmt = mysqli_prepare($connect, $checkSql);
        if (!$checkStmt) {
            die("Error preparing duplicate check: " . mysqli_error($connect));
        }
        $checkStmt->bind_param("s", $vaccineName);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $exists = mysqli_num_rows($checkResult) > 0;
        $checkStmt->close();

        if ($exists) {
            $errorMessage = "This vaccine already exists.";
        } else {
            $insertSql = "INSERT INTO vaccines (vaccine_name) VALUES (?)";
            $insertStmt = mysqli_prepare($connect, $insertSql);
            if (!$insertStmt) {
                die("Error preparing insert query: " . mysqli_error($connect));
            }
            $insertStmt->bind_param("s", $vaccineName);
            if ($insertStmt->execute()) {
                $insertStmt->close();
                header("Location: manage_vaccines.php?message=added");
                exit;
            }
            $errorMessage = "Unable to add vaccine. Please try again.";
            $insertStmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Vaccine | VaccineCare Admin</title>

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
                    <h2>Add Vaccine</h2>
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
                    <h1>Add Vaccine</h1>
                    <p>Enter the name of the new vaccine.</p>
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
                        <p>Enter the vaccine name below.</p>
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
                                placeholder="e.g. Polio Vaccine"
                                value="<?php echo htmlspecialchars($vaccineName); ?>"
                                required
                            >
                        </div>

                    </div>


                    <div class="form-actions">

                        <a href="manage_vaccines.php" class="btn btn-ghost">Cancel</a>

                        <button type="submit" name="add_vaccine" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i>
                            Add Vaccine
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
