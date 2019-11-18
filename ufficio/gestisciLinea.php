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
			<link rel="stylesheet" href="/mi_incollaggio/ufficio/css/styleV3.css" />
			<script src="struttura.js"></script>
			<script>
				function resetStyle()
				{
					var all = document.getElementsByClassName("btnIntestazioneGestisciLinea");
					for (var i = 0; i < all.length; i++) 
					{
						all[i].style.color = 'black';
						all[i].style.boxShadow="";
					}
					/*try
					{
						var interval=pannelliInProduzione();
						clearInterval(interval);
					}
					catch(e)
					{
						window.alert(e.message);
					}*/
				}
				function newGridSpinner(message,container,spinnerContainerStyle,spinnerStyle,messageStyle)
				{
					document.getElementById(container).innerHTML='<div id="gridSpinnerContainer"  style="'+spinnerContainerStyle+'"><div  style="'+spinnerStyle+'" class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div> <div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div><div id="messaggiSpinner" style="'+messageStyle+'">'+message+'</div></div>';
				}
				function pannelliInProduzione()
				{
					document.getElementById('btnPannelliInProduzione').style.color="#3367d6";
					document.getElementById('btnPannelliInProduzione').style.boxShadow=" 5px 5px 10px #9c9e9f";
					newGridSpinner("Caricamento dati in corso...","tabelleGestisciLinea","","","font-size:80%;color:#2B586F");
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById('tabelleGestisciLinea').innerHTML= this.responseText;
						}
					};
					xmlhttp.open("POST", "pannelliInProduzione.php?", true);
					xmlhttp.send();
				}
				function pannelliProdotti(codpan,lotto,dataOra)
				{
					document.getElementById('btnPannelliProdotti').style.color="#3367d6";
					document.getElementById('btnPannelliProdotti').style.boxShadow=" 5px 5px 10px #9c9e9f";
					newGridSpinner("Caricamento dati in corso...","tabelleGestisciLinea","","","font-size:80%;color:#2B586F");
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById('tabelleGestisciLinea').innerHTML= this.responseText;
						}
					};
					xmlhttp.open("POST", "pannelliProdotti.php?codpan="+codpan+"&lotto="+lotto+"&dataOra="+dataOra, true);
					xmlhttp.send();
				}
				function filtroLotto()
				{
					var lotto=document.getElementById("filtroLottoPannelliProdotti").value;
					//window.alert(lotto);
					pannelliProdotti(document.getElementById("filtroCodpanPannelliProdotti").value,lotto,document.getElementById("filtroDataOraPannelliProdotti").value);
				}
				function filtroCodpan()
				{
					var codpan=document.getElementById("filtroCodpanPannelliProdotti").value;
					//window.alert(lotto);
					pannelliProdotti(codpan,document.getElementById("filtroLottoPannelliProdotti").value,document.getElementById("filtroDataOraPannelliProdotti").value);
				}
				function filtroDataOra()
				{
					var dataOra=document.getElementById("filtroDataOraPannelliProdotti").value;
					//window.alert(lotto);
					pannelliProdotti(document.getElementById("filtroCodpanPannelliProdotti").value,document.getElementById("filtroLottoPannelliProdotti").value,dataOra);
				}
				function elencoBancali()
				{
					document.getElementById('btnElencoBancali').style.color="#3367d6";
					document.getElementById('btnElencoBancali').style.boxShadow=" 5px 5px 10px #9c9e9f";
					newGridSpinner("Caricamento dati in corso...","tabelleGestisciLinea","","","font-size:80%;color:#2B586F");
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById('tabelleGestisciLinea').innerHTML= this.responseText;
						}
					};
					xmlhttp.open("POST", "elencoBancali.php?", true);
					xmlhttp.send();
				}
				function elencoUtenti()
				{
					document.getElementById('btnElencoUtenti').style.color="#3367d6";
					document.getElementById('btnElencoUtenti').style.boxShadow=" 5px 5px 10px #9c9e9f";
					newGridSpinner("Caricamento dati in corso...","tabelleGestisciLinea","","","font-size:80%;color:#2B586F");
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById('tabelleGestisciLinea').innerHTML= this.responseText;
						}
					};
					xmlhttp.open("POST", "elencoUtenti.php?", true);
					xmlhttp.send();
				}
				function modificaUtente(i)
				{
					var id_utente=document.getElementById('idUtente'+i).innerHTML;
					var nome=document.getElementById('nomeUtente'+i).innerHTML;
					var cognome=document.getElementById('cognomeUtente'+i).innerHTML;
					var username=document.getElementById('usernameUtente'+i).innerHTML;
					
					if(nome=='' || cognome=='' || username=='')	
						document.getElementById('risultato'+i).innerHTML="<b style='color:red'>Tutti i campi sono obbligatori</b>";
					else
					{
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								document.getElementById('risultato'+i).innerHTML= this.responseText;
							}
						};
						xmlhttp.open("POST", "modificaUtente.php?id_utente="+id_utente+"&nome="+nome+"&cognome="+cognome+"&username="+username, true);
						xmlhttp.send();
					}
				}
				function inserisciUtente()
				{
					var nome=document.getElementById('nuovoNomeUtente').innerHTML;
					var cognome=document.getElementById('nuovoCognomeUtente').innerHTML;
					var username=document.getElementById('nuovoUsernameUtente').innerHTML;
					
					if(nome=='' || cognome=='' || username=='')	
						document.getElementById('risultatoInserimento').innerHTML="<b style='color:red'>Tutti i campi sono obbligatori</b>";
					else
					{					
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								if(this.responseText.indexOf("inserito")>0)
									document.getElementById('btnElencoUtenti').click();
								else
									document.getElementById('risultatoInserimento').innerHTML= this.responseText;
							}
						};
						xmlhttp.open("POST", "inserisciUtente.php?nome="+nome+"&cognome="+cognome+"&username="+username, true);
						xmlhttp.send();
					}
				}
				function graficoProduzione()
				{
					document.getElementById('btnGraficoProduzione').style.color="#3367d6";
					document.getElementById('btnGraficoProduzione').style.boxShadow=" 5px 5px 10px #9c9e9f";
					document.getElementById('tabelleGestisciLinea').innerHTML= '<div id="chartContainer" style="height: 370px; width: 100%;margin-top:10px"></div>';
					creaGrafico() ;
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
				function statisticheProduzione()
				{
					document.getElementById('btnStatisticheProduzione').style.color="#3367d6";
					document.getElementById('btnStatisticheProduzione').style.boxShadow=" 5px 5px 10px #9c9e9f";
					
					document.getElementById('tabelleGestisciLinea').innerHTML='<div id="divStatistiche" style="height: 370px; width: 25%;display:inline-block;float:left;margin-top:10px"></div><div id="divTorta" style="height: 370px; width: 75%;display:inline-block;float:right;margin-top:10px"></div>';
					
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById('divStatistiche').innerHTML= this.responseText;
							document.getElementById('divTorta').innerHTML= '<div id="chartContainer2" style="height: 370px; width: 100%;display:inline-block;float:right;margin-top:10px"></div>';
							creaGraficoTorta() ;
						}
					};
					xmlhttp.open("POST", "statisticheProduzione.php?", true);
					xmlhttp.send();
				}
				function creaGraficoTorta()
				{
					var chart = new CanvasJS.Chart("chartContainer2", 
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
							dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
						}]
					});
					chart.render();
				}
				function svuotaLinea()
				{
					if (confirm("ATTENZIONE!\n\n Lo svuotamento comporterà la perdita di tutti i dati dei pannelli che dovranno essere tolti manualmente dalla linea. \n\nQuesta procedura deve essere seguita da un reset macchina.\n\nIl programma si riavviera su tutti i monitor.\n")) 
					{
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								document.getElementById('risultatoManagement').style.width="300px";
								document.getElementById('risultatoManagement').innerHTML= this.responseText;
								document.getElementById('btnPannelliInProduzione').click();
								setTimeout(function()
								{ 
									document.getElementById('risultatoManagement').innerHTML= "";
									document.getElementById('risultatoManagement').style.width="0px";
									eliminaFlagSvuotaLinea();
								}, 3000);
							}
						};
						xmlhttp.open("POST", "svuotaLinea.php?", true);
						xmlhttp.send();
					} 
				}
				function eliminaFlagSvuotaLinea()
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							
						}
					};
					xmlhttp.open("POST", "eliminaFlagSvuotaLinea.php?", true);
					xmlhttp.send();
				}
			
				function riapriLotto()
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById('tabelleManagement').style.width="100%";
							document.getElementById('tabelleManagement').innerHTML= this.responseText;
						}
					};
					xmlhttp.open("POST", "riapriLotto.php?", true);
					xmlhttp.send();
				}
				function riapriLottoModifica(i)
				{
					var lotto=document.getElementById('riapriLotto'+i).innerHTML;
					
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById('btnRiapriLotto').click();
							document.getElementById('risultatoManagement').style.width="300px";
							document.getElementById('risultatoManagement').innerHTML= this.responseText;
							setTimeout(function()
							{ 
								document.getElementById('risultatoManagement').innerHTML= "";
								document.getElementById('risultatoManagement').style.width="0px";
								/*document.getElementById('tabelleManagement').style.width="0px";
								document.getElementById('tabelleManagement').innerHTML= "";*/
							}, 2000);
						}
					};
					xmlhttp.open("POST", "riapriLottoModifica.php?lotto="+lotto, true);
					xmlhttp.send();
				}
				function forzaProducibile(i)
				{
					var lotto=document.getElementById('riapriLotto'+i).innerHTML;
					
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById('btnRiapriLotto').click();
							document.getElementById('risultatoManagement').style.width="300px";
							document.getElementById('risultatoManagement').innerHTML= this.responseText;
							setTimeout(function()
							{ 
								document.getElementById('risultatoManagement').innerHTML= "";
								document.getElementById('risultatoManagement').style.width="0px";
								/*document.getElementById('tabelleManagement').style.width="0px";
								document.getElementById('tabelleManagement').innerHTML= "";*/
							}, 2000);
						}
					};
					xmlhttp.open("POST", "forzaProducibile.php?lotto="+lotto, true);
					xmlhttp.send();
				}
				function chiudiTabelleManagement()
				{
					document.getElementById('tabelleManagement').innerHTML= "";
					document.getElementById('tabelleManagement').style.width="0px";
				}
				function aggiungiPannelliLotto()
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById('tabelleManagement').style.width="100%";
							document.getElementById('tabelleManagement').innerHTML= this.responseText;
						}
					};
					xmlhttp.open("POST", "aggiungiPannelliLotto.php?", true);
					xmlhttp.send();
				}
				function aggiungiPannelliModifica(i)
				{
					var lotto=document.getElementById('aggiungiPannelli'+i).innerHTML;
					var codpan=document.getElementById('aggiungiPannelliCodpan'+i).value;
					var qnt=document.getElementById('aggiungiPannelliQnt'+i).value;
					var finitura=document.getElementById('aggiungiPannelliFinitura'+i).value;
					
					if(codpan=='' || qnt=='' || finitura=='' || qnt==0)
					{
						document.getElementById('risultatoManagement').style.width="300px";
						setTimeout(function()
						{ 
							document.getElementById('risultatoManagement').innerHTML= "Tutti i campi sono obbligatori";
						}, 600);
						setTimeout(function()
						{ 
							document.getElementById('risultatoManagement').innerHTML= "";
							document.getElementById('risultatoManagement').style.width="0px";
						}, 2000);
					}
					else
					{
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								document.getElementById('risultatoManagement').style.width="300px";
								document.getElementById('risultatoManagement').style.lineHeight= "50px";
								var r=this.responseText;
								setTimeout(function()
								{ 
									document.getElementById('risultatoManagement').innerHTML= r;
									document.getElementById('aggiungiPannelliCodpan'+i).value="+K4PN";
									document.getElementById('aggiungiPannelliQnt'+i).value="";
									document.getElementById('aggiungiPannelliFinitura'+i).value="";
								}, 600);
								setTimeout(function()
								{ 
									document.getElementById('risultatoManagement').innerHTML= "";
									document.getElementById('risultatoManagement').style.width="0px";
									document.getElementById('risultatoManagement').style.lineHeight= "100px";
								}, 3000);
							}
						};
						xmlhttp.open("POST", "aggiungiPannelliModifica.php?lotto="+lotto+"&codpan="+codpan+"&qnt="+qnt+"&finitura="+finitura, true);
						xmlhttp.send();
					}
				}
				function riavviaProgrammi()
				{
					if (confirm("ATTENZIONE!\n\nIl programma si riavviera su tutti i monitor.\n")) 
					{
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								document.getElementById('risultatoManagement').style.width="300px";
								document.getElementById('risultatoManagement').innerHTML= this.responseText;
								setTimeout(function()
								{ 
									document.getElementById('risultatoManagement').innerHTML= "";
									document.getElementById('risultatoManagement').style.width="0px";
									eliminaFlagSvuotaLinea();
								}, 3000);
							}
						};
						xmlhttp.open("POST", "riavviaProgrammi.php?", true);
						xmlhttp.send();
					}
				}
				function toggleAngoli()
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText!="ok")
								window.alert(this.responseText);
						}
					};
					xmlhttp.open("POST", "toggleAngoli.php?", true);
					xmlhttp.send();
				}
				function getFlagLanaAngolo()
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText==1)
							{
								document.getElementById("someSwitchOptionPrimary").checked = true;
							}
							if(this.responseText==0)
							{
								document.getElementById("someSwitchOptionPrimary").checked = false;
							}
						}
					};
					xmlhttp.open("POST", "getFlagLanaAngolo.php?", true);
					xmlhttp.send();
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
					<button id="btnRiapriLotto" class="btnManagement" onclick="riapriLotto()" ><span>Gestisci lotti</span></button>
					<button id="btnAggiungiPannelliLotto" class="btnManagement" onclick="aggiungiPannelliLotto()" ><span>Aggiungi pannelli</span></button>
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