<?php

	//$serverNameParametri='web.azure.servizioglobale.it';
	$serverNameParametri='192.168.1.1';
	$connectionInfoParametri=array("Database"=>"parametri", "UID"=>"sa", "PWD"=>"Serglo123");
	$connParametri = sqlsrv_connect($serverNameParametri,$connectionInfoParametri);

?>