<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";

	set_time_limit(240);
			
	$lotto=$_REQUEST['lotto'];
	$dataOra=$_REQUEST['dataOra'];
	
	$codpan=$_REQUEST['codpan'];
	if($codpan!="%")
		$codpan='+'.substr($codpan,1);
			
	if($dataOra!="%")
	{		
		//$dataOra=date_create_from_format('Y-m-d H:i:s', $dataOra);
		//echo "d:".$dataOra->format('Y');
		$dataOra2 = DateTime::createFromFormat("Y-m-d", $dataOra);
		//echo $dataOra->format("Y");
		$query="SELECT TOP(1000) pannelli_prodotti.*,bancali.nome as bancale,dataOraIncollaggio-dataOraCaricamento AS tempoProduzione FROM pannelli_prodotti,bancali WHERE pannelli_prodotti.bancale=bancali.id_bancale AND pannelli_prodotti.lotto LIKE '$lotto' AND pannelli_prodotti.codpan LIKE '$codpan' AND YEAR(pannelli_prodotti.dataOraCaricamento) = '".$dataOra2->format('Y')."' AND MONTH(pannelli_prodotti.dataOraCaricamento) = '".$dataOra2->format('m')."' AND DAY(pannelli_prodotti.dataOraCaricamento) = '".$dataOra2->format('d')."' ORDER BY id_produzione DESC";
		//echo $query;
	}
	else
		$query="SELECT TOP(1000) pannelli_prodotti.*,bancali.nome as bancale,dataOraIncollaggio-dataOraCaricamento AS tempoProduzione FROM pannelli_prodotti,bancali WHERE pannelli_prodotti.bancale=bancali.id_bancale AND pannelli_prodotti.lotto LIKE '$lotto' AND pannelli_prodotti.codpan LIKE '$codpan' ORDER BY id_produzione DESC";

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
				echo '<th style="text-align:center;padding-left:5px;padding-right:5px">Id</th>';
				echo '<th style="text-align:center;padding-left:5px;padding-right:5px">Id produzione</th>';
				
				echo '<th style="text-align:center;padding-left:5px;padding-right:5px">Codpan';
					echo "<select id='filtroCodpanPannelliProdotti' class='filtriPannelliProdotti' onchange='filtroCodpan()'>";
						if($codpan!='%')
						{
							echo "<option value='$codpan'>$codpan</option>";
						}
						echo "<option value='%'>Tutti</option>";
						if($codpan!='%')
							$queryCodpan="SELECT DISTINCT codpan FROM pannelli_prodotti WHERE codpan <> '$codpan' ORDER BY codpan ";
						else
							$queryCodpan="SELECT DISTINCT codpan FROM pannelli_prodotti ORDER BY codpan ";
						$resultCodpan=sqlsrv_query($conn,$queryCodpan);
						if($resultCodpan==FALSE)
						{
							$queryCodpan=str_replace("'","*APICE*",$queryCodpan);
							$testoErrore=print_r(sqlsrv_errors(),TRUE);
							$testoErrore=str_replace("'","*APICE*",$testoErrore);
							$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
							$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$queryCodpan','".$testoErrore."','".$_SESSION['Username']."')";
							$resultErrori=sqlsrv_query($conn,$queryErrori);
							$queryCodpan=str_replace("*APICE*","'",$queryCodpan);
							echo "<br><br>Errore esecuzione query<br>Query: ".$queryCodpan."<br>Errore: ";
							die(print_r(sqlsrv_errors(),TRUE));
						}
						else
						{
							while($rowCodpan=sqlsrv_fetch_array($resultCodpan))
							{
								echo "<option value='".$rowCodpan['codpan']."'>".$rowCodpan['codpan']."</option>";
							}
						}
					echo "</select>";
				echo '</th>';
				echo '<th style="text-align:center;padding-left:5px;padding-right:5px">Finitura</th>';
				echo '<th style="text-align:center;padding-left:5px;padding-right:5px">Angolo</th>';
				
				echo '<th style="text-align:center;padding-left:5px;padding-right:5px">Lotto';
					echo "<select id='filtroLottoPannelliProdotti' class='filtriPannelliProdotti' onchange='filtroLotto()'>";
						if($lotto!='%')
						{
							echo "<option value='$lotto'>$lotto</option>";
						}
						echo "<option value='%'>Tutti</option>";
						if($lotto!='%')
							$queryLotto="SELECT DISTINCT lotto FROM pannelli_prodotti WHERE lotto <> '$lotto' ORDER BY lotto ";
						else
							$queryLotto="SELECT DISTINCT lotto FROM pannelli_prodotti ORDER BY lotto ";
						$resultLotto=sqlsrv_query($conn,$queryLotto);
						if($resultLotto==FALSE)
						{
							$queryLotto=str_replace("'","*APICE*",$queryLotto);
							$testoErrore=print_r(sqlsrv_errors(),TRUE);
							$testoErrore=str_replace("'","*APICE*",$testoErrore);
							$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
							$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$queryLotto','".$testoErrore."','".$_SESSION['Username']."')";
							$resultErrori=sqlsrv_query($conn,$queryErrori);
							$queryLotto=str_replace("*APICE*","'",$queryLotto);
							echo "<br><br>Errore esecuzione query<br>Query: ".$queryLotto."<br>Errore: ";
							die(print_r(sqlsrv_errors(),TRUE));
						}
						else
						{
							while($rowLotto=sqlsrv_fetch_array($resultLotto))
							{
								echo "<option value='".$rowLotto['lotto']."'>".$rowLotto['lotto']."</option>";
							}
						}
					echo "</select>";
				echo '</th>';
				
				echo '<th style="text-align:center;padding-left:5px;padding-right:5px">Data/ora';
					echo "<select id='filtroDataOraPannelliProdotti' class='filtriPannelliProdotti' onchange='filtroDataOra()'>";
						if($dataOra!='%')
						{
							$dataOra3 = DateTime::createFromFormat("Y-m-d", $dataOra);
							echo "<option value='$dataOra'>$dataOra</option>";
						}
						echo "<option value='%'>Tutti</option>";
						//if($dataOra!='%')
						//	$queryDataOra="SELECT DISTINCT DATEPART(year, dataOraCaricamento) AS anno, DATEPART(month, dataOraCaricamento) AS mese, DATEPART(day, dataOraCaricamento) AS giorno FROM pannelli_prodotti WHERE DATEPART(year, dataOraCaricamento) <> ".$dataOra3->format('Y')." AND DATEPART(month, dataOraCaricamento) <> ".$dataOra3->format('m')." AND DATEPART(day, dataOraCaricamento) <> ".$dataOra3->format('d')." ORDER BY dataOraCaricamento ";
						//else
							$queryDataOra="SELECT DISTINCT DATEPART(year, dataOraCaricamento) AS anno, DATEPART(month, dataOraCaricamento) AS mese, DATEPART(day, dataOraCaricamento) AS giorno FROM pannelli_prodotti ORDER BY DATEPART(year, dataOraCaricamento),DATEPART(month, dataOraCaricamento), DATEPART(day, dataOraCaricamento)";
						$resultDataOra=sqlsrv_query($conn,$queryDataOra);
						if($resultDataOra==FALSE)
						{
							$queryDataOra=str_replace("'","*APICE*",$queryDataOra);
							$testoErrore=print_r(sqlsrv_errors(),TRUE);
							$testoErrore=str_replace("'","*APICE*",$testoErrore);
							$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
							$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$queryDataOra','".$testoErrore."','".$_SESSION['Username']."')";
							$resultErrori=sqlsrv_query($conn,$queryErrori);
							$queryDataOra=str_replace("*APICE*","'",$queryDataOra);
							echo "<br><br>Errore esecuzione query<br>Query: ".$queryDataOra."<br>Errore: ";
							die(print_r(sqlsrv_errors(),TRUE));
						}
						else
						{
							while($rowDataOra=sqlsrv_fetch_array($resultDataOra))
							{
								//echo "<option value='2018-05-21'>cio</option>";
								echo "<option value='".$rowDataOra['anno']."-".$rowDataOra['mese']."-".$rowDataOra['giorno']."'>".$rowDataOra['anno']."-".$rowDataOra['mese']."-".$rowDataOra['giorno']."</option>";
							}
						}
					echo "</select>";
				echo '</th>';
				
				echo '<th style="text-align:center;padding-left:5px;padding-right:5px">Bancale</th>';
				echo '<th style="text-align:center;padding-left:5px;padding-right:5px">Operatore</th>';
				echo '<th style="text-align:center;padding-left:5px;padding-right:5px">Posizione</th>';
				echo '<th style="text-align:center;padding-left:5px;padding-right:5px">Eliminato</th>';
				echo '<th style="text-align:center;padding-left:5px;padding-right:5px">Tempo produzione</th>';
			echo '</tr>';
			while($row=sqlsrv_fetch_array($result))
			{
				echo '<tr>';
					echo '<td style="text-align:center;padding-left:5px;padding-right:5px">'.$row['id_pannello_prodotto'].'</td>';
					echo '<td style="text-align:center;padding-left:5px;padding-right:5px">'.$row['id_produzione'].'</td>';
					echo '<td style="text-align:center;padding-left:5px;padding-right:5px">'.$row['codpan'].'</td>';
					echo '<td style="text-align:center;padding-left:5px;padding-right:5px">'.$row['finitura'].'</td>';
					$codpan=$row['codpan'];
					echo '<td style="text-align:center;padding-left:5px;padding-right:5px">'.getAngolo($conn,"$codpan").'</td>';
					echo '<td style="text-align:center;padding-left:5px;padding-right:5px">'.$row['lotto'].'</td>';
					echo '<td style="text-align:center;padding-left:5px;padding-right:5px"><b style="float:left">Caricamento:</b> <span style="float:right">'.$row['dataOraCaricamento']->format('Y-m-d H:i:s').'</span></br>';
					echo '<b style="float:left">Taglio rinforzi:</b> <span style="float:right">'.$row['dataOraRinforzi1']->format('Y-m-d H:i:s').'</span></br>';
					echo '<b style="float:left">Taglio lana:</b> <span style="float:right">'.$row['dataOraLana']->format('Y-m-d H:i:s').'</span></br>';
					echo '<b style="float:left">Incollaggio rinforzi:</b> <span style="float:right">'.$row['dataOraRinforzi']->format('Y-m-d H:i:s').'</span></br>';
					echo '<b style="float:left">Incollaggio lana:</b> <span style="float:right">'.$row['dataOraIncollaggio']->format('Y-m-d H:i:s').'</span></td>';
					echo '<td style="text-align:center;padding-left:5px;padding-right:5px">'.$row['bancale'].'</td>';
					$utenteCaricamento=$row['utenteCaricamento'];
					$utenteRinforzi=$row['utenteRinforzi'];
					$utenteLana=$row['utenteLana'];
					$utenteRinforzi1=$row['utenteRinforzi1'];
					$utenteIncollaggio=$row['utenteIncollaggio'];
					echo '<td style="text-align:center;padding-left:5px;padding-right:5px"><b style="float:left">Caricamento:</b> <span style="float:right">'.getUsername($conn,$utenteCaricamento).'</span></br>';
					echo '<b style="float:left">Taglio rinforzi:</b> <span style="float:right">'.getUsername($conn,$utenteRinforzi1).'</span></br>';
					echo '<b style="float:left">Taglio lana:</b> <span style="float:right">'.getUsername($conn,$utenteLana).'</span></br>';
					echo '<b style="float:left">Incollaggio rinforzi:</b> <span style="float:right">'.getUsername($conn,$utenteRinforzi).'</span></br>';
					echo '<b style="float:left">Incollaggio lana:</b> <span style="float:right">'.getUsername($conn,$utenteIncollaggio).'</span></td>';
					echo '<td style="text-align:center;padding-left:5px;padding-right:5px">'.$row['posizione'].'</td>';
					if($row['eliminato']=="true")
						echo '<td style="text-align:center;padding-left:5px;padding-right:5px;color:red;font-weight:bold">V</td>';
					else
						echo '<td style="text-align:center;padding-left:5px;padding-right:5px">X</td>';
					echo '<td style="text-align:center;padding-left:5px;padding-right:5px">'.$row['tempoProduzione']->format('H:i:s').'</td>';
				echo '</tr>';
			}
		echo "</table>";
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
	
	function getUsername($conn,$id_utente)
	{
		$query="SELECT username FROM utenti WHERE id_utente=$id_utente";
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
				return $row['username'];
			}
		}
	}
?>