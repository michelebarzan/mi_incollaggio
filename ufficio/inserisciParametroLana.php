<?php

	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";

	$SPESS=$_REQUEST['SPESS'];
	$ANG_MIN=$_REQUEST['ANG_MIN'];
	$ANG_MAX=$_REQUEST['ANG_MAX'];
	$latoStretto=$_REQUEST['latoStretto'];
	$latoLargo=$_REQUEST['latoLargo'];
	
	$query2="INSERT INTO correzione_lana (SPESS,ANG_MIN,ANG_MAX,latoStretto,latoLargo) VALUES ($SPESS,$ANG_MIN,$ANG_MAX,$latoStretto,$latoLargo)";
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		$query2=str_replace("'","*APICE*",$query2);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$query2','".$testoErrore."','".$_SESSION['Username']."')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$query2=str_replace("*APICE*","'",$query2);
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
		echo "<b style='color:green'>Correzione lana inserita</b>";

	
?>