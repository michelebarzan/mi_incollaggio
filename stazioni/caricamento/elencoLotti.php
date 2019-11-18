<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	creaEriempiTabella($conn);
	
	function creaEriempiTabella($conn)
	{
		echo '<table id="myTableElencoLotti">';
		echo '<tr class="Theader">';
			creaHeader($conn);
		echo '</tr>';
			riempiTabella($conn);
		echo '</table>';
	}

	//---------------------------------------------------------------------------------------------------------------------------------------------------------------

	function creaHeader($conn)
	{
		echo '<th style="color:#666f77;font-family:arial;font-size:100%;font-weight:bold;">N.</th>';
		echo '<th style="color:#666f77;font-family:arial;font-size:100%;font-weight:bold;">Lotto</th>';
		echo '<th style="color:#666f77;font-family:arial;font-size:100%;font-weight:bold;">Chiuso</th>';
		echo '<th style="color:#666f77;font-family:arial;font-size:100%;font-weight:bold;">Completato</th>';
	}
	
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------

	function riempiTabella($conn)
	{
		$queryRighe="SELECT lotti.* FROM lotti WHERE producibile='true' AND chiuso='false' ORDER BY dataImportazione DESC";
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
				if($i<10)
					$j='0'.$i;
				else
					$j=$i;
				
				echo '<tr id="rigaLotto'.$j.'" onclick=codice("'.$j.'")>';
					echo "<td id='colonnaNumeroLotto".$j."'>$j</td>";
					echo '<td id="colonnaLotto'.$j.'">'.$rowRighe['lotto'].'</td>';
					if($rowRighe['chiuso']=='true')
						echo '<td style="color:green">V</td>';
					else
						echo '<td style="color:red">X</td>';
					//controllaCompletato($conn,$rowRighe['lotto']);
					if($rowRighe['completato']=='true')
						echo '<td id="colonnaCompletato'.$j.'" style="color:green">V</td>';
					else
						echo '<td id="colonnaCompletato'.$j.'" style="color:red">X</td>';
				echo '</tr>';
			}
			echo "<input type='hidden' id='qntLotti' value='$j' />";
		}
	}
	
	function controllaCompletato($conn,$lotto)
	{
		$pannelliMancanti=array();
			
		$query="SELECT lotto, pannello, SUM(qnt) AS qnt, finitura FROM lotti_pannelli WHERE lotto='$lotto' GROUP BY lotto,pannello,finitura";
		
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
				$lotto1=$row['lotto'];
				$pannello=$row['pannello'];
				array_push($pannelliMancanti,getPannelliMancanti($conn,"$lotto1",$row['qnt'],"$pannello"));
			}
			$completato="false";
			$i=0;
			while($i<count($pannelliMancanti))
			{
				if($pannelliMancanti[$i]!=0)
				{
					$completato="false";
					break;
				}
				else
					$completato="true";
				$i++;
			}
			setCompletato($conn,$lotto,$completato);
		}
	}
	
	function getPannelliMancanti($conn,$lotto,$tot,$pannello)
	{
		$query="SELECT qnt FROM qtaPannelli WHERE lotto='$lotto' AND codpan='$pannello'";
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
				$qnt=$row['qnt'];
				return $tot-$qnt;
			}
			return $tot;
		}
	}
	
	function setCompletato($conn,$lotto,$completato)
	{
		$query="UPDATE lotti SET completato='$completato' WHERE lotto='$lotto'";
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
	}

?>