<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
			
	$query="SELECT [id_produzione],[posizione],[eliminato],[dataOraCaricamento],[lotto],[finitura],utenti.username,codpan FROM produzione,utenti WHERE produzione.utenteCaricamento=utenti.id_utente";
	
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
		echo '<table id="myTableTabelleGestisciLinea">';
			echo '<tr class="TheaderTabelleGestisciLinea">';
				echo '<th>Id</th>';
				echo '<th>Codice pannello</th>';
				echo '<th>Finitura</th>';
				echo '<th>Angolo</th>';
				echo '<th>Lotto</th>';
				echo '<th>Data/ora caricamento</th>';
				echo '<th>Caricato da</th>';
				echo '<th>Posizione</th>';
				echo '<th>Eliminato</th>';
				echo '<th>Postazione</th>';
			echo '</tr>';
			while($row=sqlsrv_fetch_array($result))
			{
				echo '<tr>';
					echo '<td>'.$row['id_produzione'].'</td>';
					echo '<td>'.$row['codpan'].'</td>';
					echo '<td>'.$row['finitura'].'</td>';
					$codpan=$row['codpan'];
					echo '<td>'.getAngolo($conn,"$codpan").'</td>';
					echo '<td>'.$row['lotto'].'</td>';
					echo '<td>'.$row['dataOraCaricamento']->format('Y-m-d H:i:s').'</td>';
					echo '<td>'.$row['username'].'</td>';
					echo '<td>'.$row['posizione'].'</td>';
					if($row['eliminato']=="true")
						echo '<td style="color:red;font-weight:bold">V</td>';
					else
						echo '<td>X</td>';
					echo '<td>'.getPostazione($conn,$row['id_produzione']).'</td>';
				echo '</tr>';
			}
		echo "</table>";
	}
	
	function getPostazione($conn,$id_produzione)
	{
		$query="SELECT mAcqua,mRinforzi FROM produzione WHERE id_produzione=$id_produzione";
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
				if($row['mAcqua']=="false")
					return "Caricamento";
				if($row['mAcqua']=="true" && $row['mRinforzi']=="false")
					return "Rinforzi";
				if($row['mAcqua']=="true" && $row['mRinforzi']=="true")
					return "Incollaggio";
			}
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
					return "No";
				else
					return $row['ANG'];
			}
		}
	}

?>