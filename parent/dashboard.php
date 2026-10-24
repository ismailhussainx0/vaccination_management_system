<?php
session_start();
include "../config/db_connection.php";

if(!empty($_SESSION["parent_id"])){

    $userName = $_SESSION["name"]; 

    echo "Hello $userName". $_SESSION["parent_id"];

}else{
    header("Location: parent_login.php");
}














?>