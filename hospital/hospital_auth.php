<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION["hospitalId"])) {
    header("Location: hospital_login.php");
    exit;
}

?>
