<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
		
	$LUNG='';
	$Pos=array();
		
	$queryLana="SELECT * FROM Pc_xlana WHERE LUNG=(SELECT MAX(LUNG) FROM Pc_xlana)";
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
			$latoLargo=$rowLana['latoLargo'];
			if($latoLargo>0)
				$latoLargo='+'.$latoLargo;
			if($latoLargo==0)
				$latoLargo='';
		}
	}
	
	if($LUNG=='')
		die();
	
	$queryLanaFresature="SELECT * FROM xlana_fresature";
	$resultLanaFresature=sqlsrv_query($conn,$queryLanaFresature);
	if($resultLanaFresature==FALSE)
	{
		$queryLanaFresature=str_replace("'","*APICE*",$queryLanaFresature);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$queryLanaFresature','".$testoErrore."','".$_SESSION['Username']."')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$queryLanaFresature=str_replace("*APICE*","'",$queryLanaFresature);
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryLanaFresature."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($rowLanaFresature=sqlsrv_fetch_array($resultLanaFresature))
		{
			array_push($Pos,($rowLanaFresature['Pos']/2));
		}
	}
	
	$Y_rettangolo1=400-($LUNG);
	$n_fresature=count($Pos);
	$i=0;
	if($Ang1!=0 || $Ang2!=0)
		$scantonare="SCANTONARE";
	else
		$scantonare="";
		
	echo '<svg height="450px" width="100%">';
		//PANNELLO
		echo '<rect x="50" y="'.$Y_rettangolo1.'" width="'.$HALT.'" height="'.$LUNG.'" style="fill:white;stroke:black;stroke-width:2" />';
		echo '<text x="30" y="'.($Y_rettangolo1-10).'" fill="black" style="font-family:Arial;font-weight:bold;font-size:130%" >'.$Ang1.'°</text>';
		echo '<text x="30" y="'.($Y_rettangolo1+$LUNG+25).'" fill="black" style="font-family:Arial;font-weight:bold;font-size:130%" >'.$Ang2.'°</text>';
		//echo '<text x="35" y="'.(470-($LUNG/2)).'" fill="#F0DA14" transform="rotate(270 35,'.(470-($LUNG/2)).')" style="font-family:Arial;font-weight:bold;font-size:150%" >'.$scantonare.'</text>';
	
		//FRESATURE
		while($i<$n_fresature)
		{
			echo '<line x1="'.(35+$Pos[$i]).'" y1="'.(400-$LUNG).'" x2="'.(35+$Pos[$i]).'" y2="'.(400).'" style="stroke:red;stroke-width:3" />';
			echo '<text x="'.(42+$Pos[$i]).'" y="'.(395-$LUNG).'" fill="red" transform="rotate(270 '.(42+$Pos[$i]).','.(395-$LUNG).')" style="font-family:Arial;font-weight:bold;font-size:130%" >'.($Pos[$i]*2).'</text>';
			$i++;
		}
		
		//QUOTA ORRIZZONTALE
		echo '<line x1="35" y1="'.(330-$LUNG).'" x2="'.(65+$HALT).'" y2="'.(330-$LUNG).'" style="stroke:black;stroke-width:2" />';
		echo '<line x1="50" y1="'.(330-$LUNG-10).'" x2="50" y2="'.(330-$LUNG+10).'" style="stroke:black;stroke-width:2" />';
		echo '<line x1="'.(50+$HALT).'" y1="'.(330-$LUNG-10).'" x2="'.(50+$HALT).'" y2="'.(330-$LUNG+10).'" style="stroke:black;stroke-width:2" />';
		echo '<text x="'.((55+$HALT)/2).'" y="'.(325-$LUNG).'" fill="black" style="font-family:Arial;font-weight:bold;font-size:130%" >'.($HALT*2).'</text>';
		//QUOTA VERTICALE
		echo '<line x1="'.(130+$HALT).'" y1="415" x2="'.(130+$HALT).'" y2="'.(385-$LUNG).'" style="stroke:black;stroke-width:2" />';
		echo '<line x1="'.(120+$HALT).'" y1="400" x2="'.(140+$HALT).'" y2="400" style="stroke:black;stroke-width:2" />';
		echo '<line x1="'.(120+$HALT).'" y1="'.(400-$LUNG).'" x2="'.(140+$HALT).'" y2="'.(400-$LUNG).'" style="stroke:black;stroke-width:2" />';
		echo '<text x="'.(120+$HALT).'" y="'.(420-($LUNG/2)).'" transform="rotate(270 '.(120+$HALT).','.(420-($LUNG/2)).')" fill="black" style="font-family:Arial;font-weight:bold;font-size:130%" >'.(round(($LUNG*2),1)).$latoLargo.'</text>';
		
	echo 'Sorry, your browser does not support inline SVG.</svg>';
	

?>