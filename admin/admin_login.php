<?php

session_start();

include "../config/db_connection.php";

$error = "";

if (isset($_SESSION["adminId"])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["admin_email"]);
    $password = $_POST["admin_password"];

    $sql = "SELECT * FROM admins WHERE admin_email = ?";

    $stmt = mysqli_prepare($connect, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {

        $admin = mysqli_fetch_assoc($result);

        if (password_verify($password, $admin["password"])) {

            $_SESSION["adminId"] = $admin["admin_id"];
            $_SESSION["adminName"] = $admin["admin_name"];

            header("Location: dashboard.php");
            exit;

        } else {
            $error = "Invalid email or password.";
        }

    } else {
        $error = "Invalid email or password.";
    }

    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login | VaccineCare</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="CSS/admin-login.css">
</head>

<body>

    <main class="login-page">

        <section class="login-card">

            <div class="login-header">

                <div class="brand-icon">
                    <i class="fa-solid fa-user-shield"></i>
                </div>

                <p class="brand-name">VaccineCare Admin</p>

                <h1>Admin Panel</h1>

                <p class="subtitle">Vaccination Management System</p>

            </div>

            <?php if (!empty($error)) { ?>
                <div class="alert-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php } ?>

            <form method="POST" class="login-form">

                <div class="form-group">

                    <label for="admin_email">Email Address</label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope"></i>

                        <input
                            type="email"
                            id="admin_email"
                            name="admin_email"
                            placeholder="Enter your email"
                            required
                        >
                    </div>

                </div>

                <div class="form-group">

                    <label for="admin_password">Password</label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock"></i>

                        <input
                            type="password"
                            id="admin_password"
                            name="admin_password"
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

            <p class="footer-text">
                &copy; 2026 VaccineCare. All rights reserved.
            </p>

        </section>

    </main>

</body>
</html>
