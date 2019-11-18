<?php
	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
		
	$lotto=$_REQUEST['lotto'];
	
	$codPan=$_REQUEST['codpan'];
	$codPan="+".substr($codPan,1);
	
	$query="SELECT lotti_pannelli.* FROM lotti_pannelli WHERE lotti_pannelli.lotto='$lotto' AND lotti_pannelli.pannello='$codPan'";
	
	$result=sqlsrv_query($conn,$query);
	if($result==FALSE)
	{
		$query=str_replace("'","*APICE*",$query);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$query','".$testoErrore."','".$_SESSION['Username']."')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$query=str_replace("*APICE*","'",$query);
		echo "<br><br>Errore esecuzione query<br>Query: ".$query."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		$rows = sqlsrv_has_rows( $result );
		if ($rows === true)
		{
			echo "ok";
		}
		else
		{
			echo "Pannello $codPan non presente nel lotto $lotto";
		}
	}
?>