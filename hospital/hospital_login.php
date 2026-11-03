
<?php
session_start();
include "../config/db_connection.php";

$loginMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["hospital_email"], $_POST["hospital_password"])) {

        $email = trim($_POST["hospital_email"]);
        $enterPassword = $_POST["hospital_password"];

        if (!empty($email) && !empty($enterPassword)) {

            try {

                $selectSql = "SELECT * FROM hospital WHERE hospital_email = ?";
                $selectStmt = mysqli_prepare($connect, $selectSql);

                $selectStmt->bind_param("s", $email);
                $selectStmt->execute();

                $result = $selectStmt->get_result();

                // Check whether email exists
                if (mysqli_num_rows($result) > 0) {

                    $resultRow = mysqli_fetch_assoc($result);
                    $storedHash = $resultRow["password"];

                    if (password_verify($enterPassword, $storedHash)) {

                        $_SESSION['hospitalId'] = $resultRow['hospital_id'];
                        $_SESSION['hospitalName'] = $resultRow['hospital_name'];

                        header("Location: hospital_dashboard.php");
                        exit;

                    } else {

                        $loginMessage = "Incorrect password.";

                    }

                } else {

                    $loginMessage = "No hospital account found with this email.";

                }

                // CLOSE STATEMENT
                $selectStmt->close();

            } catch (mysqli_sql_exception $e) {

                $loginMessage = "Something went wrong. Please try again later.";

            }

        } else {

            $loginMessage = "Please enter your email and password.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hospital Login | VaccineCare</title>

    <link rel="stylesheet" href="CSS/login.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <main class="login-page">

        <section class="login-card">

            <div class="login-header">

                <div class="brand-icon">
                    <i class="fa-solid fa-hospital"></i>
                </div>

                <p class="brand-name">VaccineCare</p>

                <h1>Welcome Back</h1>

                <p class="subtitle">
                    Login to your hospital account to manage appointments.
                </p>

            </div>

            <?php if (!empty($loginMessage)): ?>
                <div class="alert-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo htmlspecialchars($loginMessage); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form">

                <div class="form-group">

                    <label for="hospital_email">Hospital Email</label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope"></i>

                        <input
                            type="email"
                            id="hospital_email"
                            name="hospital_email"
                            placeholder="Enter hospital email"
                            required
                            value="<?php echo htmlspecialchars($_POST['hospital_email'] ?? ''); ?>"
                        >
                    </div>

                </div>

                <div class="form-group">

                    <label for="hospital_password">Password</label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock"></i>

                        <input
                            type="password"
                            id="hospital_password"
                            name="hospital_password"
                            placeholder="Enter your password"
                            required
                        >
                    </div>

                </div>

                <button type="submit" class="login-btn">
                    Login to Dashboard
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </form>

            <p class="register-link">
                Don't have a hospital account?
                <a href="hospital_register.php">Register Hospital</a>
            </p>

            <p class="footer-text">
                © 2026 VaccineCare. All rights reserved.
            </p>

        </section>

    </main>

</body>
</html>
