
<?php

include "../config/db_connection.php";

$registrationMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset(
        $_POST["parent_name"],
        $_POST["parent_email"],
        $_POST["parent_password"],
        $_POST["confirm_password"]
    )) {

        $name = trim($_POST["parent_name"]);
        $email = trim($_POST["parent_email"]);
        $phone = trim($_POST["parent_phone"] ?? "");
        $password = $_POST["parent_password"];
        $confirmPassword = $_POST["confirm_password"];

        if (
            !empty($name) &&
            !empty($email) &&
            !empty($password) &&
            !empty($confirmPassword)
        ) {

            if ($password === $confirmPassword) {

                $hashPassword = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                // Optional phone: empty value becomes NULL
                if ($phone === "") {
                    $phone = null;
                }

                try {

                    // Check whether email already exists
                    $selectSql = "SELECT * FROM parents WHERE email = ?";

                    $selectStmt = mysqli_prepare(
                        $connect,
                        $selectSql
                    );

                    $selectStmt->bind_param("s", $email);
                    $selectStmt->execute();

                    $selectResult = $selectStmt->get_result();

                    if (mysqli_num_rows($selectResult) > 0) {

                        $registrationMessage = "This email already exists.";

                    } else {

                        // Insert new parent
                        $sql = "INSERT INTO parents
                                (name, email, password, phone)
                                VALUES (?, ?, ?, ?)";

                        $statement = mysqli_prepare(
                            $connect,
                            $sql
                        );

                        $statement->bind_param(
                            "ssss",
                            $name,
                            $email,
                            $hashPassword,
                            $phone
                        );

                        $result = $statement->execute();

                        if ($result) {

                            header("Location: parent_login.php");
                            exit;

                        } else {

                            $registrationMessage = "Registration failed. Please try again.";

                        }

                        $statement->close();
                    }

                    $selectStmt->close();

                } catch (mysqli_sql_exception $error) {

                    $registrationMessage = "Something went wrong. Please try again later.";

                }

            } else {

                $registrationMessage = "Confirm password does not match.";

            }

        } else {

            $registrationMessage = "Please fill all required fields.";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Parent Registration | VaccineCare</title>

    <link rel="stylesheet" href="CSS/register.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <main class="register-page">

        <section class="register-card">

            <div class="register-header">

                <div class="brand-icon">
                    <i class="fa-solid fa-user-shield"></i>
                </div>

                <p class="brand-name">VaccineCare</p>

                <h1>Create Parent Account</h1>

                <p class="subtitle">
                    Register your account to manage your children's vaccination appointments.
                </p>

            </div>

            <?php if (!empty($registrationMessage)): ?>
                <div class="alert-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo htmlspecialchars($registrationMessage); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="register-form">

                <div class="form-group">

                    <label for="parent_name">Full Name</label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-user"></i>

                        <input
                            type="text"
                            id="parent_name"
                            name="parent_name"
                            placeholder="Enter your name"
                            required
                            value="<?php echo htmlspecialchars($_POST['parent_name'] ?? ''); ?>"
                        >
                    </div>

                </div>

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

                <div class="form-row">

                    <div class="form-group">

                        <label for="parent_password">Password</label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock"></i>

                            <input
                                type="password"
                                id="parent_password"
                                name="parent_password"
                                placeholder="Enter password"
                                required
                            >
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="confirm_password">Confirm Password</label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock"></i>

                            <input
                                type="password"
                                id="confirm_password"
                                name="confirm_password"
                                placeholder="Confirm password"
                                required
                            >
                        </div>

                    </div>

                </div>

                <div class="form-group">

                    <label for="parent_phone">
                        Phone Number
                        <span class="optional-label">Optional</span>
                    </label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-phone"></i>

                        <input
                            type="tel"
                            id="parent_phone"
                            name="parent_phone"
                            placeholder="Enter your phone number"
                            value="<?php echo htmlspecialchars($_POST['parent_phone'] ?? ''); ?>"
                        >
                    </div>

                </div>

                <button type="submit" class="register-btn">
                    Create Account
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </form>

            <p class="login-link">
                Already have an account?
                <a href="parent_login.php">Login here</a>
            </p>

            <p class="footer-text">
                © 2026 VaccineCare. All rights reserved.
            </p>

        </section>

    </main>

</body>
</html>
