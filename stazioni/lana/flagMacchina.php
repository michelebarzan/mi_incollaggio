<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	//$codPan=$_REQUEST['codPan'];
	
	//$codPan=substr($codPan,1);
	//$codPan='+'.$codPan;
	
	$query3="UPDATE produzione SET mLana='true',dataOraLana=GETDATE() WHERE produzione.id_produzione=(SELECT id_produzione FROM Dtagli)";
	$result3=sqlsrv_query($conn,$query3);
	if($result3==FALSE)
	{
		$query3=str_replace("'","*APICE*",$query3);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$query3','".$testoErrore."','".$_SESSION['Username']."')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$query3=str_replace("*APICE*","'",$query3);
		echo "<br><br>Errore esecuzione query<br>Query: ".$query3."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		$query2="INSERT INTO [dbo].[log_tagli]
           ([dataOra]
           ,[utente]
           ,[note]
           ,[id_produzione]
           ,[codpan]) SELECT getDate(),'','forza lavorazione macchina',id_produzione,codpas FROM Dtagli";
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
			echo "Lavorazione macchina forzata";
	}
?>