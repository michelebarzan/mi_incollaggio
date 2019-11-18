<?php

	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";

	$id_correzione_lana=$_REQUEST['id_correzione_lana'];
	$SPESS=$_REQUEST['SPESS'];
	$ANG_MIN=$_REQUEST['ANG_MIN'];
	$ANG_MAX=$_REQUEST['ANG_MAX'];
	$latoStretto=$_REQUEST['latoStretto'];
	$latoLargo=$_REQUEST['latoLargo'];
	

	$query2="UPDATE correzione_lana SET ANG_MIN='$ANG_MIN', SPESS='$SPESS',ANG_MAX='$ANG_MAX',latoStretto='$latoStretto',latoLargo='$latoLargo' WHERE id_correzione_lana=$id_correzione_lana";
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
		echo "<b style='color:green'>Correzione lana modificata</b>";
		
	
?>