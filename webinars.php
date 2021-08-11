<?php
echo('webinars.php<br>');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>Общая статистика по ПРОШЕДШИМ вебинарам</p>
    <form method="POST" name = "frmwebinars" id="qwe">
        <div id = "frmWebinars">
            <div id="dData">
                <?php
                    $startDate = date("Y-m-d", strtotime("-1 day"));
                    $endDate = date("Y-m-d");
                ?>
                с <input name = "instartdate" type="date" value="<?php echo($startDate) ?>"> по <input name = "inenddate" type="date" value="<?php echo($endDate) ?>">
            </div>

            <div id="dButton">
                <input type="button" value = "СТАРТ" name="showDetails" class="btnWebinarsClick">
            </div>
        </div>
    </form>
</body>
</html>
<!--
   перешли на listwebinars.php
-->