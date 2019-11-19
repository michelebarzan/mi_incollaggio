<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
	
	$pageName="Importa lotti";
?>
<html>
	<head>
		<title><?php echo $pageName; ?></title>
		<link rel="stylesheet" href="/mi_incollaggio/ufficio/css/styleV4.css" />
		<script src="struttura.js"></script>
		<script src="js/gestisciLotti.js"></script>
		<link rel="stylesheet" href="js_libraries/spinners/spinner.css" />
		<script src="js_libraries/spinners/spinner.js"></script>
		<link href="fontawesome/css/all.css" rel="stylesheet">
		<script src="js_libraries/jquery/jquery-3.4.1.min.js"></script>
	</head>
	<body onload="tabellaLotti('%');tabellaLottiPannelli('%')">
		<?php include('struttura.php'); ?>
		<div id="container">
			<div id="content">
				<div id="immagineLogo" class="immagineLogo" ></div>
				<input type="button" id="importaLotto" value="Importa lotti" onclick="importaLotto()" />
				<div id="divCaricamento"></div>
				<div id="divTempo"></div>
				<div id="risultatoImportaLotto">
					<div style='height:30px;line-height:30px;width:100%;font-weight:bold;border-bottom:1px solid #D1D1D1;background:#D1D1D1;margin-bottom:10px'>Risultato:</div>
				</div>
				<div id="tabellaLotti">
					<div style='height:30px;line-height:30px;width:100%;font-weight:bold;border-bottom:1px solid #D1D1D1;background:#D1D1D1;color:gray;font-family:Monospace;font-size:115%'>Elenco lotti:</div>
				</div>
				<div id="tabellaLottiPannelli">
					<div style='height:30px;line-height:30px;width:100%;font-weight:bold;border-bottom:1px solid #D1D1D1;background:#D1D1D1;color:gray;font-family:Monospace;font-size:115%'>Elenco pannelli dei lotti:</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<b>Marine&nbspInteriors&nbspS.p.A.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspSegaluzza&nbsp33170&nbspPordenone&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp0434612811
		</div>
	</body>
</html>
