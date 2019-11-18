<?php
	
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "connessioneParametri.php";
	include "Session.php";
	
	//ini_set('max_execution_time', 120);
	
	$producibile=0;
	$producibile2=0;
	$lottiNonProducibili=array();
	$messaggiErrore=array();
		
	//IMPORTA PDF-----------------------------------------------------------------------------------------------------------------------
	
	if(set_time_limit(120))
	{
		
		$output1 = shell_exec('net use "'.getParametro($connParametri,$conn,'percorsoPDFRemoto').'" /delete 2>&1');
		$output2 = shell_exec('net use "'.getParametro($connParametri,$conn,'percorsoPDFRemoto').'" /USER:"'.getParametro($connParametri,$conn,'userRemoto').'" '.getParametro($connParametri,$conn,'passwordRemoto').' 2>&1');
		$output3 = shell_exec('xcopy "'.getParametro($connParametri,$conn,'percorsoPDFRemoto').'\\*.pdf" "'.getParametro($connParametri,$conn,'percorsoPDFLocale').'\\*.*" /d /y 2>&1');
		
		//echo "delete: ".$output1."<br>connessione: ".$output2."<br>copia: ".$output3."<br>";
		//echo 'xcopy "'.getParametro($connParametri,$conn,'percorsoPDFRemoto').'\\*.pdf" "'.getParametro($connParametri,$conn,'percorsoPDFLocale').'\\*.*"';
		
		$l3=strlen($output3);
		$output3=substr($output3,$l3-16,$l3);
		$output3=str_replace(" File copiati","",$output3);
		if(strlen($output3)<2)
			$output3=0;
		echo "Sono stati copiati <b style='color:green'>".$output3."</b> pdf"."<br>";
		set_time_limit(30);
	}
	else
		echo "<b style='color:red'>Errore di sistema: </b>impossibile copiare i pdf. Contattare l' amministratore";
	
	//CONTROLLA PDF MANCANTI-----------------------------------------------------------------------------------------------------------------------
	
	$output = shell_exec('dir "'.getParametro($connParametri,$conn,'percorsoPDFLocale').'\\*.pdf" /b ');
	$output=str_replace(".pdf","','",$output);
	$output=str_replace("\n","",$output);
	$l=strlen($output);
	$l=$l-2;
	$output="'".substr($output,0,$l);
	//echo $output."<br>fine<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
	$query="SELECT * FROM lotti_pannelli WHERE pannello NOT IN (".$output.")";
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
		//echo $query."<br>fine<br><br>";
		$n=1;
		while($row=sqlsrv_fetch_array($result))
		{
			$lotto=$row['lotto'];
			set_error_handler('exceptions_error_handler');
			try 
			{
				$test=$messaggiErrore[$lotto];
			} 
			catch (Exception $e) 
			{
				$messaggiErrore[$lotto]="";
			}
			$pannello=$row['pannello'];
			$qnt=$row['qnt'];
			//echo "<b style='color:red'>Errore: </b>pdf del pannello <b>".$pannello."</b> del lotto <b>".$lotto."</b> mancante. Quantita pannelli nel lotto: <b>".$qnt."</b><br>";
			$messaggiErrore[$lotto]=$messaggiErrore[$lotto]."<span class='messaggioErrore$lotto' style='display:none'><b style='color:red'>$n) </b>pdf del pannello <b>".$pannello."</b> del lotto <b>".$lotto."</b> mancante. Quantita pannelli nel lotto: <b>".$qnt."</b><br></span>";
			//METTI LOTTO NON PRODUCIBILE
			array_push($lottiNonProducibili,$lotto); 
			$producibile=1;
			$n++;
		}
		if($producibile==0)
			echo "Controllo pdf <b style='color:green'>superato</b><br>";
	}
	//CONTROLLA DATI PANNELLO MANCANTI-----------------------------------------------------------------------------------------------------------------------
	
	$query2="SELECT * FROM pannelliMancanti";
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
		//echo $query."<br>fine<br><br>";
		$n=1;
		while($row2=sqlsrv_fetch_array($result2))
		{
			$lotto2=$row2['lotto'];
			set_error_handler('exceptions_error_handler');
			try 
			{
				$test=$messaggiErrore[$lotto];
			} 
			catch (Exception $e) 
			{
				$messaggiErrore[$lotto2]="";
			}
			$pannello2=$row2['pannello'];
			$qnt2=$row2['qnt'];
			//echo "<b style='color:red'>Errore: </b>dati del pannello <b>".$pannello2."</b> del lotto <b>".$lotto2."</b> mancanti. Quantita pannelli nel lotto: <b>".$qnt2."</b><br>";
			$messaggiErrore[$lotto2]=$messaggiErrore[$lotto2]."<span class='messaggioErrore$lotto2' style='display:none'><b style='color:red'>$n) </b>dati del pannello <b>".$pannello2."</b> del lotto <b>".$lotto2."</b> mancanti. Quantita pannelli nel lotto: <b>".$qnt2."</b><br></span>";
			//METTI LOTTO NON PRODUCIBILE
			array_push($lottiNonProducibili,$lotto2); 
			$producibile2=1;
			$n++;
		}
		if($producibile2==0)
			echo "Controllo dati pannelli <b style='color:green'>superato</b><br>";
	}
	
	foreach($messaggiErrore as $x => $x_value) 
	{
		echo "<div class='containerErroriImportazione'><a onclick='toggleDisplayMessaggiErrore(".htmlspecialchars(json_encode($x)).")'><b style='color:red'>Errori </b>nel lotto <b>".$x."</b></a></div>";
		echo $x_value;
	}
	function exceptions_error_handler($severity, $message, $filename, $lineno) 
	{
	  if (error_reporting() == 0) {
		return;
	  }
	  if (error_reporting() & $severity) {
		throw new ErrorException($message, 0, $severity, $filename, $lineno);
	  }
	}
	//METTI TUTTI I LOTTI PRODUCIBILI-----------------------------------------------------------------------------------------------------------------------
	
	$query4="UPDATE lotti SET producibile = 'true'";
	$result4=sqlsrv_query($conn,$query4);
	if($result4==FALSE)
	{
		$query4=str_replace("'","*APICE*",$query4);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$query4','".$testoErrore."','".$_SESSION['Username']."')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$query4=str_replace("*APICE*","'",$query4);
		echo "<br><br>Errore esecuzione query<br>Query: ".$query4."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	
	//METTI LOTTO NON PRODUCIBILE-----------------------------------------------------------------------------------------------------------------------
	
	if($producibile==1 || $producibile2==1)
	{
		$lottiNonProducibili2=array("'".implode("','",array_unique($lottiNonProducibili))."'");
		$query3="UPDATE lotti SET producibile = 'false' WHERE lotto IN (".implode(",",$lottiNonProducibili2).")";
		$result3=sqlsrv_query($conn,$query3);
		if($result3==FALSE)
		{
			$query3=str_replace("'","*APICE*",$query3);
			$testoErrore=print_r(sqlsrv_errors(),TRUE);
			$testoErrore=str_replace("'","*APICE*",$testoErrore);
			$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
			$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$query3','".$testoErrore."','".$_SESSION['Username']."')";
			$resultErrori=sqlsrv_query($conn,$queryErrori);
			$query3=str_replace("*APICE*","'",$query3);
			echo "<br><br>Errore esecuzione query<br>Query: ".$query3."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else 
			echo "I lotti <b style='color:red'>".implode(',',array_unique($lottiNonProducibili))."</b> non sono producibili";
	}
	
	//-------------------------------------------------------------------------------------------------------------------------------------

	function getParametro($connParametri,$conn,$parametro)
	{
		$query="SELECT valore FROM parametri WHERE parametro = '$parametro'";
		$result=sqlsrv_query($connParametri,$query);
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
				return $row['valore'];
			}
		}
	}
?>