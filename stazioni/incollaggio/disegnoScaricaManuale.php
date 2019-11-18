<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$codPan=$_REQUEST['codPan'];
	$codPan=substr($codPan,1);
	$codPan='+'.$codPan;
	
	$posy=array();
	$posyText=array();
	$posx=array();
	$posxText=array();
	$lunghezza=array();
	$lunghezzaText=array();
	
	$LUNG1='';
	$LUNG2='';
	$HALT='';
	
	$queryRinforzi="SELECT * FROM rinforzi_tutti_pannelli WHERE CODPAS='".$codPan."'";
	$resultRinforzi=sqlsrv_query($conn,$queryRinforzi);
	if($resultRinforzi==FALSE)
	{
		$queryRinforzi=str_replace("'","*APICE*",$queryRinforzi);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$queryRinforzi','".$testoErrore."','".$_SESSION['Username']."')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$queryRinforzi=str_replace("*APICE*","'",$queryRinforzi);
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRinforzi."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($rowRinforzi=sqlsrv_fetch_array($resultRinforzi))
		{
			$LUNG1=$rowRinforzi['LUNG1'];
			$LUNG2=$rowRinforzi['LUNG2'];
			$HALT=$rowRinforzi['HALT'];
		}
	}
	if($LUNG1=='' || $LUNG1==NULL)
	{
		echo "<br><br><b style='font-size:300%;color:red;font-family:Monospace'>DISEGNO NON TROVATO</b>";
		die();
	}
	
	$queryRinforzi_T="SELECT * FROM rinforzi_T_tutti_pannelli WHERE CODPAS='".$codPan."'";
	$resultRinforzi_T=sqlsrv_query($conn,$queryRinforzi_T);
	if($resultRinforzi_T==FALSE)
	{
		$queryRinforzi_T=str_replace("'","*APICE*",$queryRinforzi_T);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$queryRinforzi_T','".$testoErrore."','".$_SESSION['Username']."')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$queryRinforzi_T=str_replace("*APICE*","'",$queryRinforzi_T);
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRinforzi_T."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($rowRinforzi_T=sqlsrv_fetch_array($resultRinforzi_T))
		{
			array_push($posy,($rowRinforzi_T['POSY']/2));
			array_push($posyText,$rowRinforzi_T['POSY']);
			array_push($posx,($rowRinforzi_T['POSX']/2));
			array_push($posxText,$rowRinforzi_T['POSX']);
			$codPan=$rowRinforzi_T['CODPAS'];
			array_push($lunghezza,($rowRinforzi_T['lunghezza']/2));
			array_push($lunghezzaText,$rowRinforzi_T['lunghezza']);
		}
		
	}
	
	$posy_P=array();
	$posyText_P=array();
	$posx_P=array();
	$posxText_P=array();
	$lunghezza_P=array();
	$lunghezzaText_P=array();
	
	$queryRinforzi_P="SELECT * FROM rinforzi_P_tutti_pannelli WHERE CODPAS='".$codPan."'";
	$resultRinforzi_P=sqlsrv_query($conn,$queryRinforzi_P);
	if($resultRinforzi_P==FALSE)
	{
		$queryRinforzi_P=str_replace("'","*APICE*",$queryRinforzi_P);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$queryRinforzi_P','".$testoErrore."','".$_SESSION['Username']."')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$queryRinforzi_P=str_replace("*APICE*","'",$queryRinforzi_P);
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRinforzi_P."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($rowRinforzi_P=sqlsrv_fetch_array($resultRinforzi_P))
		{
			array_push($posy_P,($rowRinforzi_P['POSY']/2));
			array_push($posyText_P,$rowRinforzi_P['POSY']);
			array_push($posx_P,($rowRinforzi_P['POSX']/2));
			array_push($posxText_P,$rowRinforzi_P['POSX']);
			//$codPan=$rowRinforzi_P['CODPAS'];
			array_push($lunghezza_P,($rowRinforzi_P['lunghezza']/2));
			array_push($lunghezzaText_P,$rowRinforzi_P['lunghezza']);
		}
		
	}
	
	$n_rinforzi_P=count($posy_P);
	$n_rinforzi_T=count($posy);
	$HALT=$HALT/2;
	$LUNG1=$LUNG1/2;
	$LUNG2=$LUNG2/2;
	$Y_rettangolo1=490-($LUNG1);
	$Y_rettangolo2=490-$LUNG1-$LUNG2;
	//echo $HALT.",".$LUNG1.",".$Y_rettangolo1;
	
	$i=0;
	$j=0;
	
	
	echo '<svg height="550px" width="1260px">';
		//PANNELLO
		echo '<rect x="50" y="'.$Y_rettangolo2.'" width="'.$HALT.'" height="'.$LUNG2.'" style="fill:white;stroke:black;stroke-width:2" />';
		echo '<rect x="50" y="'.$Y_rettangolo1.'" width="'.$HALT.'" height="'.$LUNG1.'" style="fill:white;stroke:black;stroke-width:2" />';
		
		//RINFORZI P
		while($j<$n_rinforzi_P)
		{
			$x1=50+$posy_P[$j];
			//$y1=505-$LUNG1;
			$y1=490-($posx_P[$j]);
			$x2=$x1;
			//$y2=505-$LUNG1+$lunghezza[$i];
			$y2=490-($posx_P[$j])-$lunghezza_P[$j];
			$x1Text=$x1+8;
			$y1Text=550-$LUNG1;
			//echo '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" style="stroke:#0ED200;stroke-width:3" />';
			echo '<rect x="'.($x2-20).'" y="'.$y2.'" width="40" height="'.$lunghezza_P[$j].'" style="fill:#0ED200;stroke:#0ED200;stroke-width:3" />';
			echo '<text x="'.$x1Text.'" y="'.($y1Text+$lunghezza_P[$j]).'" fill="#0ED200" transform="rotate(270 '.$x1Text.','.($y1Text+$lunghezza_P[$j]).')" style="font-family:Arial;font-weight:bold;font-size:130%" >'.$posyText_P[$j].'</text>';
			echo '<text x="'.($x1Text).'" y="'.(370-$posx_P[$j]).'" fill="white" transform="rotate(270 '.($x1Text).','.(370-$posx_P[$j]).')" style="font-family:Arial;font-weight:bold;font-size:130%" >L: '.(round(($lunghezza_P[$j]*2),1)).'</text>';
			$j++;
		}
		//RINFORZI T
		while($i<$n_rinforzi_T)
		{
			$x1=50+$posy[$i];
			//$y1=505-$LUNG1;
			$y1=490-($posx[$i]);
			$x2=$x1;
			//$y2=505-$LUNG1+$lunghezza[$i];
			$y2=490-($posx[$i])-$lunghezza[$i];
			$x1Text=$x1+8;
			if($LUNG2<50)
				$y1Text=480-$LUNG1-$LUNG2;
			else
				$y1Text=480-$LUNG1;
			echo '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" style="stroke:red;stroke-width:3" />';
			echo '<text x="'.$x1Text.'" y="'.$y1Text.'" fill="red" transform="rotate(270 '.$x1Text.','.$y1Text.')" style="font-family:Arial;font-weight:bold;font-size:130%" >'.$posyText[$i].'</text>';
			echo '<text x="'.($x1Text-17).'" y="'.(480-$posx[$i]).'" fill="red" transform="rotate(270 '.($x1Text-17).','.(480-$posx[$i]).')" style="font-family:Arial;font-weight:bold;font-size:130%" >L: '.(round(($lunghezza[$i]*2),1)).'</text>';
			$i++;
		}
		
		//QUOTA ORRIZZONTALE
		echo '<line x1="35" y1="'.(347-$LUNG1).'" x2="'.(65+$HALT).'" y2="'.(347-$LUNG1).'" style="stroke:black;stroke-width:2" />';
		echo '<line x1="50" y1="'.(347-$LUNG1-10).'" x2="50" y2="'.(347-$LUNG1+10).'" style="stroke:black;stroke-width:2" />';
		echo '<line x1="'.(50+$HALT).'" y1="'.(347-$LUNG1-10).'" x2="'.(50+$HALT).'" y2="'.(347-$LUNG1+10).'" style="stroke:black;stroke-width:2" />';
		echo '<text x="'.((45+$HALT)/2).'" y="'.(341-$LUNG1).'" fill="black" style="font-family:Arial;font-weight:bold;font-size:130%" >'.($HALT*2).'</text>';
		//QUOTA VERTICALE
		echo '<line x1="'.(130+$HALT).'" y1="505" x2="'.(130+$HALT).'" y2="'.(475-$LUNG1).'" style="stroke:black;stroke-width:2" />';
		echo '<line x1="'.(120+$HALT).'" y1="490" x2="'.(140+$HALT).'" y2="490" style="stroke:black;stroke-width:2" />';
		echo '<line x1="'.(120+$HALT).'" y1="'.(490-$LUNG1).'" x2="'.(140+$HALT).'" y2="'.(490-$LUNG1).'" style="stroke:black;stroke-width:2" />';
		echo '<text x="'.(120+$HALT).'" y="'.(510-($LUNG1/2)).'" transform="rotate(270 '.(120+$HALT).','.(510-($LUNG1/2)).')" fill="black" style="font-family:Arial;font-weight:bold;font-size:130%" >'.(round(($LUNG1*2),1)).'</text>';
		if($LUNG2!=0)
		{
			//QUOTA VERTICALE PICCOLA
			echo '<line x1="'.(130+$HALT).'" y1="'.(505-$LUNG1).'" x2="'.(130+$HALT).'" y2="'.(475-$LUNG1-$LUNG2).'" style="stroke:black;stroke-width:2" />';
			echo '<line x1="'.(120+$HALT).'" y1="'.(490-$LUNG1-$LUNG2).'" x2="'.(140+$HALT).'" y2="'.(490-$LUNG1-$LUNG2).'" style="stroke:black;stroke-width:2" />';
			echo '<text x="'.(115+$HALT).'" y="'.(510-($LUNG2/2)-$LUNG1).'" transform="rotate(270 '.(115+$HALT).','.(510-($LUNG2/2)-$LUNG1).')" fill="black" style="font-family:Arial;font-weight:bold;font-size:130%" >'.(round(($LUNG2*2),1)).'</text>';
		}
		
	echo 'Sorry, your browser does not support inline SVG.</svg>';
	

?>