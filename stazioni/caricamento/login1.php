<?php

	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	if(!$conn)
		echo "connessione fallita";

	$numero=$_REQUEST['numero'];
	$query="SELECT username, COUNT(*) FROM utenti WHERE numero='$numero' GROUP BY username ";
	
	$result=sqlsrv_query($conn,$query);
	if($result==FALSE)
	{
		$query=str_replace("'","*APICE*",$query);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$query','".$testoErrore."','login')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$query=str_replace("*APICE*","'",$query);
		echo "<br><br>Errore esecuzione query<br>Query: ".$query."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($row=sqlsrv_fetch_array($result))
		{
			$username=$row['username'];
			session_start();
			$_SESSION['Username']=$username;
			echo "<b style='color:green'>Login corretto</b>";
			die();
		}
	}
	echo "Login fallito";

?>