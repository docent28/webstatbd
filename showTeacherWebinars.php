<?php
echo('showTeacherWebinars.php<br>');
	$token = '548b24d95ee6ada9fd35e9c3298b0796';

	// сортировка массива по возрастанию даты
function cmp($a, $b) {
    return ($b['startsAt'] <=> $a['startsAt']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<?php
		//Показываем вебинары конкретного преподавателя
$allWebinarsTeacher = array();

  $data = array(
      'from' => $_GET['instartdate'].'T00:00:00',
      'to' => $_GET['inenddate'].'T23:59:59',
      'userId' => $_GET['idTeacher'],
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
    $allWebinarsTeacher = $val;
  }

if (count($allWebinarsTeacher) > 0) {
      uasort($allWebinarsTeacher, 'cmp');  // сортируем массив по дате по возрастанию

      $count = 0;
      $strAll = '
        <div class="headTable">
          <div class="number">Номер</div>
          <div class="dateWebinar">Дата</div>
          <div class="nameWebinar">Наименование вебинара</div>
          <div class="startTime">Время начала</div>
          <div class="endTime">Время окончания</div>
          <div class="durationTime">Длительность</div>
        </div>
      ';
      foreach($allWebinarsTeacher as $value){
        $count++;

		$endsAt = substr($value['endsAt'], 11, 8);

		$durationEvent = strtotime($value['endsAt']) - strtotime($value['startsAt']);

        $strAll .= '
          <div class="headTable">
            <div class="number">'.$count.'</div>
            <div class="dateWebinar">'.date('d-m-Y', strtotime(substr($value['startsAt'], 0, 10))).'</div>
            <div class="nameWebinar">'.$value['name'].'</div>
            <div class="startTime">'.substr($value['startsAt'], 11, 8).'</div>
            <div class="endTime">'.$endsAt.'</div>
            <div class="durationTime">'.intdiv($durationEvent, 3600).' ч '
                                      .intdiv(($durationEvent - intdiv($durationEvent, 3600) * 3600), 60).' мин '
                                      .($durationEvent - intdiv($durationEvent, 3600) * 3600 - intdiv(($durationEvent - intdiv($durationEvent, 3600) * 3600), 60) * 60).' сек '.'</div>
          </div>
        ';
      }

      echo('Данным преподавателем за указанный промежуток времени было проведено '.$count.' вебинаров<br><br>');
      echo($strAll);
    } else {
      echo("Вебинары за указанный промежуток времени не проводились");
    }

?>
</body>
</html>