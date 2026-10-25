
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Login</title>
</head>
<body>

    <h2>Parent Login</h2>

    <form method="post">

        <label for="parent_email">Email</label><br>
        <input
            type="email"
            id="parent_email"
            name="parent_email"
            placeholder="Enter your email"
            required
        >
        <br><br>

        <label for="parent_password">Password</label><br>
        <input
            type="password"
            id="parent_password"
            name="parent_password"
            placeholder="Enter your password"
            required
        >
        <br><br>

        <button type="submit">Login</button>

    </form>

</body>
</html>

<?php
session_start();
include "../config/db_connection.php";

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
                        $_SESSION["parent_id"] = $resultRow["parent_id"];    
                        $_SESSION["parent_name"] = $resultRow["name"];    
        
                        header("Location: dashboard.php");

                    } else {

                        echo "Invalid Password";

                    }

                } else {

                    echo "Invalid Email";

                }

                $selectStmt->close();

            } catch (mysqli_sql_exception $error) {

                echo "Something went wrong. Please try again later.";

            }

        } else {

            echo "Fill both fields";

        }
    }
}

?>
