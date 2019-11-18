<?php

	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";

	$nome=$_REQUEST['nome'];
	$cognome=$_REQUEST['cognome'];
	$username=$_REQUEST['username'];
	
	$query="SELECT * FROM utenti WHERE username='$username'";
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
			$query3="SELECT (MAX(numero))+ 1 AS numero FROM utenti";
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
				echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
			else
			{
				while($row3=sqlsrv_fetch_array($result3))
				{
					if($row3['numero']<10)
						$query2="INSERT INTO utenti (nome,cognome,username,numero) VALUES ('$nome','$cognome','$username','0".$row3['numero']."')";
					else
						$query2="INSERT INTO utenti (nome,cognome,username,numero) VALUES ('$nome','$cognome','$username',".$row3['numero'].")";
				}
			
				//$query2="INSERT INTO utenti (nome,cognome,username,numero) VALUES ('$nome','$cognome','$username',$numero)";
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
					echo "<b style='color:green'>Utente inserito</b>";
			}
		}
	}
	
?>