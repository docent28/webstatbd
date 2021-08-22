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

// проходим по всем организациям и актуализируем списки записей
foreach ($arrKeyAPI as $key => $value) {
      $token = $value['keyAPI'];
      $idestablishment = $value['id'];
      $endDate = date("Y-m-d", strtotime("-1 day"));

      // получаем список всех преподавателей, зарегистрированных в системе Webinar.ru
      // по каждой организации которая есть в таблице establishment
      $sql = "SELECT * FROM `siteuser` WHERE idEstablishment = ".$idestablishment;
      $listTeacher = mysqli_query($conn, $sql);
      if (mysqli_num_rows($listTeacher) != 0) {
            if ($value['lastDateUpdateRecords'] != NULL) {
                  $startDate = date("Y-m-d", strtotime("-1 day"));
            } else {
                  $startDate = $value['startDate'];
            }
            foreach ($listTeacher as $keyT => $valueT) {
                  $userID = $valueT['userID'];
                  $data = array(
                      'from' => $startDate.'T00:00:00',
                      'to' => $endDate.'T23:59:59',
                      'userId' => $userID,
                  );
                  $pageNum = 0;
                  $fullPage = true;

                  while ($fullPage) {
                        $data['offset'] = $pageNum * 10;

                        // Получить список записей
                        // https://help.webinar.ru/ru/articles/3151268-получить-список-записей
                        $url = 'https://userapi.webinar.ru/v3/records?'.http_build_query($data);

                        $options = array(
                                'http' => array(
                                'header'  =>
                                "Content-Type: application/x-www-form-urlencoded\r\n" .
                                "x-auth-token: $token\r\n",
                                'method' => 'GET',
                            )
                        );

                        $context  = stream_context_create($options);
                        $result = file_get_contents($url, false, $context);
                        $recTeacherPage=json_decode($result, true);

                        if (count($recTeacherPage) > 0) {
                              foreach ($recTeacherPage as $keyR => $valueR) {
                                    $id = $valueR['id'];
                                    $namerec = "'".$valueR['name']."'";
                                    $daterec = "'".substr($valueR['createAt'], 0, 10)."'";
                                    $timestart = "'".substr($valueR['createAt'], 11, 8)."'";
                                    $sizerec = $valueR['size'];
                                    $sqlR = "INSERT INTO records (id, namerec, daterec, timestart, sizerec, iduser) VALUES ($id, $namerec, $daterec, $timestart, $sizerec, $userID)";
                                    mysqli_query($conn, $sqlR);
                              }
                              $pageNum++;
                        } else {
                              $fullPage = false;
                        }
                  }
            }
            $sqlLastDate = "UPDATE `establishment` SET `lastDateUpdateRecords` = '".date("Y-m-d H:i:s")."' WHERE `establishment`.`id` = ".$idestablishment;
            mysqli_close($conn);
            $conn = mysqli_connect($servername, $username, $password, $database);
            mysqli_query($conn, $sqlLastDate);
      }
}

echo 'Добавили записи за '. (microtime(true) - $start) .' секунд';

mysqli_close($conn);

?>