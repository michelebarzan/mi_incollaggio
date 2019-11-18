<?php

	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";

	echo '<table id="myTable">';
		echo '<tr class="Theader">';
			echo '<th>Lotto</th>';
			echo '<th>Commessa</th>';
			echo '<th>Data importazione</th>';
			echo '<th>Chiuso</th>';
			echo '<th>Completato</th>';
			echo '<th>Producibile</th>';
		echo '</tr>';

		$queryRighe="SELECT lotti.* FROM lotti ORDER BY dataImportazione";
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
					echo '<td>'.$rowRighe['lotto'];
						
					echo '</td>';
					echo '<td>'.$rowRighe['commessa'].'</td>';
					$string=$rowRighe["dataImportazione"]->format('Y-m-d H:i:s');
					echo '<td>'.$string.'</td>';
					if($rowRighe['chiuso']=='true')
						echo '<td style="color:green">V</td>';
					else
						echo '<td style="color:red">X</td>';
					if($rowRighe['completato']=='true')
						echo '<td style="color:green">V</td>';
					else
						echo '<td style="color:red">X</td>';
					if($rowRighe['producibile']=='true')
						echo '<td style="color:green">V</td>';
					else
						echo '<td style="color:red">X</td>';
				echo '</tr>';
			}
		}
	echo '</table><br><br><br><br>';
	
?>