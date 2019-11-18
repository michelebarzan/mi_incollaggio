<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$lotto=$_REQUEST['lotto'];
	$codPan=$_REQUEST['codPan'];
	$codPan="+".substr($codPan,1);
		
	$query="SELECT finitura, SUM(qnt) AS qnt FROM lotti_pannelli WHERE lotto='$lotto' AND pannello='$codPan' GROUP BY finitura";
	
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
			
			if(getPannelliMancanti($conn,"$lotto",$row['qnt'],"$codPan")==0)
			{
				echo "errore";
				die();
			}
			else
			{
				echo $row['finitura'];
				die();
			}
		}
		echo "errore";
	}
	
	function getPannelliMancanti($conn,$lotto,$tot,$codPan)
	{
		$query="SELECT qnt FROM qtaPannelli WHERE lotto='$lotto' AND codpan='$codPan'";
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
				$qnt=$row['qnt'];
				//return $tot-$qnt;
				return $qnt;
			}
			//return $tot;
		}
	}

?>