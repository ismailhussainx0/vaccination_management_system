
<?php
include "../config/db_connection.php";

$registrationMessage = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // CHECKING KEYS SET OR NOT
    if (isset(
        $_POST["hospital_name"],
        $_POST["hospital_email"],
        $_POST["hospital_password"],
        $_POST["confirm_password"],
        $_POST["hospital_phone"],
        $_POST["hospital_address"]
    )) {

        $hospitalName = trim($_POST["hospital_name"]);
        $hospitalEmail = trim($_POST["hospital_email"]);
        $password = $_POST["hospital_password"];
        $confirmPassword = $_POST["confirm_password"];
        $hospitalPhone = trim($_POST["hospital_phone"]);
        $hospitalAddress = trim($_POST["hospital_address"]);

        // CHECKS WHETHER THE FIELDS ARE EMPTY OR NOT
        if (
            !empty($hospitalName) &&
            !empty($hospitalEmail) &&
            !empty($password) &&
            !empty($confirmPassword) &&
            !empty($hospitalPhone) &&
            !empty($hospitalAddress)
        ) {

            // CHECKING CONFIRM PASSWORD
            if ($confirmPassword === $password) {

                $hashPassword = password_hash($password, PASSWORD_DEFAULT);

                try {

                    $selectSql = "SELECT * FROM hospital WHERE hospital_email = ?";
                    $selectStmt = mysqli_prepare($connect, $selectSql);
                    $selectStmt->bind_param("s", $hospitalEmail);
                    $selectStmt->execute();

                    $selectResult = $selectStmt->get_result();

                    if (mysqli_num_rows($selectResult) > 0) {

                        $registrationMessage = "This Email already exists.";
                        $messageType = "error";

                    } else {

                        $insertSql = "INSERT INTO hospital
                            (hospital_name, hospital_email, hospital_phone, password, hospital_address)
                            VALUES (?, ?, ?, ?, ?)";

                        $insertStmt = mysqli_prepare($connect, $insertSql);

                        $insertStmt->bind_param(
                            "sssss",
                            $hospitalName,
                            $hospitalEmail,
                            $hospitalPhone,
                            $hashPassword,
                            $hospitalAddress
                        );

                        $insertResult = $insertStmt->execute();

                        if ($insertResult) {

                            header("Location: hospital_login.php");
                            exit;

                        } else {

                            $registrationMessage = "Registration failed. Please try again.";
                            $messageType = "error";

                        }

                        $insertStmt->close();
                    }

                    $selectStmt->close();

                } catch (mysqli_sql_exception $error) {

                    $registrationMessage = "Something went wrong. Please try again later.";
                    $messageType = "error";

                }

            } else {

                $registrationMessage = "Confirm password does not match.";
                $messageType = "error";

            }

        } else {

            $registrationMessage = "Please fill in all fields.";
            $messageType = "error";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hospital Registration</title>

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
                    <i class="fa-solid fa-hospital"></i>
                </div>

                <p class="brand-name">VaccineCare</p>

                <h1>Register Your Hospital</h1>

                <p class="subtitle">
                    Create your hospital account to manage vaccination appointments.
                </p>

            </div>

            <?php if (!empty($registrationMessage)): ?>
                <div class="alert-message <?php echo $messageType; ?>">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo htmlspecialchars($registrationMessage); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="register-form">

                <div class="form-group">
                    <label for="hospital_name">Hospital Name</label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-hospital"></i>

                        <input
                            type="text"
                            id="hospital_name"
                            name="hospital_name"
                            placeholder="Enter hospital name"
                            required
                            value="<?php echo htmlspecialchars($_POST['hospital_name'] ?? ''); ?>"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="hospital_email">Email Address</label>

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

                <div class="form-row">

                    <div class="form-group">
                        <label for="hospital_password">Password</label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock"></i>

                            <input
                                type="password"
                                id="hospital_password"
                                name="hospital_password"
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
                    <label for="hospital_phone">Phone Number</label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-phone"></i>

                        <input
                            type="tel"
                            id="hospital_phone"
                            name="hospital_phone"
                            placeholder="Enter hospital phone"
                            required
                            value="<?php echo htmlspecialchars($_POST['hospital_phone'] ?? ''); ?>"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="hospital_address">Hospital Address</label>

                    <div class="input-wrapper textarea-wrapper">
                        <i class="fa-solid fa-location-dot"></i>

                        <textarea
                            id="hospital_address"
                            name="hospital_address"
                            placeholder="Enter hospital address"
                            required
                        ><?php echo htmlspecialchars($_POST['hospital_address'] ?? ''); ?></textarea>
                    </div>
                </div>

                <button type="submit" class="register-btn">
                    Register Hospital
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </form>

            <p class="login-link">
                Already have an account?
                <a href="hospital_login.php">Login here</a>
            </p>

            <p class="footer-text">
                © 2026 VaccineCare. All rights reserved.
            </p>

        </section>

    </main>

</body>
</html>
