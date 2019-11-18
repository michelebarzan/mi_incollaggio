<?php
	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
		
	$lotto=$_REQUEST['lotto'];
	$qnt=$_REQUEST['qnt'];
	
	$codPan=$_REQUEST['codpan'];
	$codPan="+".substr($codPan,1);
	
	$query="SELECT SUM(lotti_pannelli.qnt) AS qnt FROM lotti_pannelli WHERE lotti_pannelli.lotto='$lotto' AND lotti_pannelli.pannello='$codPan'";
	
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
			//echo $row['qnt'];
			if($row['qnt']==0)
			{
				echo "Pannello $codPan finito nel lotto $lotto. Aggiungere #$qnt% pannelli al lotto?";
				die();
			}
			if($qnt<=$row['qnt'])
			{
				echo "ok";
				die();
			}
			if($qnt>$row['qnt'])
			{
				$diffQnt=$qnt-$row['qnt'];
				echo "Pannello $codPan finito nel lotto $lotto. Aggiungere #$diffQnt% pannelli al lotto?";
				die();
			}
		}
	}
?>