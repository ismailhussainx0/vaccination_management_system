
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

    <title>Admin Login</title>

    <link rel="stylesheet" href="CSS/admin-login.css">
</head>

<body>

    <div class="login-container">

        <div class="login-header">
            <h1>Admin Panel</h1>
            <p>Vaccination Management System</p>
        </div>

        <form method="POST">

            <div class="form-group">
                <label>Email Address</label>

                <input
                    type="email"
                    name="admin_email"
                    placeholder="Enter your email"
                    required
                >
            </div>

            <div class="form-group">
                <label>Password</label>

                <input
                    type="password"
                    name="admin_password"
                    placeholder="Enter your password"
                    required
                >
            </div>

            <?php if (!empty($error)) { ?>
                <p class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </p>
            <?php } ?>

            <button type="submit">Login to Dashboard</button>

        </form>

    </div>

</body>
</html>
