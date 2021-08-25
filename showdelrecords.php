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

echo('showdelrecords.php<br>');
	$token = '548b24d95ee6ada9fd35e9c3298b0796';

	// сортировка массива по возрастанию даты
function cmp($a, $b) {
    return ($b['createAt'] <=> $a['createAt']);
}

//Показываем список записей которые мы хотели бы удалить

$data = array(
	'from' => $_GET['instartdate'].'T00:00:00',
	'to' => $_GET['inenddate'].'T23:59:59',
);

if ($_GET['idTeacher'] != '0') {
	$data['userId'] = $_GET['idTeacher'];
}

  // получаем список всех преподавателей, зарегистрированных в системе Webinar.ru
  // Получить данные о сотрудниках Организации
  // https://help.webinar.ru/ru/articles/3151499-получить-данные-о-сотрудниках-организации
	$dataTeacher = array();
	$urlTeacher = 'https://userapi.webinar.ru/v3/organization/members'.http_build_query($dataTeacher);
	$optionsTeacher = array(
	    		'http' => array(
	        'header'  =>
	        "Content-Type: application/x-www-form-urlencoded\r\n" .
	        "x-auth-token: $token\r\n",
	        'method'  => 'GET',
	    )
	);
	$contextTeacher = stream_context_create($optionsTeacher);
	$resultTeacher = file_get_contents($urlTeacher, false, $contextTeacher);

	$arrT=json_decode($resultTeacher, true);

	$arrTeacher = array();

	$countT = 0;
	foreach ($arrT as $keyT => $valueT) {
	    $arrTeacher += [$arrT[$countT]['id'] => $arrT[$countT]['secondName']." ".$arrT[$countT]['name']];
	    $countT++;
	}

if ($_GET['idTeacher'] == 0) {
	$allRecordsAllTeacher = [];
	foreach ($arrTeacher as $key => $value) {
		$data['userId'] = $key;

		$pageNum = 0;
		$fullPage = true;
		$allRecordsTeacher = [];

		while($fullPage) {
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
			$val=json_decode($result, true);

			if (count($val) > 0) {
				$allRecordsTeacher = array_merge($allRecordsTeacher, $val);
				$pageNum++;
			} else {
				$fullPage = false;
			}
		}
    if (count($allRecordsTeacher) > 0) {
      $tmp_val = array();
      foreach ($allRecordsTeacher as $k => $v) {
        $v += array('idTeacher' => $key);
        $v += array('fioTeacher' => $value);
        $tmp_val[] = $v;
      }
			$allRecordsAllTeacher = array_merge($allRecordsAllTeacher, $tmp_val);
    }
	}

} else {
	$pageNum = 0;
	$fullPage = true;
	$allRecordsTeacher = [];

	while($fullPage) {
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
		$val=json_decode($result, true);

		if (count($val) > 0) {
			$allRecordsTeacher = array_merge($allRecordsTeacher, $val);
			$pageNum++;
		} else {
			$fullPage = false;
		}
	}
  $tmp_val = array();
  foreach ($allRecordsTeacher as $k => $v) {
    $v += array('idTeacher' => $_GET['idTeacher']);
    $v += array('fioTeacher' => $arrTeacher[$_GET['idTeacher']]);
    $tmp_val[] = $v;
  }
	$allRecordsAllTeacher = $tmp_val;
}

if (count($allRecordsAllTeacher) > 0) {
      uasort($allRecordsAllTeacher, 'cmp');  // сортируем массив по дате по возрастанию

      $count = 0;
      $allSize = 0;
      $strAll = '
        <div class="headTable">
          <div class="number">Номер</div>
          <div class="dateWebinar">Дата</div>
          <div class="nameWebinar">Наименование вебинара</div>
          <div class="startTime">Время начала</div>
          <div class="sizeMB">Размер, MB</div>
		      <div class="nameTeacher">ФИО преподавателя</div>
		      <div class="checkDel">Удалить</div>
        </div>
      ';
      foreach($allRecordsAllTeacher as $value){
        $count++;
        $allSize += $value['size'];

        $strAll .= '
          <div class="headTable">
            <div class="number">'.$count.'</div>
            <div class="dateWebinar">'.date('d-m-Y', strtotime(substr($value['createAt'], 0, 10))).'</div>
            <div class="nameWebinar">'.$value['name'].'</div>
            <div class="startTime">'.substr($value['createAt'], 11, 8).'</div>';

            $sizeRecords = $value['size'];
			      $numDivisions = 0;
			      while ($sizeRecords > 1000) {
			      	$sizeRecords = $sizeRecords / 1000;
			      	$numDivisions++;
			      }
			      switch ($numDivisions) {
			      	case 0:
			      		$unitMeasure = 'B';
			      		break;
			      	case 1:
			      		$unitMeasure = 'KB';
			      		break;
			      	case 2:
			      		$unitMeasure = 'MB';
			      		break;
			      	case 3:
			      		$unitMeasure = 'GB';
			      		break;
			      	default:
			      		$unitMeasure = 'Неизвестная величина';
			      		break;
			      }

				$strAll .= '
            <div class = "sizeMB">'.number_format($sizeRecords, 2, '.', '').' '.$unitMeasure.'</div>
			      <div class="nameTeacher">'.$value['fioTeacher'].'</div>
			      <div class = "checkDel" name = "checkRec"><input type = "checkbox" class = "boxDelRec" name = "'.$value['id'].'" id = "'.$value['id'].'""></div>
          </div>
        ';
      }

      echo('У данного преподавателям за указанный промежуток времени имеется '.$count.' записей проведенных им вебинаров<br><br>');
      echo('
	      <div id="dButton">
	          <input type="button" value = "УДАЛИТЬ" name="delRecords" class="btnDelRecordsClick">
	      </div>
      ');

      $numDivisions = 0;
      while ($allSize > 1000) {
      	$allSize = $allSize / 1000;
      	$numDivisions++;
      }
      switch ($numDivisions) {
      	case 0:
      		$unitMeasure = 'B';
      		break;
      	case 1:
      		$unitMeasure = 'KB';
      		break;
      	case 2:
      		$unitMeasure = 'MB';
      		break;
      	case 3:
      		$unitMeasure = 'GB';
      		break;
      	default:
      		$unitMeasure = 'Неизвестная величина';
      		break;
      }

      echo('Общий объем занимаемого места '.number_format($allSize, 2, '.', '').' '.$unitMeasure.'<br><br>');
      echo($strAll);

    } else {
      echo("Записей за указанный промежуток времени не имеется");
    }

?>