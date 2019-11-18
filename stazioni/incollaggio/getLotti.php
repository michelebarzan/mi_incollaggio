<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$codPan=$_REQUEST['codpan'];
	$codPan="+".substr($codPan,1);
	
	creaEriempiTabella($conn,$codPan);
	
	function creaEriempiTabella($conn,$codPan)
	{
		echo '<table id="myTableElencoLotti">';
		echo '<tr class="Theader">';
			creaHeader($conn);
		echo '</tr>';
			riempiTabella($conn,$codPan);
		echo '</table>';
	}

	//---------------------------------------------------------------------------------------------------------------------------------------------------------------

	function creaHeader($conn)
	{
		echo '<th style="color:#666f77;font-family:arial;font-size:100%;font-weight:bold;">Lotto</th>';
		echo '<th style="color:#666f77;font-family:arial;font-size:100%;font-weight:bold;">Chiuso</th>';
		echo '<th style="color:#666f77;font-family:arial;font-size:100%;font-weight:bold;">Completato</th>';
	}
	
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------

	function riempiTabella($conn,$codPan)
	{
		$queryRighe="SELECT lotti.* FROM lotti WHERE producibile='true' AND chiuso='false' AND lotti.lotto IN (SELECT lotti_pannelli.lotto FROM lotti_pannelli WHERE pannello='$codPan') ORDER BY dataImportazione DESC";
		$resultRighe=sqlsrv_query($conn,$queryRighe);
		if($resultRighe==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			$j=0;
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				echo '<tr id="rigaLotto'.$j.'" onclick=selezionaLotto("'.$j.'")>';
					echo '<td id="colonnaLotto'.$j.'" class="colonnaLottoScaricamentoManuale">'.$rowRighe['lotto'].'</td>';
					if($rowRighe['chiuso']=='true')
						echo '<td style="color:green">V</td>';
					else
						echo '<td style="color:red">X</td>';
					if($rowRighe['completato']=='true')
						echo '<td id="colonnaCompletato'.$j.'" style="color:green">V</td>';
					else
						echo '<td id="colonnaCompletato'.$j.'" style="color:red">X</td>';
				echo '</tr>';
				$j++;
			}
		}
	}


?>