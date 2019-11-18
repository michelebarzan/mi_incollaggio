<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "connessioneParametri.php";
	include "Session.php";
	
	$connected = @fsockopen(getParametro($connParametri,$conn,'fileServer'),445);
	if($connected)
	{
		$connected2 = @fsockopen(getParametro($connParametri,$conn,'sqlServer'),1433);
		if($connected2)
		{
			$internet= "true";
			fclose($connected2);
			fclose($connected);
		}
		else
			$internet= "false";
	}
	else
		$internet= "false";
	
	echo $internet;
	
	//------------------------------------------------------------------------------------------------------------------------------------------------------------------
	function getParametro($connParametri,$conn,$parametro)
	{
		$query="SELECT valore FROM parametri WHERE parametro = '$parametro'";
		$result=sqlsrv_query($connParametri,$query);
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
				return $row['valore'];
			}
		}
	}
?>