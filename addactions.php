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

// проходим по всем организациям и актуализируем список мероприятий
foreach ($arrKeyAPI as $key => $value) {
	if ($value['initialDownload']) {
		$startDate = date("Y-m-d", strtotime("-1 day"));
	}
	$token = $value['keyAPI'];
	$idestablishment = $value['id'];
	$startDate = $value['startDate'];
	$endDate = date("Y-m-d", strtotime("-1 day"));

	// получаем список всех преподавателей, зарегистрированных в системе Webinar.ru
	// по каждой организации которая есть в таблице establishment
	$sql = "SELECT * FROM `siteuser` WHERE idEstablishment = ".$idestablishment;
	$listTeacher = mysqli_query($conn, $sql);
	if (mysqli_num_rows($listTeacher) != 0) {
		if ($value['initialDownload']) {
			$startDate = date("Y-m-d", strtotime("-1 day"));
		}
		foreach ($listTeacher as $keyT => $valueT){
			$userID = $valueT['userID'];
			// Получить информацию о завершенных мероприятиях
			// Пробегаемся по списку преподавателей
			$data = array(
			    'from' => $startDate.'T00:00:00',
			    'to' => $endDate.'T23:59:59',
			    'userId' => $userID,
			);

			// Выгрузить статистику по вебинарам
			// https://help.webinar.ru/ru/articles/3149503-выгрузить-статистику-по-вебинарам
			$url = 'https://userapi.webinar.ru/v3/stats/events?'.http_build_query($data);

			$options = array(
			        'http' => array(
			        'header'  =>
			        "Content-Type: application/x-www-form-urlencoded\r\n" .
			        "x-auth-token: $token\r\n",
			        'method' => 'GET',
			    )
			);

			$context  = stream_context_create($options);
			if (@file_get_contents($url, false, $context)) {
				$resultR = file_get_contents($url, false, $context);
				$arrActions = json_decode($resultR, true);
				if (count($arrActions) > 0) {
					foreach ($arrActions as $keyA => $valueA) {
						$idRecords = $arrActions[$keyA]['id'];
						$name = "'".$arrActions[$keyA]['name']."'";
						$dateStart = "'".substr($arrActions[$keyA]['startsAt'], 0, 19)."'";
						$dateEnd = "'".substr($arrActions[$keyA]['endsAt'], 0, 19)."'";

						$sql = "INSERT INTO actions (idRecords, name, dateStart, dateEnd, idUser) VALUES ($idRecords, $name, $dateStart, $dateEnd, $userID)";
						mysqli_query($conn, $sql);
					}
				}
			}
		}
		if (!$value['initialDownload']) {
			$sqlU = "UPDATE `establishment` SET `initialDownload` = '1' WHERE `establishment`.`id` = ".$idestablishment;
			mysqli_query($conn, $sqlU);
		}
		$sqlLastDate = "UPDATE `establishment` SET `lastDateUpdate` = '".microtime(true)."' WHERE `establishment`.`id` = ".$idestablishment;
		mysqli_query($conn, $sqlLastDate);
	}
}

echo 'Добавили мероприятия за ' . (microtime(true) - $start) . ' секунд<br>';

mysqli_close($conn);

?>
