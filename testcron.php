<?php
$servername = "localhost";
$database = "docent28_tstcrn";
$username = "docent28_tstcrn";
$password = "Ntyfc1967!";
// Устанавливаем соединение
$conn = mysqli_connect($servername, $username, $password, $database);
// Проверяем соединение
if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
}

for ($i=0; $i < 10; $i++) { 
	$name = "'user.$i'";
	$datestart = "'2021-08-04'";
	$dateend = "'2021-08-19'";
	$iduser = 1000 + $i;
	$sql = "INSERT INTO tstcrn (name, datestart, dateend, iduser) VALUES ($name, $datestart, $dateend, $iduser)";
	if (!mysqli_query($conn, $sql)) {
	      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
 } 

mysqli_close($conn);


?>