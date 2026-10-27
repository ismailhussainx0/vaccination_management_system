<?php
include "parent_auth.php";
include "../config/db_connection.php";

    // IDES PARENT AND CHILD
    $childId = $_GET["child_id"];
    $parentId = $_SESSION["parent_id"];
    
    // SELECT INSERTED DATA 
    $selectSql = "SELECT * FROM child WHERE child_id = ? AND parent_id = ?";
    $selectStmt = mysqli_prepare($connect, $selectSql);
    $selectStmt->bind_param("ii", $childId, $parentId);
    $selectStmt->execute();

    $result = $selectStmt->get_result();

    
    if(mysqli_num_rows($result) > 0){
        
        $resultRow = mysqli_fetch_assoc($result);

        
        // OLD DATA FROM DATABASE
        $childName = $resultRow["child_name"];
        $dateOfBirth = $resultRow["date_of_birth"];
        
        if($_SERVER["REQUEST_METHOD"] === "POST"){


            if(isset($_POST["child_name"], $_POST["date_of_birth"])){
                
                $updatedName = trim($_POST["child_name"]);
                $updatedDate = $_POST["date_of_birth"];

                $updateSql = "UPDATE child
                            SET child_name = ?, date_of_birth = ?
                            WHERE child_id = ? AND parent_id = ?";

                $updateStmt = mysqli_prepare($connect, $updateSql);
                $updateStmt->bind_param("ssii", $updatedName, $updatedDate, $childId, $parentId); 
                $updateResult = $updateStmt->execute();

                if($updateResult){
                    header("Location: dashboard.php"); 
                    exit;
                }




            }


        }


    }else{
        echo "This child doesnot exists";
    }



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Form</title>
</head>
<body>


<form action="" method="POST">

    <label for="child_name">Child Name:</label>
    <input
        type="text"
        id="child_name"
        name="child_name"
        value="<?php echo htmlspecialchars($childName); ?>"
        required
    >

    <br><br>

    <label for="date_of_birth">Date of Birth:</label>
    <input
        type="date"
        id="date_of_birth"
        name="date_of_birth"
        value="<?php echo htmlspecialchars($dateOfBirth); ?>"
        required
    >

    <br><br>

    <button type="submit" name="update_child">
        Update Child
    </button>

</form>

</body>
</html>


<?php





?>








