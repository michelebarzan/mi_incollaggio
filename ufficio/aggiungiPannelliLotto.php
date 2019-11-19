<?php

	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";

	$lotto=$_REQUEST["lotto"];

	echo '<table id="myTableManagement">';
		echo '<tr>';
			echo '<th>Lotto<select id="aggiungiPannelliLottoFiltroLotto" onchange="aggiungiPannelliLotto(this.value)">';
				if($lotto=="%")
					echo "<option value='%'>Tutti</option>";
				else
				{
					echo "<option value='".$lotto."'>".$lotto."</option>";
					echo "<option value='%'>Tutti</option>";
				}
				$queryFiltroLotto="SELECT distinct lotti.lotto FROM lotti WHERE lotto<>'$lotto' ORDER BY lotto";
				$resultFiltroLotto=sqlsrv_query($conn,$queryFiltroLotto);
				if($resultFiltroLotto==FALSE)
				{
					die("error");
				}
				else
				{
					while($rowFiltroLotto=sqlsrv_fetch_array($resultFiltroLotto))
					{
						echo "<option value='".$rowFiltroLotto['lotto']."'>".$rowFiltroLotto['lotto']."</option>";
					}
				}
			echo '</select></th>';
			echo '<th>Commessa</th>';
			echo '<th>Completato</th>';
			echo '<th>Codpan</th>';
			echo '<th>Finitura</th>';
			echo '<th>Quantita</th>';
			echo '<th>Azione<input type="button" id="btnChiudiTabelleManagement" value="X" onclick="chiudiTabelleManagement()" /></th>';
		echo '</tr>';

		$queryRighe="SELECT lotti.* FROM lotti WHERE lotto like '$lotto' ORDER BY dataImportazione";
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
				echo '<tr>';
					echo '<td id="aggiungiPannelli'.$i.'">'.$rowRighe['lotto'].'</td>';
					echo '<td>'.$rowRighe['commessa'].'</td>';
					if($rowRighe['completato']=='true')
						echo '<td style="color:green">V</td>';
					else
						echo '<td style="color:red">X</td>';
					echo '<td><input type="text" id="aggiungiPannelliCodpan'.$i.'" class="aggiungiPannelliCodpan" value="+K4PN" /></td>';
					echo '<td><input type="text" id="aggiungiPannelliFinitura'.$i.'" class="aggiungiPannelliCodpan" /></td>';
					echo '<td><input type="number" id="aggiungiPannelliQnt'.$i.'" class="aggiungiPannelliQnt" /></td>';
					echo '<td><input type="button" id="btnAggiungiPannelliModifica" value="Aggiungi" onclick="aggiungiPannelliModifica('.$i.')" /></td>';
				echo '</tr>';
				$i++;
			}
		}
	echo '</table>';
	
?>