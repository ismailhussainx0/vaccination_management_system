
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Login</title>
</head>
<body>

    <h2>Hospital Login</h2>

    <form method="post">

        <label for="hospital_email">Hospital Email</label>
        <br>
        <input
            type="email"
            id="hospital_email"
            name="hospital_email"
            placeholder="Enter hospital email"
            required
        >
        <br><br>

        <label for="hospital_password">Password</label>
        <br>
        <input
            type="password"
            id="hospital_password"
            name="hospital_password"
            placeholder="Enter password"
            required
        >
        <br><br>

        <button type="submit">Login</button>

    </form>

</body>
</html>



<?php
session_start();
include "db_connection.php";


if($_SERVER["REQUEST_METHOD"] === "POST"){

    if(isset($_POST["hospital_email"], $_POST["hospital_password"])){

        $email = trim($_POST["hospital_email"]);
        $enterPassword = $_POST["hospital_password"];

        if(!empty($email) && !empty($enterPassword)){

            try{

                $selectSql = "SELECT * FROM hospital WHERE hospital_email = ?";
                $selectStmt = mysqli_prepare($connect, $selectSql);
                
                $selectStmt->bind_param("s", $email);
                $selectStmt->execute();
                
                $result = $selectStmt->get_result();
                
                // Check whether email exists
                if (mysqli_num_rows($result) > 0) {
                    
                    $resultRow = mysqli_fetch_assoc($result);
                    $storedHash = $resultRow["password"];
                    
                    if(password_verify($enterPassword, $storedHash)) {

                        $_SESSION['hospitalId'] = $resultRow['hospital_id'];
                        $_SESSION['hospitalName'] = $resultRow['hospital_name'];
                        
                        echo "loged In";
                        
                    }else {
                        echo "Incorrect Password";
                    }
                
                    
                }else {
                    echo "Incorrect Email";
                }

                // CLOSE STATEMENT 
                $selectStmt->close();

                
            }catch(mysqli_sql_exception $e){

                echo "Something went wrong. Try later";
                
            }


        }else{
            echo "Enter all Fields";
        }


    }


}




?>