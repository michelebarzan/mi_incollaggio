<?php

	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$lotto=$_REQUEST['lotto'];
		
	$query="SELECT SUM(pezzi) AS pezzi,lunghezza
			FROM (SELECT w1.lRinf, w1.nRinf AS pezzi, MAX(w2.min) AS lunghezza
				FROM (SELECT Expr3 AS lRinf, SUM(nRinforzi) AS nRinf
				FROM (SELECT        w1.CODPAS, w1.QNT AS Expr2, w4.CODRIN, w4.CODMAT, w4.QNT AS Expr3, w1.POSX, w2.qnt, w1.QNT * w2.qnt AS nRinforzi
FROM            mi_incollaggio.dbo.DIBpaS AS w1 INNER JOIN
                         mi_incollaggio.dbo.tabrinf AS w4 ON w1.CODELE = w4.CODRIN INNER JOIN
                             (SELECT        lotto, pannello, qnt
                               FROM            dbo.lotti_pannelli
                               WHERE        (lotto = '$lotto')) AS w2 ON w1.CODPAS = w2.pannello
WHERE        (w4.CODMAT = '+27CM13213')) AS w1
				GROUP BY Expr3) AS w1 INNER JOIN
				(SELECT min
				FROM dbo.T_gri_rinfT AS w1
				GROUP BY min) AS w2 ON w1.lRinf >= w2.min
				GROUP BY w1.lRinf, w1.nRinf) AS w1
			GROUP BY lunghezza
			ORDER BY lunghezza";
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
		echo "<table id='myTableEstrazioneRinforzi'>";
			echo "<tr>";
				echo "<th>Pezzi</th>";
				echo "<th>Lunghezza</th>";
			echo "</tr>";
		while($row=sqlsrv_fetch_array($result))
		{
			echo "<tr>";
				echo "<td>".$row['pezzi']."</td>";
				echo '<td>'.$row["lunghezza"].'</td>';
			echo "</tr>";
		}
		echo "</table>";
	}

?>