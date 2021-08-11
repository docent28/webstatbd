<?php
echo('vacating.php<br>');
	$token = '548b24d95ee6ada9fd35e9c3298b0796';
// удаляем записи, которые отмечены в таблице
?>

<?php
	$arrRecordsDeleted = json_decode($_POST["x"], false);

  // Удаляем запись по ID
  // Удалить онлайн-запись вебинара
  // https://help.webinar.ru/ru/articles/3151441-удалить-онлайн-запись-вебинара
	$options = array(
	    		'http' => array(
	        'header'  =>
	        "Content-Type: application/x-www-form-urlencoded\r\n" .
	        "x-auth-token: $token\r\n",
	        'method' => 'DELETE',
	    )
	);

	foreach ($arrRecordsDeleted as $key => $value) {
		$url = 'https://userapi.webinar.ru/v3/records/'.$value;
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
	}

	echo('Удаление записей произошло успешно');

?>
