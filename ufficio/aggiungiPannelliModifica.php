<?php

	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";

	$lotto=$_REQUEST['lotto'];
	$finitura=$_REQUEST['finitura'];
	$codpan=$_REQUEST['codpan'];
	$codpan=substr($codpan,1);
	$codpan='+'.$codpan;
	$qnt=$_REQUEST['qnt'];
	
	$query="SELECT qnt FROM lotti_pannelli WHERE lotto='$lotto' AND pannello='$codpan' AND finitura='$finitura'";
	//echo $codpan;
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
			while($row=sqlsrv_fetch_array($result))
			{
				$qnt2=$row['qnt']+$qnt;
			}
			$query2="UPDATE lotti_pannelli SET qnt=$qnt2 WHERE lotto='$lotto' AND pannello='$codpan' AND finitura='$finitura'";
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
			{
				if($qnt==1)
					echo "$qnt pannello $codpan e stato aggiunto al lotto $lotto";
				else
					echo "$qnt pannelli $codpan sono stati aggiunti al lotto $lotto";
			}
		}
		else
		{
			$query2="INSERT INTO lotti_pannelli (lotto,pannello,qnt,finitura) VALUES ('$lotto','$codpan',$qnt,'$finitura')";
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
			{
				if($qnt==1)
					echo "$qnt pannello $codpan e stato aggiunto al lotto $lotto";
				else
					echo "$qnt pannelli $codpan sono stati aggiunti al lotto $lotto";
			}
		}
	}		
?>