<?php
include "../config/db_connection.php";
include "parent_auth.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["child_name"], $_POST["date_of_birth"])) {

        $childName = trim($_POST["child_name"]);
        $dateOfBirth = $_POST["date_of_birth"];
        $parentId = $_SESSION["parent_id"];

        if (!empty($childName) && !empty($dateOfBirth)) {

            try {

                $insertQuery = "INSERT INTO child (child_name, date_of_birth, parent_id) VALUES (?, ?, ?)";

                $insertStmt = mysqli_prepare($connect, $insertQuery);

                $insertStmt->bind_param("ssi", $childName, $dateOfBirth, $parentId);

                $result = $insertStmt->execute();

                if ($result) {
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $message = "Child data could not be added.";
                    $messageType = "error";
                }

                $insertStmt->close();

            } catch (mysqli_sql_exception $error) {

                $message = "Something went wrong. Please try again later.";
                $messageType = "error";

            }

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

    <title>Add Child | VaccineCare</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="CSS/parent-app.css">
</head>

<body>

<div class="app">

    <?php $activePage = "add_child"; include __DIR__ . "/includes/sidebar.php"; ?>

    <div class="app-main">

        <header class="topbar">

            <div class="topbar-left">

                <button type="button" class="icon-btn menu-btn" data-menu-toggle
                        aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-title">
                    <p>Parent Portal</p>
                    <h2>Add Child</h2>
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
                    <span class="eyebrow">Child Management</span>
                    <h1>Add a New Child</h1>
                    <p>Enter your child's details to add them to your account.</p>
                </div>

                <a href="dashboard.php" class="btn btn-ghost">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Dashboard
                </a>

            </div>


            <div class="card form-card">

                <div class="form-card-head">

                    <div class="form-icon">
                        <i class="fa-solid fa-child-reaching"></i>
                    </div>

                    <div>
                        <h3>Child Information</h3>
                        <p>Please provide accurate information.</p>
                    </div>

                </div>


                <?php if (!empty($message)): ?>

                    <div class="alert <?php echo $messageType; ?>">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?php echo htmlspecialchars($message); ?>
                    </div>

                <?php endif; ?>


                <form action="" method="POST" class="form-body">

                    <div class="form-group">

                        <label for="child_name">Child Name</label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-user"></i>

                            <input
                                type="text"
                                id="child_name"
                                name="child_name"
                                placeholder="Enter child's full name"
                                value="<?php echo htmlspecialchars($_POST["child_name"] ?? ""); ?>"
                                required
                            >
                        </div>

                    </div>


                    <div class="form-group">

                        <label for="date_of_birth">Date of Birth</label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-calendar-days"></i>

                            <input
                                type="date"
                                id="date_of_birth"
                                name="date_of_birth"
                                max="<?php echo date('Y-m-d'); ?>"
                                value="<?php echo htmlspecialchars($_POST["date_of_birth"] ?? ""); ?>"
                                required
                            >
                        </div>

                    </div>


                    <div class="form-note">
                        <i class="fa-solid fa-circle-info"></i>
                        Make sure the child's date of birth is correct.
                    </div>


                    <div class="form-actions">

                        <a href="dashboard.php" class="btn btn-ghost">Cancel</a>

                        <button type="submit" name="add_child" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i>
                            Add Child
                        </button>

                    </div>

                </form>

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
