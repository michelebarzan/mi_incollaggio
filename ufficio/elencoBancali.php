<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";

	set_time_limit(240);
			
	$query="SELECT * FROM bancali";
	
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
				echo '<th>Nome</th>';
				echo '<th>Lotto</th>';
				echo '<th>Numero</th>';
				echo '<th>Data/ora creazione</th>';
				echo '<th>Data/ora chiusura</th>';
				echo '<th>Numero pannelli</th>';
				echo '<th>Chiuso</th>';
			echo '</tr>';
			while($row=sqlsrv_fetch_array($result))
			{
				echo '<tr>';
					echo '<td>'.$row['id_bancale'].'</td>';
					echo '<td>'.$row['nome'].'</td>';
					echo '<td>'.$row['lotto'].'</td>';
					echo '<td>'.$row['numero'].'</td>';
					echo '<td>'.$row['dataOraCreazione']->format('Y-m-d H:i:s').'</td>';
					if($row['dataOraChiusura']==null || $row['dataOraChiusura']=='')
						echo '<td></td>';
					else
						echo '<td>'.$row['dataOraChiusura']->format('Y-m-d H:i:s').'</td>';
					echo '<td>'.getNumeroPannelli($conn,$row['id_bancale']).'</td>';
					if($row['chiuso']=="true")
						echo '<td>V</td>';
					else
						echo '<td>X</td>';
				echo '</tr>';
			}
		echo "</table>";
	}
	
	function getNumeroPannelli($conn,$id_bancale)
	{
		$query="SELECT COUNT(*) AS nPannelli FROM pannelli_prodotti WHERE bancale=$id_bancale";
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
				return $row['nPannelli'];
			}
		}
	}

?>