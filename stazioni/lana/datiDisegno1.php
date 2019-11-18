<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$descrizione='';
	
	$query="SELECT * FROM Pc_xlana WHERE LUNG=(SELECT MIN(LUNG) FROM Pc_xlana)";
	
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
			$descrizione=$row['DESCRIZIONE'];
			$nome=$row['Nome'];
			$rifilare=$row['Rifilare'];
			//$nFresature=$row['Nr_Fresature'];
			$nFresature=0;
			$Ang1=$row['Ang1'];
			$Ang2=$row['Ang2'];
		}
		
		if($descrizione=='')
			die();
		
		$rTipo=substr($descrizione, -2);
		$tipo="";
		if($rTipo=="15")
			$tipo="B0";
		if($rTipo=="25")
			$tipo="B15";
		
		if($nome==NULL || $nome=="")
			$prelievo="Standard";
		else
			$prelievo=$nome;
		
		echo "<table id='myTableDatiLana'>";
			echo "<tr>";
				if($tipo=="B15")
					echo "<td style='background:yellow'><b>TIPO:</b> <b style='color:blue'>$tipo</b></td>";
				else
					echo "<td><b>TIPO:</b> <b style='color:blue'>$tipo</b></td>";
				if($prelievo!="Standard")
					echo "<td style='background:yellow'><b>PRELIEVO:</b> <b style='color:blue'>$prelievo</b></td>";
				else
					echo "<td><b>PRELIEVO:</b> <b style='color:blue'>$prelievo</b></td>";
				if($rifilare!=NULL || $rifilare!="")
					echo "<td style='background:yellow'><b>RIFILARE:</b> <b style='color:blue'>$rifilare</b></td>";
				else
					echo "<td><b>RIFILARE:</b> <b style='color:blue'>$rifilare</b></td>";
				if($Ang1!=0 || $Ang2!=0)
					echo "<td style='background:yellow'><b style='color:blue'>Scantonare</b></td>";
				if($nFresature!=0)
					echo "<td style='background:yellow'><b>FRESATURE:</b> <b style='color:blue'>$nFresature</b></td>";
				else
					echo "<td><b>FRESATURE:</b> <b style='color:blue'>$nFresature</b></td>";
				//echo "TIPO: $tipo | PRELIEVO: $prelievo | RIFILARE: $rifilare | FRESATURE: $nFresature";
			echo "</tr>";
		echo "</table>";
	}
	
?>