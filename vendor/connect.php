<?php

$servername = "localhost";
//$database = "docent28_tstcrn";
//$username = "docent28_tstcrn";
//$password = "Ntyfc1967!";
$database = "webstat";
$username = "root";
$password = "";

    $conn= mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die('Error connect to DataBase');
    }