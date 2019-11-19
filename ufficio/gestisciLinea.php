<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "connessionePDO.php";
	include "Session.php";
	
	$pageName="Gestione linea";
	
	$dataPoints1 = array();
	
	$handle1 = $connPDO->prepare('SELECT TOP(30) dataProduzione AS x,qnt as y FROM pannelli_al_giorno ORDER BY dataProduzione ASC'); 
    $handle1->execute(); 
    $result1 = $handle1->fetchAll(\PDO::FETCH_OBJ);
		
    foreach($result1 as $row1)
	{
        array_push($dataPoints1, array("label"=> $row1->x, "y"=> $row1->y));
    }
	
	$dataPoints2 = array();
	
	$handle2 = $connPDO->prepare('SELECT COUNT(*) AS y,codpan AS x FROM pannelli_prodotti GROUP BY  codpan'); 
    $handle2->execute(); 
    $result2 = $handle2->fetchAll(\PDO::FETCH_OBJ);
		
    foreach($result2 as $row2)
	{
        array_push($dataPoints2, array("label"=> $row2->x, "y"=> $row2->y));
    }
	
	$dataOra = date('d/m/Y h:i:s', time());
	
	$query="UPDATE pannelli_prodotti SET dataOraAcqua=dataOraCaricamento+2 WHERE dataOraAcqua IS NULL;UPDATE pannelli_prodotti SET dataOraRinforzi1=dataOraCaricamento+2 WHERE dataOraRinforzi1 IS NULL;UPDATE pannelli_prodotti SET dataOraRinforzi=dataOraCaricamento+3 WHERE dataOraRinforzi IS NULL;UPDATE pannelli_prodotti SET dataOraLana=dataOraCaricamento+2 WHERE dataOraLana IS NULL;UPDATE pannelli_prodotti SET dataOraIncollaggio=dataOraCaricamento+5 WHERE dataOraIncollaggio IS NULL;UPDATE pannelli_prodotti SET utenteRinforzi=1 WHERE utenteRinforzi IS NULL;UPDATE pannelli_prodotti SET utenteRinforzi1=1 WHERE utenteRinforzi1 IS NULL;UPDATE pannelli_prodotti SET utenteLana=1 WHERE utenteLana IS NULL;UPDATE pannelli_prodotti SET utenteIncollaggio=1 WHERE utenteIncollaggio IS NULL;UPDATE pannelli_prodotti SET mCaricamento='true';UPDATE pannelli_prodotti SET mLana='true';UPDATE pannelli_prodotti SET mRinforzi1='true';UPDATE pannelli_prodotti SET mRinforzi='true';UPDATE pannelli_prodotti SET mIncollaggio='true'";
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
		die("errore");
	}
?>
<html>
	<head>
		<title><?php echo $pageName; ?></title>
		<link rel="stylesheet" href="/mi_incollaggio/ufficio/css/styleV4.css" />
		<script src="struttura.js"></script>
		<script src="js/gestisciLinea.js"></script>
		<link rel="stylesheet" href="js_libraries/spinners/spinner.css" />
		<script src="js_libraries/spinners/spinner.js"></script>
		<script src="editableTable/editableTable.js"></script>
		<link rel="stylesheet" href="editableTable/editableTable.css" />
		<link href="fontawesome/css/all.css" rel="stylesheet">
		<script src="js_libraries/jquery/jquery-3.4.1.min.js"></script>
		<script>
			function creaGraficoTorta()
			{
				var dataPoints=<?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>;
				var chart = new CanvasJS.Chart("chartContainer2", {
                    animationEnabled: true,
                    theme: "light2",
                    title:{
                        fontSize: 15,
                        fontWeight:'bold',
                        fontFamily: "sans-serif",
						text: "Codici lavorati",
						padding:5
                    },
                    data: [{
						type: "pie",
						yValueFormatString: "###0\"\"",
                        startAngle: 240,
                        indexLabel: "{label} {y}",
                        dataPoints
                    }]
                });
                chart.render();
				/*var chart = new CanvasJS.Chart("chartContainer2", 
				{
					animationEnabled: true,
					title: {
						text: ""
					},
					subtitles: [{
						text: ""
					}],
					data: [{
						type: "pie",
						yValueFormatString: "#,##0\"\"",
						indexLabel: "{label} ({y})",
						dataPoints
					}]
				});
				chart.render();*/
			}
			function creaGrafico() 
			{
			
				var chart = new CanvasJS.Chart("chartContainer", 
				{
					title: {
						text: ""
					},
					theme: "light2",
					animationEnabled: true,
					toolTip:{
						shared: true,
						reversed: true
					},
					axisY: {
						title: "",
						suffix: " pannelli"
					},
					legend: {
						cursor: "pointer",
						itemclick: toggleDataSeries
					},
					data: [
						{
							type: "stackedColumn",
							name: " ",
							showInLegend: true,
							yValueFormatString: "#,##0 pannelli",
							dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
						}
					]
				});
				
				chart.render();
				
				function toggleDataSeries(e) 
				{
					if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					e.chart.render();
				}
				
			}
		</script>
	</head>
	<body onload="pannelliInProduzione();getFlagLanaAngolo()">
	<div id='risultatoManagement'></div>
		<?php include('struttura.php'); ?>
		<div id="container">
			<div id="content">
				<div id="immagineLogo" class="immagineLogo" ></div>
				<div id="bottoniGestisciLinea">
					<h1><span>Management</span></h1>
					<button id="btnSvuotaLinea" class="btnManagement" onclick="svuotaLinea()" ><span>Svuota linea</span></button>
					<!--<button id="btnRiapriLotto" class="btnManagement" onclick="riapriLotto()" ><span>Gestisci lotti</span></button>-->
					<button id="btnRiapriLotto" class="btnManagement" onclick="gestisciLotti()" ><span>Gestisci lotti</span></button>
					<button id="btnAggiungiPannelliLotto" class="btnManagement" onclick="aggiungiPannelliLotto('%')" ><span>Aggiungi pannelli</span></button>
					<button id="btnRiavviaProgrammi" class="btnManagement" onclick="riavviaProgrammi()" ><span>Riavvia programmi</span></button>
					<button id="btnSpegniAngoli" onclick="document.getElementById('someSwitchOptionPrimary').click()" class="btnManagement" >
						<span>Angoli:</span>
						<div id="containerSwitchAngoli" >
							<div class="material-switch pull-right" >
								<input id="someSwitchOptionPrimary" name="someSwitchOption001" onclick="toggleAngoli()" type="checkbox"/>
								<label for="someSwitchOptionPrimary"  class="label-primary"></label>
							</div>
						</div>
					</button>
					<br>
					<div id='tabelleManagement'></div>
				</div>
				<div id="intestazioneGestisciLinea">
					<input type="button" id="btnPannelliInProduzione" class="btnIntestazioneGestisciLinea" onclick="resetStyle();pannelliInProduzione()" value="Pannelli in produzione" />
					<input type="button" id="btnPannelliProdotti" class="btnIntestazioneGestisciLinea" onclick="resetStyle();pannelliProdotti('%','%','%')" value="Pannelli prodotti" />
					<input type="button" id="btnElencoBancali" class="btnIntestazioneGestisciLinea" onclick="resetStyle();elencoBancali()" value="Elenco bancali" />
					<input type="button" id="btnElencoUtenti" class="btnIntestazioneGestisciLinea" onclick="resetStyle();elencoUtenti()" value="Elenco utenti" />
					<input type="button" id="btnGraficoProduzione" class="btnIntestazioneGestisciLinea" onclick="resetStyle();graficoProduzione()" value="Grafico produzione" />
					<input type="button" id="btnStatisticheProduzione" class="btnIntestazioneGestisciLinea" onclick="resetStyle();statisticheProduzione()" value="Statistiche produzione" />
				</div>
				<div id="tabelleGestisciLinea"><script src="canvasjs.min.js"></script></div>
			</div>
		</div>
		<div id="push"></div>
		<div id="footer">
			<b>Marine&nbspInteriors&nbspS.p.A.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspSegaluzza&nbsp33170&nbspPordenone&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp0434612811
		</div>
	</body>
</html>