<?php
include "parent_auth.php";
include "../config/db_connection.php";

$message = "";
$messageType = "";

if (isset($_GET["child_id"])) {

    $childId = $_GET["child_id"];
    $parentId = $_SESSION["parent_id"];

    // Verify that this child belongs to the logged-in parent
    $selectSql = "SELECT * FROM child WHERE child_id = ? AND parent_id = ?";

    $selectStmt = mysqli_prepare($connect, $selectSql);
    $selectStmt->bind_param("ii", $childId, $parentId);
    $selectStmt->execute();

    $result = $selectStmt->get_result();

    if (mysqli_num_rows($result) > 0) {

        $resultRow = mysqli_fetch_assoc($result);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            if (isset($_POST["child_name"], $_POST["date_of_birth"])) {

                $updatedName = trim($_POST["child_name"]);
                $updatedDate = $_POST["date_of_birth"];

                if (!empty($updatedName) && !empty($updatedDate)) {

                    try {

                        $updateSql = "UPDATE child
                                      SET child_name = ?, date_of_birth = ?
                                      WHERE child_id = ? AND parent_id = ?";

                        $updateStmt = mysqli_prepare($connect, $updateSql);

                        $updateStmt->bind_param(
                            "ssii",
                            $updatedName,
                            $updatedDate,
                            $childId,
                            $parentId
                        );

                        $updateResult = $updateStmt->execute();

                        if ($updateResult) {
                            header("Location: dashboard.php");
                            exit;
                        }

                        $updateStmt->close();

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

        // Show submitted values again if validation fails
        $displayName = $_POST["child_name"] ?? $resultRow["child_name"];
        $displayDate = $_POST["date_of_birth"] ?? $resultRow["date_of_birth"];

        $selectStmt->close();

    } else {

        $selectStmt->close();

        $childNotFound = true;

    }

} else {

    $childNotFound = true;

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Child | VaccineCare</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="CSS/parent-app.css">
</head>

<body>

<div class="app">

    <?php $activePage = "dashboard"; include __DIR__ . "/includes/sidebar.php"; ?>

    <div class="app-main">

        <header class="topbar">

            <div class="topbar-left">

                <button type="button" class="icon-btn menu-btn" data-menu-toggle
                        aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-title">
                    <p>Parent Portal</p>
                    <h2>Edit Child</h2>
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

            <?php if (isset($childNotFound)): ?>

                <div class="card not-found empty-state">

                    <div class="empty-icon">
                        <i class="fa-solid fa-child"></i>
                    </div>

                    <h3>Child Not Found</h3>

                    <p>This child does not exist or you do not have permission to edit this information.</p>

                    <a href="dashboard.php" class="btn btn-primary">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back to Dashboard
                    </a>

                </div>

            <?php else: ?>

                <div class="page-head">

                    <div>
                        <span class="eyebrow">Child Management</span>
                        <h1>Edit Child Information</h1>
                        <p>Update your child's personal information below.</p>
                    </div>

                    <a href="dashboard.php" class="btn btn-ghost">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back to Dashboard
                    </a>

                </div>


                <div class="card form-card">

                    <div class="form-card-head">

                        <div class="form-icon">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>

                        <div>
                            <h3>Update Information</h3>
                            <p>Make changes to your child's details.</p>
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
                                    value="<?php echo htmlspecialchars($displayName); ?>"
                                    placeholder="Enter child's full name"
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
                                    value="<?php echo htmlspecialchars($displayDate); ?>"
                                    required
                                >
                            </div>

                        </div>


                        <div class="form-note">
                            <i class="fa-solid fa-circle-info"></i>
                            Make sure your child's information is accurate.
                        </div>


                        <div class="form-actions">

                            <a href="dashboard.php" class="btn btn-ghost">Cancel</a>

                            <button type="submit" name="update_child" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Save Changes
                            </button>

                        </div>

                    </form>

                </div>

            <?php endif; ?>

        </main>

        <footer class="app-footer">
            &copy; 2026 VaccineCare. All rights reserved.
        </footer>

    </div>

</div>

<script src="JS/parent.js"></script>

</body>
</html>
