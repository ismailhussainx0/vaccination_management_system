
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>

    <form method="post">
        <label for="parent_name">Name</label><br>
        <input type="text" id="parent_name" name="parent_name"
               placeholder="Enter your name" required><br>

        <label for="parent_email">Email</label><br>
        <input type="email" id="parent_email" name="parent_email"
               placeholder="Enter your email" required><br>

        <label for="parent_password">Password</label><br>
        <input type="password" id="parent_password" name="parent_password"
               placeholder="Enter your password" required><br>

        <label for="confirm_password">Confirm Password</label><br>
        <input type="password" id="confirm_password" name="confirm_password"
               placeholder="Confirm your password" required><br>

        <label for="parent_phone">Phone</label><br>
        <input type="tel" id="parent_phone" name="parent_phone"
               placeholder="Optional"><br>

        <button type="submit">Submit</button>
    </form>

</body>
</html>

<?php

include "db_connection.php";

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

                        echo "This email already exists";

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
                            echo "Data not inserted";
                        }

                        $statement->close();
                    }

                    $selectStmt->close();

                } catch (mysqli_sql_exception $error) {

                    echo "Something went wrong. In Parent Registration. Please try again.";

                }

            } else {
                echo "Confirm password is not correct";
            }

        } else {
            echo "Please fill all required fields!";
        }
    }
}

?>
