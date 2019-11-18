<?php

	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";

	$id_utente=$_REQUEST['id_utente'];
	$nome=$_REQUEST['nome'];
	$cognome=$_REQUEST['cognome'];
	$username=$_REQUEST['username'];
	
	$query="SELECT * FROM utenti WHERE username='$username' AND id_utente<>$id_utente";
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
		$rows = sqlsrv_has_rows( $result );
		if ($rows === true)
		{
			echo "<b style='color:red'>Nome utente gia in uso</b>";
			die();
		}
		else
		{
			$query2="UPDATE utenti SET nome='$nome',cognome='$cognome',username='$username' WHERE id_utente=$id_utente";
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
				echo "<b style='color:green'>Utente modificato</b>";
		}
	}
	
?>