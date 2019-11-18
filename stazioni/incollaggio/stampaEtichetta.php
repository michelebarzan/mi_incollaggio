<?php
	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	if(!$conn)
		die( "connessione fallita");
	
	$id_bancale=$_GET['id_bancale'];
	echo '<input type="hidden" id="hiddenId_bancale" value="'.$id_bancale.'" />'
?>
<html>
	<head>
		<link rel="stylesheet" href="/mi_incollaggio/stazioni/css/styleEtichetta.css" />
		<script>
		(function() 
			{
				var beforePrint = function() 
				{
					//window.alert('Functionality to run before printing.');
				};
				var afterPrint = function() 
				{
					setTimeout(function()
					{ 
						window.close();
					}, 500)
				};

				if (window.matchMedia) 
				{
					var mediaQueryList = window.matchMedia('print');
					mediaQueryList.addListener(function(mql) 
					{
						if (mql.matches) 
						{
							beforePrint();
						} 
						else 
						{
							afterPrint();
						}
					});
				}

				window.onbeforeprint = beforePrint;
				window.onafterprint = afterPrint;
			}());
			function stampa()
			{
				setTimeout(function()
				{ 
					window.print();
				}, 500)
			}		
		</script>
	</head>
	<body onload="stampa()">
		<div id="etichetta">
			<?php
				getNome($conn,$id_bancale);
				getPannelli($conn,$id_bancale);
			?>
		</div>
	</body>
</html>
<?php

	function getNome($conn,$id_bancale)
	{
		$query="SELECT bancali.* FROM bancali WHERE bancali.id_bancale=$id_bancale";
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
				$nome= $row['nome'];
				$numero= $row['numero'];
				$lotto= $row['lotto'];
				$dataOraCreazione= $row['dataOraCreazione']->format('d/m/Y H:i:s');
				$dataOraChiusura= $row['dataOraChiusura']->format('d/m/Y H:i:s');
				$commessa=getCommessa($conn,$lotto);
			}
			echo "<div id='nome'>$nome</div>";
			echo "<div id='commessa'>Commessa:<br><b>$commessa</b></div><div id='lotto'>Lotto:<br><b>$lotto</b></div><div id='numero'>Numero:<br><b>$numero</b></div>";
			echo "<div id='dataOraCreazione'>Creato il:<br><b>$dataOraCreazione</b></div><div id='dataOraChiusura'>Chiuso il:<br><b>$dataOraChiusura</b></div>";
		}
	}
	function getCommessa($conn,$lotto)
	{
		$query="SELECT commessa FROM lotti WHERE lotti.lotto='$lotto'";
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
				
				return $row['commessa'];
			}
		}
	}
	function getPannelli($conn,$id_bancale)
	{
		$elencoCodpan=array();
		$query="SELECT TOP(50)PERCENT codpan,COUNT(*) AS qnt FROM pannelli_prodotti WHERE bancale =$id_bancale GROUP BY codpan";
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
			echo '<table id="myTable">';
				echo '<tr class="Theader">';
					echo '<th style="border-right: 1px solid #ddd;">Codpan</th>';
					echo '<th>Qnt</th>';
				echo '</tr>';
				while($row=sqlsrv_fetch_array($result))
				{
					echo '<tr>';
						echo '<td style="border-right: 1px solid #ddd;">'.$row['codpan'].'</td>';
						array_push($elencoCodpan,"'".$row['codpan']."'");
						echo '<td>'.$row['qnt'].'</td>';
					echo '</tr>';
				}
			echo "</table>";
		}
		if(count($elencoCodpan)==0)
			$query2="SELECT codpan,COUNT(*) AS qnt FROM pannelli_prodotti WHERE bancale =$id_bancale AND codpan NOT IN ('') GROUP BY codpan";
		else
			$query2="SELECT codpan,COUNT(*) AS qnt FROM pannelli_prodotti WHERE bancale =$id_bancale AND codpan NOT IN (".implode(",",$elencoCodpan).") GROUP BY codpan";
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
			echo $testoErrore;
		}
		else
		{
			echo '<table id="myTable">';
				echo '<tr class="Theader">';
					echo '<th style="border-right: 1px solid #ddd;">Codpan</th>';
					echo '<th>Qnt</th>';
				echo '</tr>';
				while($row=sqlsrv_fetch_array($result2))
				{
					echo '<tr>';
						echo '<td style="border-right: 1px solid #ddd;">'.$row['codpan'].'</td>';
						echo '<td>'.$row['qnt'].'</td>';
					echo '</tr>';
				}
			echo "</table>";
		}
	}
?>
