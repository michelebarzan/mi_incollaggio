<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$codPan=$_REQUEST['codPan'];
	$lotto=$_REQUEST['lotto'];
	$ruotato=$_REQUEST['ruotato'];
	$finitura=$_REQUEST['finitura'];
	
	$codPan="+".substr($codPan,1);
	$lotto=substr($lotto,1);
	
	$dataOraCaricamento = date('d/m/Y h:i:s', time());
	
	/*$q="SELECT * FROM produzione WHERE codPan='$codPan' AND lotto='$lotto'";
	$r=sqlsrv_query($conn,$q);
	if($r==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$q."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		$rows = sqlsrv_has_rows( $r );
		if ($rows === true)
		{
			echo "Pannello '$codPan' del lotto '$lotto' gia caricato";
			die();
		}
	}*/
	
	$query="SELECT MAX(posizione) AS posizione FROM produzione";
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
			$posizione=$row['posizione']+1;
		}
	}
	$utente=getUtente($conn,$_SESSION['Username']);
	$queryIns="INSERT INTO produzione (posizione,codpan,ruotato,mCaricamento,dataOraCaricamento,lotto,mRinforzi,mLana,mIncollaggio,vRinforzi,vLana,vIncollaggio,mAcqua,vRinforzi1,mRinforzi1,eliminato,finitura,utenteCaricamento) VALUES ($posizione,'$codPan','$ruotato','true','$dataOraCaricamento','$lotto','false','false','false','false','false','false','false','false','false','false','$finitura',$utente)";
	//da sostituire quando ci saranno le macchine
	//$queryIns="INSERT INTO produzione (posizione,codpan,ruotato,mCaricamento,dataOraCaricamento,lotto,mRinforzi,mLana,mIncollaggio,vRinforzi,vLana,vIncollaggio,mAcqua) VALUES ($posizione,'$codPan','$ruotato','true','$dataOraCaricamento','$lotto','false','false','false','false','false','false','true')";
	//echo $queryIns;
	
	$resultIns=sqlsrv_query($conn,$queryIns);
	if($resultIns==FALSE)
	{
		$queryIns=str_replace("'","*APICE*",$queryIns);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$queryIns','".$testoErrore."','".$_SESSION['Username']."')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$queryIns=str_replace("*APICE*","'",$queryIns);
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryIns."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
		echo "Pannello caricato";
	

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