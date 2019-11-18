<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
	
	echo '<table id="myTableTabelleGestisciLinea">';
		echo '<tr class="TheaderTabelleGestisciLinea">';
			echo '<th>Statistica</th>';
			echo '<th>Valore</th>';
		echo '</tr>';
	
		echo '<tr>';
			echo "<td>Media pannelli al giorno</td>";
			echo "<td>".getMediaPannelliAlGiorno($conn)."</td>";
		echo '</tr>';
		echo '<tr>';
			echo "<td>Media angoli al giorno</td>";
			echo "<td>".getMediaAngoliAlGiorno($conn)."</td>";
		echo '</tr>';
		echo '<tr>';
			echo "<td>Tempo medio produzione pannello</td>";
			echo "<td>".getMediaTempoProduzione($conn)."</td>";
		echo '</tr>';
		echo '<tr>';
			echo "<td>Tempo medio uscita pannello</td>";
			echo "<td>".getMediaTempoUscita($conn)."</td>";
		echo '</tr>';
		echo '<tr>';
			echo "<td>Record pannelli prodotti in un giorno</td>";
			echo "<td>".getMaxPannelliProdotti($conn)."</td>";
		echo '</tr>';
		
	echo "</table>";
	
	function getMediaPannelliAlGiorno($conn)
	{
		$query="SELECT AVG(qnt) AS media FROM pannelli_al_giorno";
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
				return $row['media'];
			}
		}
	}
	function getMaxPannelliProdotti($conn)
	{
		$query="SELECT MAX(qnt) AS max FROM pannelli_al_giorno";
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
				return $row['max'];
			}
		}
	}
	
	function getMediaAngoliAlGiorno($conn)
	{
		$query="SELECT AVG(qnt) AS media FROM angoli_al_giorno";
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
				return $row['media'];
			}
		}
	}
	function getMediaTempoProduzione($conn)
	{
		$tempi_produzione=array();
		$query="SELECT tempoProduzione FROM tempo_produzione WHERE tempoProduzione IS NOT NULL";
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
			if ($rows === true)
			{
				while($row=sqlsrv_fetch_array($result))
				{
					$ore_minuti_secondi=explode(":",$row['tempoProduzione']->format('H:i:s'));
					if($ore_minuti_secondi[0]==0 && $ore_minuti_secondi[1]<15)
					{
						$tot_secondi=($ore_minuti_secondi[0]*3600)+($ore_minuti_secondi[1]*60)+$ore_minuti_secondi[2];
						array_push($tempi_produzione,$tot_secondi);
					}
				}
				$tempi_produzione = array_filter($tempi_produzione);
				$media_secondi = array_sum($tempi_produzione)/count($tempi_produzione);
				$media_secondi=round($media_secondi);
				
				return gmdate("i:s", $media_secondi);
			}
		}
	}
	function getMediaTempoUscita($conn)
	{
		$tempiUscita=array();
		$query="SELECT tempo_uscita FROM uscita_pannelli";
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
			if ($rows === true)
			{
				while($row=sqlsrv_fetch_array($result))
				{
					$ore_minuti_secondi=explode(":",$row['tempo_uscita']->format('H:i:s'));
					if($ore_minuti_secondi[0]==0 && $ore_minuti_secondi[1]<6)
					{
						$tot_secondi=($ore_minuti_secondi[0]*3600)+($ore_minuti_secondi[1]*60)+$ore_minuti_secondi[2];
						array_push($tempiUscita,$tot_secondi);
					}
				}
				$tempiUscita = array_filter($tempiUscita);
				$media_secondi = array_sum($tempiUscita)/count($tempiUscita);
				$media_secondi=round($media_secondi);
				
				return gmdate("i:s", $media_secondi);
			}
		}
	}
	
?>