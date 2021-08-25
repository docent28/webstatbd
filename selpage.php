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

    echo('selpage.php<br>');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <title>Статистика Webinar-ru</title>
</head>
<body>
    <form>
        <input type="button" value = "Все вебинары" name="webinars">
        <input type="button" value = "Преподаватель" name="teachers">
        <input type="button" value = "Удаление вебинаров" name="delWebinars">
        <input type="button" value = "Удаление записей" name="delRecords">
        <input type="button" value = "Общее" name="statistic">
    </form>
    <div id="contentBody">
    </div>
    <div id="contentTest">
    </div>
    <div id="delRecords">
    </div>
    <div id="loading" style="display: none">
        <p>Идет загрузка...</p>
    </div>
    <script type="text/javascript" src="js/showcontent.js"></script>
</body>
</html>