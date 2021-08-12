<?php
echo('index.php<br>');
   $token = '548b24d95ee6ada9fd35e9c3298b0796';

$start = microtime(true);


    // получаем список всех преподавателей, зарегистрированных в системе Webinar.ru
    // Получить данные о сотрудниках Организации
    // https://help.webinar.ru/ru/articles/3151499-получить-данные-о-сотрудниках-организации
$data = array();
$url = 'https://userapi.webinar.ru/v3/organization/members'.http_build_query($data);
$options = array(
        'http' => array(
        'header'  =>
        "Content-Type: application/x-www-form-urlencoded\r\n" .
        "x-auth-token: $token\r\n",
        'method'  => 'GET',
    )
);
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

$arrVal=json_decode($result, true);


echo 'Получили массив преподавателей за ' . (microtime(true) - $start) . ' секунд<br>';

//===========================================================================

$start = microtime(true);

    // получаем список всех преподавателей, зарегистрированных в системе Webinar.ru
    // Получить данные о сотрудниках Организации
    // https://help.webinar.ru/ru/articles/3151499-получить-данные-о-сотрудниках-организации
$data = array();
$url = 'https://userapi.webinar.ru/v3/organization/members'.http_build_query($data);
$options = array(
        'http' => array(
        'header'  =>
        "Content-Type: application/x-www-form-urlencoded\r\n" .
        "x-auth-token: $token\r\n",
        'method'  => 'GET',
    )
);
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

$arrVal=json_decode($result, true);

//$arrTeamMembers = array();

$servername = "localhost";
$database = "webstat";
$username = "root";
$password = "";
// Устанавливаем соединение
$conn = mysqli_connect($servername, $username, $password, $database);
// Проверяем соединение
if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
}
 
echo "Connected successfully";

//var_dump($arrVal);

$count = 0;
foreach ($arrVal as $key => $value) {
//    $arrTeamMembers += [$arrVal[$count]['id'] => $arrVal[$count]['secondName']." ".$arrVal[$count]['name']];
    $nameUser ="'".$arrVal[$count]['secondName'].' '.$arrVal[$count]['name']."'";
    $userID = $arrVal[$count]['id'];
    $sql = "INSERT INTO siteuser (name, userid, idestablishment) VALUES ($nameUser, $userID, 1)";
    if (!mysqli_query($conn, $sql)) {
          echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    $count++;
}

mysqli_close($conn);

echo 'Заполнили таблицу преподавателей за ' . (microtime(true) - $start) . ' секунд<br>';
//=================================================================================

$start = microtime(true);

$conn = mysqli_connect($servername, $username, $password, $database);
$sql = "SELECT * FROM siteuser";
$result = mysqli_query($conn, $sql);
echo 'Получили массив из таблицы за ' . (microtime(true) - $start) . ' секунд<br>';




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