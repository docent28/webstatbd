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

	$sql = "SELECT * FROM `siteuser` WHERE `idestablishment` = '".$idestablishment."'";
      $arrTeacherTable = mysqli_query($conn, $sql);
      $arrTeacherTable = mysqli_fetch_all($arrTeacherTable, MYSQLI_ASSOC);

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



/*
      foreach ($arrTeacherTable as $keyT => $valueT) {
            foreach ($listTeacher as $keyL => $valueL) {
                  if ($valueL['id'] == $valueT['userID']) {
                        echo('нашел<br>');
                        break;
                  } else {
                        $nameUser = "'".$valueL['secondName']." ".$valueL['name']."'";
                        $userID = $valueL['id'];

                        $sql = "INSERT INTO siteuser (name, userid, idestablishment) VALUES ($nameUser, $userID, $idestablishment)";
                        mysqli_query($conn, $sql);
                  }
            }
      }
*/

}

echo 'Сверили массивы за ' . (microtime(true) - $start) . ' секунд<br>';

/*

for ($i=0; $i < 10; $i++) { 
	$name = "'user.$i'";
	$datestart = "'2021-08-04'";
	$dateend = "'2021-08-19'";
	$iduser = 1000 + $i;
	$sql = "INSERT INTO tstcrn (name, datestart, dateend, iduser) VALUES ($name, $datestart, $dateend, $iduser)";
	if (!mysqli_query($conn, $sql)) {
	      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
 } 
*/
mysqli_close($conn);


?>

<?php
/*
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



*/
?>
