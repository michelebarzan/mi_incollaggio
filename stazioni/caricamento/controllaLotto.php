<?php

	/*include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$lotto=$_REQUEST['lotto'];
		
	$query="SELECT chiuso, completato FROM lotti WHERE lotto='$lotto'";
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
		echo "<br><br>Errore esecuzione query<br>Query: ".$query."<br>Errore: ";
		echo $testoErrore;
	}
	else
	{
		while($row=sqlsrv_fetch_array($result))
		{
			if($row['chiuso']=='true' || $row['completato']=='true')
			{
				echo "Lotto gia chiuso o completato";
			}
			else
				echo "ok";
		}
	}*/
	
	echo "ok";

?>