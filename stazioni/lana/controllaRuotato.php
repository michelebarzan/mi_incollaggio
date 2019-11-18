<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$codPan=$_REQUEST['codPan'];
	
	$codPan=substr($codPan,1);
	
	$query="SELECT ruotato FROM pannelli_ruotati WHERE CODPAS='".'+'."$codPan'";
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
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($row=sqlsrv_fetch_array($result))
		{
			if($row['ruotato']=='true')
			{
				echo "<div style='border:3px solid red;box-sizing: border-box;height:60px;line-height:60px;width:400px;overflow:hidden;margin-top:5px;display:inline-block;color:red;background:yellow;font-family:Exo,Arial;font-size:120%;font-weight:bold;'><input type='button' id='alert' value='' style='float:left;margin-left:20px;' />Attenzione ! Pannello ruotato</div>";
			}
		}
	}
	//echo $codPan;

?>