<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
			
	$lotto=$_REQUEST['lotto'];
			
	$query="SELECT * FROM laneManuali WHERE lotto LIKE '$lotto' ";
	
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
		$i=1;
		echo '<table id="myTableTabelleGestisciLinea">';
			echo '<tr class="TheaderTabelleGestisciLinea">';
				echo '<th>Lotto';
				echo "<select id='filtroLotto' onchange='filtroLotto()'>";
					if($lotto!='%')
					{
						echo "<option value='$lotto'>$lotto</option>";
					}
					echo "<option value='%'>Tutti</option>";
					if($lotto!='%')
						$query2="SELECT DISTINCT lotto FROM laneManuali WHERE lotto <> '$lotto' ORDER BY lotto ";
					else
						$query2="SELECT DISTINCT lotto FROM laneManuali ORDER BY lotto ";
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
						while($row2=sqlsrv_fetch_array($result2))
						{
							echo "<option value='".$row2['lotto']."'>".$row2['lotto']."</option>";
						}
					}
				echo "</select>";
				echo '</th>';
				echo '<th>Quantita</th>';
				echo '<th>Spessore</th>';
				echo '<th>Altezza</th>';
				echo '<th>Lunghezza</th>';
				echo '<th><input type="button" id="btnStampaMisureLanaAngoli" value="Stampa" onclick="stampaTutti()" /></th>';
			echo '</tr>';
			while($row=sqlsrv_fetch_array($result))
			{
				echo '<tr id="rowLanaAngoli'.$i.'">';
					echo '<td contenteditable>'.$row['lotto'].'</td>';
					echo '<td contenteditable>'.$row['qnt'].'</td>';
					echo '<td contenteditable>'.$row['SPESS'].'</td>';
					echo '<td contenteditable>'.$row['HALT'].'</td>';
					echo '<td contenteditable>'.(round($row['LUNG'],1)).'</td>';
					echo '<td><input type="button" id="btnEliminaMisureLanaAngoli" value="Elimina" onclick="eliminaRigaLana('.$i.')" /></td>';
				echo '</tr>';
				$i++;
			}
		echo "</table>";
	}

?>