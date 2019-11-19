<?php
$serverName = 'web.azure.servizioglobale.it';
$connectionInfo=array("Database"=>"mi_incollaggio", "UID"=>"sa", "PWD"=>"Serglo123");

$conn = sqlsrv_connect($serverName,$connectionInfo);

if(!$conn)
{
	echo "<b style='color:red'>Connection with the database failed</b><br><br>";
	die(print_r(sqlsrv_errors(),TRUE));
}

?>