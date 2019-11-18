<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	//$queryRinforzi_T="SELECT COUNT(*) AS n_rinforzi_T ,lunghezza FROM rinforzi_T GROUP BY lunghezza";
	$queryRinforzi_T="SELECT COUNT(*) AS n_rinforzi_T FROM rinforzi_T ";
	$resultRinforzi_T=sqlsrv_query($conn,$queryRinforzi_T);
	if($resultRinforzi_T==FALSE)
	{
		$queryRinforzi_T=str_replace("'","*APICE*",$queryRinforzi_T);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$queryRinforzi_T','".$testoErrore."','".$_SESSION['Username']."')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$queryRinforzi_T=str_replace("*APICE*","'",$queryRinforzi_T);
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRinforzi_T."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($rowRinforzi_T=sqlsrv_fetch_array($resultRinforzi_T))
		{
			//echo 'PRELEVARE<br>Rinforzi T : '.$rowRinforzi_T['n_rinforzi_T'].'<br>Lunghezza : '.$rowRinforzi_T['lunghezza'];
			echo 'PRELEVARE<br>Rinforzi T : '.$rowRinforzi_T['n_rinforzi_T'];
		}
	}
	

?>