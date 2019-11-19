<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
	
	$pageName="Tabelle parametri";
?>
<html>
	<head>
		<title><?php echo $pageName; ?></title>
		<link rel="stylesheet" href="/mi_incollaggio/ufficio/css/styleV4.css" />
		<script src="struttura.js"></script>
		<script src="js/tabelleParametri.js"></script>
		<link rel="stylesheet" href="js_libraries/spinners/spinner.css" />
		<script src="js_libraries/spinners/spinner.js"></script>
		<script src="editableTableParametri/editableTable.js"></script>
		<link rel="stylesheet" href="editableTableParametri/editableTable.css" />
		<script src="js_libraries/jquery/jquery-3.4.1.min.js"></script>
		<link href="fontawesome/css/all.css" rel="stylesheet">
	</head>
	<body>
		<?php include('struttura.php'); ?>
		<div id="container">
			<div id="content">
				<div id="immagineLogo" class="immagineLogo" ></div>
				<div class="funcionListContainer" style="top:280;">
					<div class="functionList">
						<button class="functionListButton" onclick="resetStyle(this);getTabellaParametriLana()">Parametri lana</button>
						<button class="functionListButton" onclick="resetStyle(this);getTable('parametri')">Parametri applicazione</button>
					</div>
				</div>
				<span id="rowsNumEditableTable" style="display:none"></span>
				<div id="containerTabellaParametri" style="margin-top:80px"></div>
		</div>				
		<div id="push"></div>
		<div id="footer">
			<b>Marine&nbspInteriors&nbspS.p.A.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspSegaluzza&nbsp33170&nbspPordenone&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp0434612811
		</div>
	</body>
</html>