<?php
echo('delrecords.php<br>');
	$token = '548b24d95ee6ada9fd35e9c3298b0796';

// показываем записи, которые мы хотели бы удалить согласно указанных дат и ФИО преподавателя
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=	, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<br><br>
	<div>	
		УДАЛЕНИЕ ЗАПИСЕЙ
	<br><br>
		ОСВОБОЖДАЕТСЯ МЕСТО В ХРАНИЛИЩЕ
	<br><br>
		СТАТИСТИКА СОХРАНЯЕТСЯ
	<br><br>
		ПРОЦЕДУРА НЕОБРАТИМА
	<br><br>
		ВОССТАНОВЛЕНИЕ НЕВОЗМОЖНО
	</div>
<?php


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

asort($arrTeamMembers);

// создаем выпадающий список из фамилий преподавателей
$buffer = '<option value = 0>-----Выберите преподавателя-----</option>';
$count = 0;
foreach($arrTeamMembers as $key => $value) {
    $buffer .= '<option value = "'.$key.'">'.$value.'</option>';
    $count++;
}
$select = '<select id = "lecture" name = "idTeacher">'.$buffer.'</select>';
?>

<div class = "criterionTeacher">

	<?php
		echo('<div class = "selectTeacher">'.$select.'</div>');
	?>

	<div class = "dateTeacher">
	    <div id="dData">
	        <?php
	            $startDate = date("Y-m-d", strtotime("-60 day"));
	            $endDate = date("Y-m-d", strtotime("-14 day"));
	        ?>
	        с <input name = "instartdate" type="date" value="<?php echo($startDate) ?>"> по <input name = "inenddate" type="date" value="<?php echo($endDate) ?>">
	    </div>
	</div>

	<div id="dButton" class = "btnTeacher">
   	<input type="button" value = "СТАРТ" name="showRecords" class="btnRecordsClick">
	</div>
</div>


</body>
</html>
<!--
	Ушли на showdelrecords.php
-->