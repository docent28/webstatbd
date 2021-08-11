<?php
echo('realdelrecords.php<br>');
	$token = '548b24d95ee6ada9fd35e9c3298b0796';
// запрашиваем подтверждение на удаление записей, ID которых было передано методом POST
?>
<?php
	$arrRecordsDeleted = json_decode($_POST["x"], false);

	if (count($arrRecordsDeleted) > 0) {
		echo('Процесс удаления необратим.<br>');
		echo('В результате освободится место в хранилище. Статистика о проведенных мероприятиях сохранится.<br>');
		echo('Вы уверены, что хотите удалить ранее отмеченные записи? Процесс необратим.<br>');

		echo('
			<div class = "delQuery">
				<div>
					<input type="button" value = "Уверен, УДАЛИТЬ" name="delYes" class="btnDelYes">
				</div>
				<div>
					<input type="button" value = "ОТМЕНИТЬ" name="delCancel" class="btnDelCancel">
				</div>
			<div>
			');
	} else {
		echo('Вы не выбрали ни одной записи для удаления');
	}

?>