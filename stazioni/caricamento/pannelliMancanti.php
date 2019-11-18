<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$lotto=$_REQUEST['lotto'];
		
	$pannelliMancanti=array();
		
	$query="SELECT  pannello, SUM(qnt) AS qnt, finitura FROM lotti_pannelli WHERE lotto='$lotto' GROUP BY lotto,pannello,finitura";
	
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
		echo '<table id="myTablePannelliMancanti">';
			echo '<tr class="TheaderPannelliMancanti">';
				echo '<th>Pannello</th>';
				echo '<th>Pannelli mancanti</th>';
				echo '<th>Totale pannelli</th>';
				echo '<th>T</th>';
				echo '<th>P</th>';
				echo '<th>Ang</th>';
			echo '</tr>';
			while($row=sqlsrv_fetch_array($result))
			{
				echo '<tr>';
					echo '<td>'.$row['pannello'].'</td>';
					$pannelliMancantiEl=getPannelliMancanti($conn,$lotto,$row['pannello']);
					array_push($pannelliMancanti,$pannelliMancantiEl);
					echo '<td>'.$pannelliMancantiEl.'</td>';
					echo '<td>'.$row['qnt'].'</td>';
					echo '<td>'.getRinforziT($conn,$row['pannello']).'</td>';
					echo '<td>'.getRinforziP($conn,$row['pannello']).'</td>';
					echo '<td>'.getAngolo($conn,$row['pannello']).'</td>';
				echo '</tr>';
			}
		echo "</table>";
		$completato="false";
		$i=0;
		while($i<count($pannelliMancanti))
		{
			if($pannelliMancanti[$i]!=0)
			{
				$completato="false";
				break;
			}
			else
				$completato="true";
			$i++;
		}
		setCompletato($conn,$lotto,$completato);
		if($completato=="true")
			echo "<b style='display:none'>reload</b>";
	}
	
	function getPannelliMancanti($conn,$lotto,$pannello)
	{
		$query="SELECT qnt FROM qtaPannelli WHERE lotto='$lotto' AND codpan='$pannello'";
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
				return $row['qnt'];
			}
		}
	}
	
	function setCompletato($conn,$lotto,$completato)
	{
		$query="UPDATE lotti SET completato='$completato' WHERE lotto='$lotto'";
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
	}
	
	function getRinforziT($conn,$codpan)
	{
		$query="select qnt from n_rinforzi_T_2 WHERE codpas='$codpan'";
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
			$rows = sqlsrv_has_rows( $result );
			if($rows === TRUE)
			{
				while($row=sqlsrv_fetch_array($result))
					return "<b style='color:red'>".$row['qnt']."</b>";
			}
			else
				return 0;
		}
	}
	
	function getRinforziP($conn,$codpan)
	{
		$query="select qnt from n_rinforzi_P_2 WHERE codpas='$codpan'";
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
			$rows = sqlsrv_has_rows( $result );
			if($rows === TRUE)
			{
				while($row=sqlsrv_fetch_array($result))
					return "<b style='color:#0ED200'>".$row['qnt']."</b>";
			}
			else
				return 0;
		}
	}
	
	function getAngolo($conn,$codpan)
	{
		$query="SELECT ANG FROM pannelli_ruotati WHERE CODPAS='$codpan'";
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
				if($row['ANG']==0)
					return $row['ANG'];
				else
					return "<b style='background-color:yellow'>".$row['ANG']."</b>";
			}
		}
	}

?>