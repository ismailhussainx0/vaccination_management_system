
<?php

include "../config/db_connection.php";
include "admin_auth.php";

$error = "";

if (isset($_POST["add_hospital"])) {

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
        empty($hospitalPhone) ||
        empty($hospitalPassword)
    ) {
        $error = "All fields are required.";
    } elseif (!filter_var($hospitalEmail, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {

        // CHECK DUPLICATE EMAIL
        $checkSql = "SELECT hospital_id FROM hospital WHERE hospital_email = ?";

        $checkStmt = mysqli_prepare($connect, $checkSql);
        $checkStmt->bind_param("s", $hospitalEmail);
        $checkStmt->execute();

        $checkResult = $checkStmt->get_result();

        if (mysqli_num_rows($checkResult) > 0) {

            $error = "This email is already registered.";

        } else {

            // HASH PASSWORD
            $hashedPassword = password_hash($hospitalPassword, PASSWORD_DEFAULT);

            // INSERT HOSPITAL
            $insertSql = "INSERT INTO hospital
                          (hospital_name, hospital_address, hospital_email, hospital_phone, password)
                          VALUES (?, ?, ?, ?, ?)";

            $insertStmt = mysqli_prepare($connect, $insertSql);

            $insertStmt->bind_param(
                "sssss",
                $hospitalName,
                $hospitalAddress,
                $hospitalEmail,
                $hospitalPhone,
                $hashedPassword
            );

            if ($insertStmt->execute()) {

                $insertStmt->close();
                $checkStmt->close();

                header("Location: manage_hospitals.php");
                exit;

            } else {

                $error = "Unable to add hospital. Please try again.";
            }

            $insertStmt->close();
        }

        $checkStmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Hospital | Admin Panel</title>

    <link rel="stylesheet" href="CSS/add-hospital.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="dashboard-container">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <div class="sidebar-logo">
                <i class="fa-solid fa-shield-heart"></i>
                <h2>VaxCare Admin</h2>
            </div>

            <nav class="sidebar-nav">

                <a href="dashboard.php">
                    <i class="fa-solid fa-chart-line"></i>
                    Dashboard
                </a>

                <a href="manage_hospitals.php" class="active">
                    <i class="fa-solid fa-hospital"></i>
                    Manage Hospitals
                </a>

                <a href="admin_logout.php" class="logout-link">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </a>

            </nav>

        </aside>


        <!-- MAIN CONTENT -->
        <main class="main-content">

            <header class="topbar">
                <div>
                    <h1>Add Hospital</h1>
                    <p>Register a new hospital in the system.</p>
                </div>

                <div class="admin-info">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>
                        <?php echo htmlspecialchars($_SESSION["adminName"]); ?>
                    </span>
                </div>
            </header>


            <!-- FORM SECTION -->
            <section class="content-section">

                <div class="section-header">
                    <div>
                        <h2>Hospital Details</h2>
                        <p>Enter the hospital information below.</p>
                    </div>
                </div>


                <?php if (!empty($error)) { ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php } ?>


                <form action="" method="POST" class="hospital-form">

                    <div class="form-group">
                        <label for="hospital_name">Hospital Name</label>
                        <input
                            type="text"
                            id="hospital_name"
                            name="hospital_name"
                            value="<?php echo htmlspecialchars($_POST["hospital_name"] ?? ""); ?>"
                            required
                        >
                    </div>


                    <div class="form-group">
                        <label for="hospital_address">Hospital Address</label>
                        <textarea
                            id="hospital_address"
                            name="hospital_address"
                            rows="3"
                            required
                        ><?php echo htmlspecialchars($_POST["hospital_address"] ?? ""); ?></textarea>
                    </div>


                    <div class="form-group">
                        <label for="hospital_email">Hospital Email</label>
                        <input
                            type="email"
                            id="hospital_email"
                            name="hospital_email"
                            value="<?php echo htmlspecialchars($_POST["hospital_email"] ?? ""); ?>"
                            required
                        >
                    </div>


                    <div class="form-group">
                        <label for="hospital_phone">Hospital Phone</label>
                        <input
                            type="text"
                            id="hospital_phone"
                            name="hospital_phone"
                            value="<?php echo htmlspecialchars($_POST["hospital_phone"] ?? ""); ?>"
                            required
                        >
                    </div>


                    <div class="form-group">
                        <label for="hospital_password">Hospital Password</label>
                        <input
                            type="password"
                            id="hospital_password"
                            name="hospital_password"
                            required
                        >
                    </div>


                    <div class="form-actions">

                        <a href="manage_hospitals.php" class="cancel-btn">
                            Cancel
                        </a>

                        <button type="submit" name="add_hospital" class="submit-btn">
                            <i class="fa-solid fa-plus"></i>
                            Add Hospital
                        </button>

                    </div>

                </form>

            </section>

        </main>

    </div>

</body>
</html>
