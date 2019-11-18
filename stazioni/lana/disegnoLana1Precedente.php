<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
		
	$codPan=$_REQUEST['codPan'];
	$codPan=substr($codPan,1);
	$codPan='+'.$codPan;
		
	$queryRighe="SELECT COUNT(*) AS nRighe FROM Pc_xlana_nofiltro WHERE codpan='$codPan'";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		$queryRighe=str_replace("'","*APICE*",$queryRighe);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$queryRighe','".$testoErrore."','".$_SESSION['Username']."')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$queryRighe=str_replace("*APICE*","'",$queryRighe);
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($rowRighe=sqlsrv_fetch_array($resultRighe))
		{
			$nRighe=$rowRighe['nRighe'];
		}
	}
	if($nRighe<2)
		die();
	
	$queryLana="SELECT * FROM Pc_xlana_nofiltro WHERE LUNG=(SELECT MIN(LUNG) FROM Pc_xlana_nofiltro WHERE codpan='$codPan') AND codpan='$codPan'";
	$resultLana=sqlsrv_query($conn,$queryLana);
	if($resultLana==FALSE)
	{
		$queryLana=str_replace("'","*APICE*",$queryLana);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$queryLana','".$testoErrore."','".$_SESSION['Username']."')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$queryLana=str_replace("*APICE*","'",$queryLana);
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryLana."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($rowLana=sqlsrv_fetch_array($resultLana))
		{
			$LUNG=($rowLana['LUNG']/2);
			$HALT=($rowLana['HALT']/2);
			$Ang1=$rowLana['Ang1'];
			$Ang2=$rowLana['Ang2'];
		}
	}
	
	$Y_rettangolo1=200-($LUNG);
	$i=0;
	if($Ang1!=0 || $Ang2!=0)
		$scantonare="SCANTONARE";
	else
		$scantonare="";
		
	echo '<svg height="250px" width="1260px">';
		//PANNELLO
		echo '<rect x="50" y="'.$Y_rettangolo1.'" width="'.$HALT.'" height="'.$LUNG.'" style="fill:white;stroke:black;stroke-width:2" />';
		echo '<text x="30" y="'.($Y_rettangolo1-10).'" fill="black" style="font-family:Arial;font-weight:bold;font-size:130%" >'.$Ang1.'°</text>';
		echo '<text x="30" y="'.($Y_rettangolo1+$LUNG+25).'" fill="black" style="font-family:Arial;font-weight:bold;font-size:130%" >'.$Ang2.'°</text>';
		
		//QUOTA ORRIZZONTALE
		echo '<line x1="35" y1="'.(150-$LUNG).'" x2="'.(65+$HALT).'" y2="'.(150-$LUNG).'" style="stroke:black;stroke-width:2" />';
		echo '<line x1="50" y1="'.(150-$LUNG-10).'" x2="50" y2="'.(150-$LUNG+10).'" style="stroke:black;stroke-width:2" />';
		echo '<line x1="'.(50+$HALT).'" y1="'.(150-$LUNG-10).'" x2="'.(50+$HALT).'" y2="'.(150-$LUNG+10).'" style="stroke:black;stroke-width:2" />';
		echo '<text x="'.((55+$HALT)/2).'" y="'.(145-$LUNG).'" fill="black" style="font-family:Arial;font-weight:bold;font-size:130%" >'.($HALT*2).'</text>';
		//QUOTA VERTICALE
		echo '<line x1="'.(130+$HALT).'" y1="215" x2="'.(130+$HALT).'" y2="'.(185-$LUNG).'" style="stroke:black;stroke-width:2" />';
		echo '<line x1="'.(120+$HALT).'" y1="200" x2="'.(140+$HALT).'" y2="200" style="stroke:black;stroke-width:2" />';
		echo '<line x1="'.(120+$HALT).'" y1="'.(200-$LUNG).'" x2="'.(140+$HALT).'" y2="'.(200-$LUNG).'" style="stroke:black;stroke-width:2" />';
		echo '<text x="'.(120+$HALT).'" y="'.(220-($LUNG/2)).'" transform="rotate(270 '.(120+$HALT).','.(220-($LUNG/2)).')" fill="black" style="font-family:Arial;font-weight:bold;font-size:130%" >'.(round(($LUNG*2),1)).'</text>';
		
	echo 'Sorry, your browser does not support inline SVG.</svg>';
?>