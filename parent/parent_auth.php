<?php
session_start();

if(empty($_SESSION["parent_id"])){

    header("Location: parent_login.php");
    exit;

}

?>