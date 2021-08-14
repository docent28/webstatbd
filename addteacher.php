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


$sql = "SELECT * FROM `establishment`";
$arrKeyAPI = mysqli_query($conn, $sql);

// проходим по всем организациям и актуализируем списки преподавателей
foreach ($arrKeyAPI as $key => $value) {
	$token = $value['keyAPI'];
      $idestablishment = $value['id'];
    // получаем список всех преподавателей, зарегистрированных в системе Webinar.ru
    // по каждой организации которая есть в таблице establishment
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

	$listTeacher = json_decode($result, true);     // вытащили преподавателей конкретной организации, актуальное состояние

      foreach ($listTeacher as $keyL => $valueL) {
            $sql = "SELECT * FROM `siteuser` WHERE 'userID' = ".$valueL['id'];
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) == 0) {
                        $nameUser = "'".$valueL['secondName']." ".$valueL['name']."'";
                        $userID = $valueL['id'];

                        $sql = "INSERT INTO siteuser (name, userid, idestablishment) VALUES ($nameUser, $userID, $idestablishment)";
                        mysqli_query($conn, $sql);
            }
      }

}

echo 'Сверили массивы преподавателей за ' . (microtime(true) - $start) . ' секунд<br>';

mysqli_close($conn);

?>
