<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
			
	$query="SELECT * FROM utenti";
	
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
				echo '<th>Id</th>';
				echo '<th>Nome</th>';
				echo '<th>Cognome</th>';
				echo '<th>Username</th>';
				echo '<th>Azione</th>';
				echo '<th></th>';
			echo '</tr>';
			while($row=sqlsrv_fetch_array($result))
			{
				echo '<tr>';
					echo '<td id="idUtente'.$i.'">'.$row['id_utente'].'</td>';
					echo '<td id="nomeUtente'.$i.'" contenteditable>'.$row['nome'].'</td>';
					echo '<td id="cognomeUtente'.$i.'" contenteditable>'.$row['cognome'].'</td>';
					echo '<td id="usernameUtente'.$i.'" contenteditable>'.$row['username'].'</td>';
					echo '<td><input type="button" id="btnModificaUtente" value="Modifica" onclick="modificaUtente('.$i.')" /></td>';
					echo '<td id="risultato'.$i.'" style="overflow:hidden;width:200px" ></td>';
				echo '</tr>';
				$i++;
			}
			echo '<tr>';
				echo '<td></td>';
				echo '<td id="nuovoNomeUtente" contenteditable></td>';
				echo '<td id="nuovoCognomeUtente" contenteditable></td>';
				echo '<td id="nuovoUsernameUtente" contenteditable></td>';
				echo '<td><input type="button" id="btnInserisciUtente" value="Inserisci" onclick="inserisciUtente()" /></td>';
				echo '<td id="risultatoInserimento" style="overflow:hidden;width:200px" ></td>';
			echo '</tr>';
		echo "</table>";
	}

?>