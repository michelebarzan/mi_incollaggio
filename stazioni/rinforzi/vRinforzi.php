<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$codPan=$_REQUEST['codPan'];
	$id_produzione=$_REQUEST['id_produzione'];
	$mRinforzi="false";
	
	$codPan=substr($codPan,1);
	$codPan='+'.$codPan;
	
	$query="SELECT mRinforzi FROM produzione WHERE codPan='$codPan' AND vRinforzi='true' AND id_produzione=$id_produzione";
	//$query="SELECT mRinforzi FROM produzione WHERE codPan='$codPan' AND id_produzione=$id_produzione";
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
		while($row=sqlsrv_fetch_array($result))
		{
			$mRinforzi=$row['mRinforzi'];
		}
	}
	if($mRinforzi=="true")
	{
		$query2="UPDATE produzione SET vRinforzi='false',utenteRinforzi=".getUtente($conn,$_SESSION['Username'])." WHERE codPan='$codPan' AND vRinforzi='true' AND id_produzione=$id_produzione";
		$result2=sqlsrv_query($conn,$query2);
		if($result2==FALSE)
		{
			$query2=str_replace("'","*APICE*",$query2);
			$testoErrore=print_r(sqlsrv_errors(),TRUE);
			$testoErrore=str_replace("'","*APICE*",$testoErrore);
			$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
			$query2Errori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$query2','".$testoErrore."','".$_SESSION['Username']."')";
			$resultErrori=sqlsrv_query($conn,$queryErrori);
			$query2=str_replace("*APICE*","'",$query2);
			echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
			echo "ok";
	}
	else
		echo "err";
	
	function getUtente($conn,$username)
	{
		$query="SELECT id_utente FROM utenti WHERE username='$username'";
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
			while($row=sqlsrv_fetch_array($result))
			{
				return $row['id_utente'];
			}
		}
	}

?>