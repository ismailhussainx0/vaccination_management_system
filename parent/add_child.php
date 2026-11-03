
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

    <link rel="stylesheet" href="CSS/add_child.css">

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

                <a href="dashboard.php" class="nav-link">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>

                <a href="add_child.php" class="nav-link active">
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
                <h2>Add Child</h2>
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

            <div class="page-heading">
                <div>
                    <span class="eyebrow">CHILD MANAGEMENT</span>
                    <h1>Add a New Child</h1>
                    <p>Enter your child's details to add them to your account.</p>
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


                <form action="" method="POST">

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

                        <a href="dashboard.php" class="cancel-button">
                            Cancel
                        </a>

                        <button type="submit" name="add_child" class="submit-button">
                            <i class="fa-solid fa-plus"></i>
                            Add Child
                        </button>

                    </div>

                </form>

            </div>

        </section>

    </main>

</div>

</body>
</html>
