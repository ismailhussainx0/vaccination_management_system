<?php

$connect = mysqli_connect(
    "localhost",
    "root",
    "",
    "vaccination"
);

if (!$connect) {
    die("Database connection failed");
}

?>
