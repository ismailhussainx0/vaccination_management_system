<?php

session_start();

if(empty($_SESSION["hospitalId"])){
    header("Location: hospital_login.php");
    exit;
}










?>