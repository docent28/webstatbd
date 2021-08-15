<?php
$servername = "localhost";
// $database = "docent28_tstcrn";
// $username = "docent28_tstcrn";
// $password = "Ntyfc1967!";
$database = "webstat";
$username = "root";
$password = "";

$start = microtime(true);

// Устанавливаем соединение
$conn = mysqli_connect($servername, $username, $password, $database);
// Проверяем соединение
if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
}


$sql = "SELECT * FROM `actions`";

$arrActions = mysqli_query($conn, $sql);

echo 'Вытащили мероприятия за ' . (microtime(true) - $start) . ' секунд<br>';

$start = microtime(true);

// Устанавливаем соединение

$sql = "SELECT * FROM `siteuser`";

$arrTeacher = mysqli_query($conn, $sql);

echo 'Вытащили преподавателей за ' . (microtime(true) - $start) . ' секунд<br>';

mysqli_close($conn);

?>