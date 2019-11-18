<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
			
	$query="SELECT * FROM correzione_lana ORDER BY SPESS,ANG_MIN";
	
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
		$i=0;
		echo '<table id="myTableTabelleGestisciLinea">';
			echo '<tr class="TheaderTabelleGestisciLinea">';
				echo '<th>Spessore lana</th>';
				echo '<th>Misura angolo iniziale (°)</th>';
				echo '<th>Misura angolo finale (°)</th>';
				echo '<th>Foglio stretto (mm)</th>';
				echo '<th>Foglio largo (mm)</th>';
				echo '<th>Azione</th>';
				echo '<th>Risultato</th>';
			echo '</tr>';
			while($row=sqlsrv_fetch_array($result))
			{
				$id_correzione_lana=$row['id_correzione_lana'];
				echo '<tr>';
					echo '<td id="SPESS'.$i.'" contenteditable>'.$row['SPESS'].'</td>';
					echo '<td id="ANG_MIN'.$i.'" contenteditable>'.$row['ANG_MIN'].'</td>';
					echo '<td id="ANG_MAX'.$i.'" contenteditable>'.$row['ANG_MAX'].'</td>';
					echo '<td id="latoStretto'.$i.'" contenteditable>'.$row['latoStretto'].'</td>';
					echo '<td id="latoLargo'.$i.'" contenteditable>'.$row['latoLargo'].'</td>';
					echo '<td><input type="button" id="btnModificaUtente" value="Modifica" onclick="modificaParametroLana('.$i.','.$id_correzione_lana.')" /><input type="button" id="btnModificaUtente" value="Elimina" style="color:red;margin-left:10px;" onclick="eliminaParametroLana('.$i.','.$id_correzione_lana.')" /></td>';
					echo '<td id="risultato'.$i.'" style="overflow:hidden;width:200px" ></td>';
				echo '</tr>';
				$i++;
			}
			echo '<tr>';
				echo '<td id="nuovoSPESS" contenteditable></td>';
				echo '<td id="nuovoANG_MIN" contenteditable></td>';
				echo '<td id="nuovoANG_MAX" contenteditable></td>';
				echo '<td id="nuovoLatoStretto" contenteditable></td>';
				echo '<td id="nuovoLatoLargo" contenteditable></td>';
				echo '<td><input type="button" id="btnInserisciUtente" value="Inserisci" onclick="inserisciParametroLana()" /></td>';
				echo '<td id="risultatoInserimento" style="overflow:hidden;width:200px" ></td>';
			echo '</tr>';
		echo "</table>";
	}

?>