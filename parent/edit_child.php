
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

    <link rel="stylesheet" href="CSS/edit-child.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<div class="dashboard-layout">

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="brand">
            <div class="brand-icon">
                <i class="fa-solid fa-heart-pulse"></i>
            </div>

            <span>VaccineCare</span>
        </div>

        <div class="sidebar-section">

            <p class="sidebar-heading">MENU</p>

            <nav class="sidebar-nav">

                <a href="dashboard.php" class="nav-link active">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>

                <a href="add_child.php" class="nav-link">
                    <i class="fa-solid fa-child"></i>
                    <span>Add Child</span>
                </a>

                
                <a href="book_appointment.php" class="nav-link">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Book Appointment</span>
                </a>

                                
                <a href="logout.php" class="nav-link logout-link"
                onclick="return confirm('Are you sure you want to logout?');">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>


            </nav>

        </div>

        <div class="sidebar-bottom">

            <div class="sidebar-help">
                <i class="fa-solid fa-circle-question"></i>

                <div>
                    <strong>Need Help?</strong>
                    <p>Contact support</p>
                </div>
            </div>

        </div>

    </aside>


    <!-- Main Content -->
    <main class="main-content">

        <!-- Topbar -->
        <header class="topbar">

            <div>
                <p class="topbar-label">Parent Portal</p>
                <h2>Edit Child</h2>
            </div>

            <div class="profile">

                <div class="profile-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>

                <div class="profile-info">
                    <strong>
                        <?php echo htmlspecialchars($_SESSION["parent_name"]); ?>
                    </strong>

                    <span>Parent Account</span>
                </div>

            </div>

        </header>


        <!-- Page Content -->
        <section class="page-content">

            <?php if (isset($childNotFound)): ?>

                <div class="not-found-card">

                    <div class="not-found-icon">
                        <i class="fa-solid fa-child"></i>
                    </div>

                    <h2>Child Not Found</h2>

                    <p>
                        This child does not exist or you do not have permission to edit this information.
                    </p>

                    <a href="dashboard.php" class="back-button">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back to Dashboard
                    </a>

                </div>

            <?php else: ?>

                <div class="page-heading">

                    <div>
                        <span class="eyebrow">CHILD MANAGEMENT</span>

                        <h1>Edit Child Information</h1>

                        <p>Update your child's personal information below.</p>
                    </div>

                    <a href="dashboard.php" class="back-button">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back to Dashboard
                    </a>

                </div>


                <!-- Form Card -->
                <div class="form-card">

                    <div class="form-card-header">

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


                    <form action="" method="POST">

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

                            <a href="dashboard.php" class="cancel-button">
                                Cancel
                            </a>

                            <button type="submit" name="update_child" class="submit-button">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Save Changes
                            </button>

                        </div>

                    </form>

                </div>

            <?php endif; ?>

        </section>

    </main>

</div>

</body>
</html>
