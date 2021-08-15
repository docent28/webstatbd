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
echo($sql);
echo('<br><br>');

$arrActions = mysqli_query($conn, $sql);
var_dump($arrActions);

echo('<br><br>');

$row = mysqli_fetch_array($arrActions, MYSQLI_ASSOC);

var_dump($row);

echo 'Вытащили мероприятия за ' . (microtime(true) - $start) . ' секунд<br>';

mysqli_close($conn);

?>