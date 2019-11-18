<?php
	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$codPan=$_REQUEST['codPan'];
	$id_produzione=$_REQUEST['id_produzione'];
	
	$codPan='+'.substr($codPan,1);
	
	$query="SELECT mRinforzi FROM produzione WHERE codpan='$codPan' AND id_produzione=$id_produzione";
	//echo $query;
	
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
			echo $row['mRinforzi'];
		}
	}
?>