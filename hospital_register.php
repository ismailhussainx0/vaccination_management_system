
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Registration</title>
</head>
<body>

    <h2>Hospital Registration</h2>

    <form method="post">

        <label for="hospital_name">Hospital Name</label><br>
        <input
            type="text"
            name="hospital_name"
            placeholder="Enter hospital name"
            required
        >
        <br><br>

        <label for="hospital_email">Email</label><br>
        <input
            type="email"
            name="hospital_email"
            placeholder="Enter hospital email"
            required
        >
        <br><br>

        <label for="hospital_password">Password</label><br>
        <input
            type="password"
            name="hospital_password"
            placeholder="Enter password"
            required
        >
        <br><br>

        <label for="confirm_password">Confirm Password</label><br>
        <input
            type="password"
            name="confirm_password"
            placeholder="Confirm password"
            required
        >
        <br><br>

        <label for="hospital_phone">Phone</label><br>
        <input
            type="tel"
            name="hospital_phone"
            placeholder="Enter hospital phone"
            required
        >
        <br><br>

        <label for="hospital_address">Address</label><br>
        <textarea
            name="hospital_address"
            placeholder="Enter hospital address"
            required
        ></textarea>
        <br><br>

        <button type="submit">Register Hospital</button>

    </form>

</body>
</html>


<?php
include "db_connection.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    // CHECKING KEYS SET OR NOT
    if(isset($_POST["hospital_name"], $_POST["hospital_email"], $_POST["hospital_password"],
    $_POST["confirm_password"], $_POST["hospital_phone"], $_POST["hospital_address"])) {

        $hospitalName = trim($_POST["hospital_name"]);
        $hospitalEmail = trim($_POST["hospital_email"]);
        $password = $_POST["hospital_password"];
        $confirmPassword = $_POST["confirm_password"];
        $hospitalPhone = trim($_POST["hospital_phone"]);
        $hospitalAddress = trim($_POST["hospital_address"]);

        // CHECKS WHETHER THE FIELDS ARE EMPTY OR NOT
        if(!empty($hospitalName) && !empty($hospitalEmail) && !empty($password) && !empty($confirmPassword) && !empty($hospitalPhone) && !empty($hospitalAddress)) {

            // CHECKING CONFIRM PASSWORD
            if($confirmPassword === $password){

                $hashPassword = password_hash($password, PASSWORD_DEFAULT);
                
                try{

                    $selectSql = "SELECT * FROM hospital WHERE hospital_email = ?";
                    $selectStmt = mysqli_prepare($connect, $selectSql);
                    $selectStmt->bind_param("s", $hospitalEmail);
                    $selectStmt->execute();

                    $selectResult = $selectStmt->get_result();

                    if(mysqli_num_rows($selectResult) > 0){

                        echo "This Email is already exists";

                    }else{

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

                        if($insertResult){

                            header("Location: hospital_login.php");
                            exit;

                        }else{

                            echo "Not Registered";

                        }

                        // INSERT STATEMENT CLOSE
                        $insertStmt->close();




                    }

                    // SELECT STATEMENT CLOSE 
                    $selectStmt->close();
                    
                } catch(mysqli_sql_exception $error){

                    echo "Something Went Wrong. in Hospital Registration Try later";

                }


            }else {
                echo "Confirm Password is Incroccet";
            }




        }else {
            echo "Please Fill All Fields";
        }







     }





}






?>