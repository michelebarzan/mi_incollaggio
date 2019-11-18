<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	//$queryRinforzi_P="SELECT COUNT(*) AS n_rinforzi_P ,lunghezza FROM rinforzi_P GROUP BY lunghezza";
	$queryRinforzi_P="SELECT COUNT(*) AS n_rinforzi_P  FROM rinforzi_P ";
	$resultRinforzi_P=sqlsrv_query($conn,$queryRinforzi_P);
	if($resultRinforzi_P==FALSE)
	{
		$queryRinforzi_P=str_replace("'","*APICE*",$queryRinforzi_P);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$queryRinforzi_P','".$testoErrore."','".$_SESSION['Username']."')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$queryRinforzi_P=str_replace("*APICE*","'",$queryRinforzi_P);
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRinforzi_P."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($rowRinforzi_P=sqlsrv_fetch_array($resultRinforzi_P))
		{
			//echo 'PRELEVARE<br>Rinforzi P : '.$rowRinforzi_P['n_rinforzi_P'].'<br>Lunghezza : '.$rowRinforzi_P['lunghezza'];
			echo 'PRELEVARE<br>Rinforzi P : '.$rowRinforzi_P['n_rinforzi_P'];
		}
	}
	/*echo 'PRELEVARE<br>Rinforzi P : 2<br>Lunghezza : 300';*/

?>