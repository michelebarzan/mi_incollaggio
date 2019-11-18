<?php
	
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
	
	$query="EXEC [dbo].[AggiornaTabelle]";
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
		//echo "<br><br>Errore esecuzione query<br>Query: ".$query."<br>Errore: ";
		//die(print_r(sqlsrv_errors(),TRUE));
		echo "<b style='color:red'>Errore: impossibile connettersi al server Marine Interiors. Contattare il responsabile IT</b>";
	}
	else 
	{
		while($row=sqlsrv_fetch_array($result))
		{
			$NumRowsChangedPannelli=$row['NumRowsChangedPannelli'];
			$ErrorCodePannelli=$row['ErrorCodePannelli'];
			$NumRowsChangedPannellil=$row['NumRowsChangedPannellil'];
			$ErrorCodePannellil=$row['ErrorCodePannellil'];
			$NumRowsChangedMater=$row['NumRowsChangedMater'];
			$ErrorCodeMater=$row['ErrorCodeMater'];
			$NumRowsChangedTabrinf=$row['NumRowsChangedTabrinf'];
			$ErrorCodeTabrinf=$row['ErrorCodeTabrinf'];
			echo "<b>Sono stati importati:</b> <b style='color:green'>$NumRowsChangedPannelli</b> pannelli, <b style='color:green'>$NumRowsChangedPannellil</b> lamiere, <b style='color:green'>$NumRowsChangedMater</b> materassini, <b style='color:green'>$NumRowsChangedTabrinf</b> rinforzi";
			if($ErrorCodePannelli!=0 || $ErrorCodePannellil!=0 || $ErrorCodeMater!=0 || $ErrorCodeTabrinf!=0)
				echo "<br><b>Errori:</b> <b style='color:red'>$ErrorCodePannelli,$ErrorCodePannellil,$ErrorCodeMater,$ErrorCodeTabrinf</b>";
		}
	}
?>