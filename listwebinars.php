<?php
session_save_path("session");
session_start();
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 120)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if (($_SESSION['user'] ?? '') === '') {
    header('Location: /');
}

echo('listwebinars.php<br>');
   $token = '548b24d95ee6ada9fd35e9c3298b0796';

// сортировка массива по возрастанию даты
function cmp($a, $b) {
    return ($b['startsAt'] <=> $a['startsAt']);
}

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

$arrTeamMembers = array();

$count = 0;
foreach ($arrVal as $key => $value) {
    $arrTeamMembers += [$arrVal[$count]['id'] => $arrVal[$count]['secondName']." ".$arrVal[$count]['name']];
    $count++;
}

$allWebinarsAllTeacher = array();

foreach ($arrTeamMembers as $key => $value) {
  // Получить информацию о завершенных мероприятиях
  // Пробегаемся по списку преподавателей

  $data = array(
      'from' => $_GET['instartdate'].'T00:00:00',
      'to' => $_GET['inenddate'].'T23:59:59',
      'userId' => $key,
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
    $result = file_get_contents($url, false, $context);
    $val=json_decode($result, true);
    if (count($val) > 0) {
      $tmp_val = array();
      foreach ($val as $k => $v) {
        $v += array('idTeacher' => $key);
        $v += array('fioTeacher' => $value);
        $tmp_val[] = $v;
      }
    }
    $allWebinarsAllTeacher = array_merge($allWebinarsAllTeacher, $tmp_val);
  }
}

if (count($allWebinarsAllTeacher) == 0) {
  echo("Вебинары за указанный промежуток времени не проводились");
} else {
  uasort($allWebinarsAllTeacher, 'cmp');  // сортируем массив по дате по возрастанию
  $count = 0;
  $strAll = '
      <div class="headTable">
      <div class="number">Номер</div>
      <div class="dateWebinar">Дата</div>
      <div class="nameWebinar">Наименование вебинара</div>
      <div class="startTime">Время начала</div>
      <div class="endTime">Время окончания</div>
      <div class="durationTime">Длительность</div>
      <div class="invitedCount">Было приглашено</div>
      <div class="visitedCount">Количество посетивших</div>
      <div class="nameTeacher">ФИО преподавателя</div>
    </div>
  ';

  foreach($allWebinarsAllTeacher as $key => $value){
    $count++;

    $strAll .= '
      <div class="headTable">
      <div class="number">'.$count.'</div>
      <div class="dateWebinar">'.date('d-m-Y', strtotime(substr($value['startsAt'], 0, 10))).'</div>
      <div class="nameWebinar">'.$value['name'].'</div>
      <div class="startTime">'.substr($value['startsAt'], 11, 8).'</div>
      <div class="endTime">'.substr($value['endsAt'], 11, 8).'</div>
      <div class="durationTime">'.intdiv($value['duration'], 3600).' ч '
                                 .intdiv(($value['duration'] - intdiv($value['duration'], 3600) * 3600), 60).' мин '
                                 .($value['duration'] - intdiv($value['duration'], 3600) * 3600 - intdiv(($value['duration'] - intdiv($value['duration'], 3600) * 3600), 60) * 60).' сек '.'</div>
      <div class="invitedCount">'.$value['invitedCount'].'</div>
      <div class="visitedCount">'.$value['registeredVisitedCount'].'</div>
      <div class="nameTeacher">'.$value['fioTeacher'].'</div>
      </div>
    ';
  }

  echo("<p>Список ПРОШЕДШИХ вебинаров для отображения</p>");
  echo('Количество = '.$count.'<br><br>');
  echo($strAll);
}
?>
<!--
  Пришли с webinars.php
-->
