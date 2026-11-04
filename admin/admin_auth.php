
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["adminId"])) {

    header("Location: admin_login.php");
    exit;

}

?>
