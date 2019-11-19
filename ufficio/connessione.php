<?php
$serverName = '192.168.1.1';
//$serverName='web.azure.servizioglobale.it';
$connectionInfo=array("Database"=>"mi_incollaggio", "UID"=>"sa", "PWD"=>"Serglo123");
$conn = sqlsrv_connect($serverName,$connectionInfo);
?>