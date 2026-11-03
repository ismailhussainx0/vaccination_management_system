
<?php

session_start();
include "../config/db_connection.php";

$loginMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["parent_email"], $_POST["parent_password"])) {

        $email = trim($_POST["parent_email"]);
        $enterPassword = $_POST["parent_password"];

        if (!empty($email) && !empty($enterPassword)) {

            try {

                // Find parent by email
                $selectSql = "SELECT * FROM parents WHERE email = ?";

                $selectStmt = mysqli_prepare(
                    $connect,
                    $selectSql
                );

                $selectStmt->bind_param("s", $email);
                $selectStmt->execute();

                $result = $selectStmt->get_result();

                // Check whether email exists
                if (mysqli_num_rows($result) > 0) {

                    $resultRow = mysqli_fetch_assoc($result);

                    $storedHash = $resultRow["password"];

                    // Verify entered password
                    if (password_verify($enterPassword, $storedHash)) {

                        // Store data in session
                        session_regenerate_id(true);

                        $_SESSION["parent_id"] = $resultRow["parent_id"];
                        $_SESSION["parent_name"] = $resultRow["name"];

                        header("Location: dashboard.php");
                        exit;

                    } else {

                        $loginMessage = "Invalid password.";

                    }

                } else {

                    $loginMessage = "No parent account found with this email.";

                }

                $selectStmt->close();

            } catch (mysqli_sql_exception $error) {

                $loginMessage = "Something went wrong. Please try again later.";

            }

        } else {

            $loginMessage = "Please enter both email and password.";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Parent Login | VaccineCare</title>

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
                    <i class="fa-solid fa-user-shield"></i>
                </div>

                <p class="brand-name">VaccineCare</p>

                <h1>Welcome Back</h1>

                <p class="subtitle">
                    Login to your parent account and manage your children's vaccination appointments.
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

                    <label for="parent_email">Email Address</label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope"></i>

                        <input
                            type="email"
                            id="parent_email"
                            name="parent_email"
                            placeholder="Enter your email"
                            required
                            value="<?php echo htmlspecialchars($_POST['parent_email'] ?? ''); ?>"
                        >
                    </div>

                </div>

                <div class="form-group">

                    <label for="parent_password">Password</label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock"></i>

                        <input
                            type="password"
                            id="parent_password"
                            name="parent_password"
                            placeholder="Enter your password"
                            required
                        >
                    </div>

                </div>

                <button type="submit" class="login-btn">
                    Login to Account
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </form>

            <p class="register-link">
                Don't have an account?
                <a href="parent_register.php">Register here</a>
            </p>

            <p class="footer-text">
                © 2026 VaccineCare. All rights reserved.
            </p>

        </section>

    </main>

</body>

</html>
