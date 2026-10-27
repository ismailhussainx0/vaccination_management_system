
<form action="" method="POST">

    <label for="child_name">Child Name:</label>
    <input
        type="text"
        name="child_name"
        placeholder="Enter child's name"
        required
    >

    <br><br>

    <label for="date_of_birth">Date of Birth:</label>
    <input
        type="date"
        id="date_of_birth"
        name="date_of_birth"
        required
    >

    <br><br>

    <button type="submit" name="add_child">
        Add Child
    </button>

</form>










<?php
include "../config/db_connection.php";
include "parent_auth.php";


if($_SERVER["REQUEST_METHOD"] === "POST"){

    if(isset($_POST["child_name"], $_POST["date_of_birth"])){

        $childName = trim($_POST["child_name"]);
        $dateOfBirth = $_POST["date_of_birth"];
        $parentId = $_SESSION["parent_id"];

       if(!empty($childName) && !empty($dateOfBirth)){

        try{

            
            $insertQuery = "INSERT INTO child (child_name, date_of_birth, parent_id) VALUES (?, ?, ?)";      
            $insertStmt = mysqli_prepare($connect, $insertQuery);
            $insertStmt->bind_param("ssi", $childName, $dateOfBirth, $parentId);
            $result = $insertStmt->execute();
            
            if($result){
                header("Location: dashboard.php");
                exit;
            }else {
                echo "Data Not added";
            }

            $insertStmt->close();
                
        }catch(mysqli_sql_exception $error){
            echo "Something Went Wrong. Please try later!";
        }
                
                
       }else{
           echo "Fill all Fields"; 
       }





    }else{
        echo "Name Attribute are not correct";
    }





}





?>