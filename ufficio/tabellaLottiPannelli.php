<?php

	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";

	$lotto=$_REQUEST['lotto'];
	
	echo '<table id="myTable">';
		echo '<tr class="Theader">';
			echo '<th>Lotto';
				echo "<select id='filtroLotto' onchange='filtroLotto()'>";
					if($lotto!='%')
					{
						echo "<option value='$lotto'>$lotto</option>";
					}
					echo "<option value='%'>Tutti</option>";
					if($lotto!='%')
						$query="SELECT DISTINCT lotto FROM lotti_pannelli WHERE lotto <> '$lotto' ORDER BY lotto ";
					else
						$query="SELECT DISTINCT lotto FROM lotti_pannelli ORDER BY lotto ";
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
							echo "<option value='".$row['lotto']."'>".$row['lotto']."</option>";
						}
					}
				echo "</select>";
			echo '</th>';
			echo '<th>Pannello</th>';
			echo '<th>Quantita</th>';
		echo '</tr>';

		$queryRighe="SELECT lotti_pannelli.* FROM lotti_pannelli WHERE lotto LIKE '$lotto'";
		$resultRighe=sqlsrv_query($conn,$queryRighe);
		if($resultRighe==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			$i=0;
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				$i++;
				echo '<tr>';
					echo '<td>'.$rowRighe['lotto'].'</td>';
					echo '<td>'.$rowRighe['pannello'].'</td>';
					echo '<td>'.$rowRighe['qnt'].'</td>';
				echo '</tr>';
			}
		}
	echo '</table><br><br><br><br>';
	
?>