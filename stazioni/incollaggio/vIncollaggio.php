<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$codPan=$_REQUEST['codPan'];
	$id_produzione=$_REQUEST['id_produzione'];
	
	$codPan=substr($codPan,1);
	$codPan='+'.$codPan;
	
	$bancale=$_REQUEST['bancale'];
	
	$query3="SELECT mLana FROM produzione WHERE id_produzione=$id_produzione";
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
		while($row3=sqlsrv_fetch_array($result3))
		{
			if($row3['mLana']=="true")
			{
				$dataOraIncollaggio = date('d/m/Y h:i:s', time());
				$query2="UPDATE produzione SET vIncollaggio='false',mIncollaggio='true',dataOraIncollaggio='$dataOraIncollaggio',bancale=$bancale,utenteIncollaggio=".getUtente($conn,$_SESSION['Username'])." WHERE codPan='$codPan' AND vIncollaggio='true' AND id_produzione=$id_produzione";
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
				{
					pannelli_prodotti($conn,$id_produzione);
				}
			}
			else
				echo "La macchina della lana non ha ancora terminato le lavorazioni";
		}
	}
	
	
	
	function pannelli_prodotti($conn,$id_produzione)
	{
		$query="INSERT INTO [dbo].[pannelli_prodotti]([id_produzione],[posizione],[codpan],[ruotato],[mCaricamento],[vRinforzi],[vLana],[vIncollaggio],[mRinforzi],[mLana],[mIncollaggio],[eliminato],[dataOraCaricamento],[dataOraRinforzi],[dataOraLana],[dataOraIncollaggio],[lotto],[bancale],[mAcqua],[dataOraAcqua],[mRinforzi1],[vRinforzi1],[finitura],[utenteCaricamento],[dataOraRinforzi1],[utenteRinforzi],[utenteRinforzi1],[utenteLana],[utenteIncollaggio],[dataProduzione]) SELECT produzione.*,getDate() FROM produzione WHERE id_produzione=$id_produzione";
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
			svuotaProduzione($conn,$id_produzione);
	}
	
	function svuotaProduzione($conn,$id_produzione)
	{
		$query="DELETE produzione FROM produzione WHERE id_produzione=$id_produzione";
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
			echo "ok";
	}
	
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