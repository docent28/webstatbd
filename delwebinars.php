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

echo('delwebinars.php<br>');
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
		УДАЛЕНИЕ ИНФОРМАЦИИ О ВЕБИНАРАХ
	<br><br>
		МЕСТО В ХРАНИЛИЩЕ НЕ ОСВОБОЖДАЕТСЯ
	<br><br>
		УНИЧТОЖАЕТСЯ СТАТИСТИКА О ПРОВЕДЕННЫХ МЕРОПРИЯТИЯХ
	<br><br>
		ПРОЦЕДУРА НЕОБРАТИМА
	<br><br>
		ВОССТАНОВЛЕНИЕ НЕВОЗМОЖНО
	</div>
</body>
</html>
