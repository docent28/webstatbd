<?php
    echo('index.php<br>');

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

$sql = "SELECT * FROM `establishment`";
$arrKeyAPI = mysqli_query($conn, $sql);


echo 'Добавили мероприятия за ' . (microtime(true) - $start) . ' секунд<br>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <title>Статистика Webinar-ru</title>
</head>
<body>
    <form>
        <input type="button" value = "Все вебинары" name="webinars">
        <input type="button" value = "Преподаватель" name="teachers">
        <input type="button" value = "Удаление вебинаров" name="delWebinars">
        <input type="button" value = "Удаление записей" name="delRecords">
        <input type="button" value = "Общее" name="statistic">
    </form>
    <div id="contentBody">
    </div>
    <div id="contentTest">
    </div>
    <div id="delRecords">
    </div>
    <div id="loading" style="display: none">
        <p>Идет загрузка...</p>
    </div>
    <script type="text/javascript" src="js/showcontent.js"></script>
</body>
</html>