<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$query="UPDATE lotti SET chiuso='false', completato='false' WHERE lotti.lotto IN (SELECT DISTINCT produzione.lotto FROM produzione)";
	//echo $query;
	//return $query;
	
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
		echo "Errore esecuzione query. Consulta l' ammministratore";
		die();
	}
	else
	{
		$query2="DELETE produzione FROM produzione";
	
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
			echo "Errore esecuzione query. Consulta l' ammministratore";
			die();
		}
		else
		{
			echo "Linea svuotata";
		}
	}

?>
