<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$valori=array();
	$lunghezza=array();
	
	$query="SELECT * FROM Pc_griT1";
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
		echo '<div style="margin-top:10px;box-sizing: border-box;width:90%;margin-left:5%;border-left: 1px solid #ddd;border-right: 1px solid #ddd;border-top: 1px solid #ddd;font-weight:bold;font-size:150%;color:gray;font-family:Arial;height:50px;line-height:50px" >Prelievo rinforzi</div>';
		echo '<table id="myTableRinforzi">';
			while($row=sqlsrv_fetch_array($result))
			{
				array_push($valori,$row['min']);
				array_push($lunghezza,$row['lunghezza']);
			}
			$i=0;
			echo '<tr>';
			while($i<(count($valori)/2))
			{
				if($lunghezza[$i]!=NULL)
					echo '<td style="background:yellow">'.$valori[$i].'</td>';
				else
					echo '<td>'.$valori[$i].'</td>';
				$i++;
			}
			echo '</tr>';
			echo '<tr>';
			while($i<count($valori))
			{
				if($lunghezza[$i]!=NULL)
					echo '<td style="background:yellow">'.$valori[$i].'</td>';
				else
					echo '<td>'.$valori[$i].'</td>';
				$i++;
			}
			echo '</tr>';
		echo '</table>';
	}

?>