<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$id_produzione=$_REQUEST['id_produzione'];
	
	$query="SELECT mAcqua,mRinforzi FROM produzione WHERE id_produzione=$id_produzione";
	
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
			if($row['mAcqua']=="true" && $row['mRinforzi']=="false")
			{
				echo "Il pannello si trova alla stazione rinforzi";
				die();
			}
			if($row['mAcqua']=="true" && $row['mRinforzi']=="true")
			{
				echo "Il pannello si trova alla stazione incollaggio";
				die();
			}
			getAcqua($conn,$id_produzione);
		}
	}
	
	function getAcqua($conn,$id_produzione)
	{
		$query="SELECT TOP(1) id_produzione FROM produzione WHERE mAcqua='false' ORDER BY posizione  ";
	
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
				if($row['id_produzione']==$id_produzione)
					echo "Il pannello si trova alla stazione acqua";
				else
					echo "Il pannello si trova alla stazione caricamento";
			}
		}
	}
	
	//echo $id_produzione;
?>