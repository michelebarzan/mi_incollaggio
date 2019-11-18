<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
	
	$pageName="Homepage";
?>
<html>
	<head>
		<title><?php echo $pageName; ?></title>
			<link rel="stylesheet" href="/mi_incollaggio/ufficio/css/styleV3.css" />
			<script src="struttura.js"></script>
	</head>
	<body>
		<?php include('struttura.php'); ?>
		<div id="container">
			<div id="content">
				<div id="immagineLogo" class="immagineLogo" ></div>
				<div id="actionList">
					<div class="linkList" onclick="gotopath('gestisciLinea.php')" >Gestisci la linea incollaggio<input type="button" class="link" value=" " onclick="gotopath('gestisciLinea.php')"/></div><br>
					<div class="linkList" onclick="gotopath('gestisciLotti.php')" >Importa i lotti, i dati e i disegni dei pannelli<input type="button" class="link" value=" " onclick="gotopath('gestisciLotti.php')"/></div><br>
					<!--<div class="linkList" onclick="gotopath('gestisciDXF.php')" >Importa e gestisci i dxf<input type="button" class="link" value=" " onclick="gotopath('gestisciDXF.php')"/></div><br>-->
					<div class="linkList" onclick="gotopath('scaffalaturaRinforzi.php')" >Imposta l' ordine dei rinforzi sugli scaffali<input type="button" class="link" value=" " onclick="gotopath('scaffalaturaRinforzi.php')"/></div><br>
					<div class="linkList" onclick="gotopath('gestioneLane.php')" >Gestione lane<input type="button" class="link" value=" " onclick="gotopath('gestioneLane.php')"/></div><br>
					<div class="linkList" onclick="gotopath('tabelleParametri.php')" >Tabelle parametri<input type="button" class="link" value=" " onclick="gotopath('tabelleParametri.php')"/></div><br>
					<div class="linkList" onclick="gotopath('estrazioneRinforzi.php')" >Estrazione rinforzi<input type="button" class="link" value=" " onclick="gotopath('estrazioneRinforzi.php')"/></div><br>
				</div>
			</div>
		</div>
		<div id="push"></div>
		<div id="footer">
			<b>Marine&nbspInteriors&nbspS.p.A.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspSegaluzza&nbsp33170&nbspPordenone&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp0434612811
		</div>
	</body>
</html>











