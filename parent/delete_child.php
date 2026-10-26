<?php

include "parent_auth.php";
include "../config/db_connection.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    if(isset($_POST["child_id"])){
        
        $childId = $_POST["child_id"]; 
        $parentId = $_SESSION["parent_id"];  
        
        $deleteSql = "DELETE FROM child WHERE parent_id = ? AND child_id = ?";
        $deleteStmt = mysqli_prepare($connect, $deleteSql);
        $deleteStmt->bind_param("ii", $parentId, $childId);
        $result = $deleteStmt->execute();
        
        if($result){
            header("Location: dashboard.php");
            exit;
        }

        $deleteStmt->close();
            
    }
            
}








?>