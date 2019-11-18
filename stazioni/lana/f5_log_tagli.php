<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$id_produzione=$_REQUEST['id_produzione'];
	$codpan=substr($codpan,1);
	$codpan='+'.$codpan;
	
	$query2="INSERT INTO [dbo].[log_tagli]
           ([dataOra]
           ,[utente]
           ,[note]
           ,[id_produzione]
           ,[codpan])
     VALUES (getDate(),'".$_SESSION['Username']."','premuto F5/uscito dalla pagina',$id_produzione,'$codpan')";
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
		echo "F5/uscita dalla pagina registrato";
?>