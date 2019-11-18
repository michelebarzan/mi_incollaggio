<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
	
	$pageName="Gestione dxf";
?>
<html>
	<head>
		<title><?php echo $pageName; ?></title>
			<link rel="stylesheet" href="/mi_incollaggio/ufficio/css/styleV3.css" />
			<script src="struttura.js"></script>
			<script>
				function importaDXF()
				{
					document.getElementById("importaDXF").disabled="disabled";
					var internet="true";
					checkInternet(internet);
					if(internet=="true")
					{
						var interval=setInterval(function()
						{
							setTimeout(function()
							{ 
								document.getElementById("divTempo").innerHTML="L' operazione potrebbe richiedere fino a 2 minuti";
							}, 500);
							document.getElementById("divCaricamento").innerHTML="Caricamento.";
							setTimeout(function()
							{ 
								document.getElementById("divCaricamento").innerHTML="Caricamento..";
							}, 500);
							setTimeout(function()
							{ 
								document.getElementById("divCaricamento").innerHTML="Caricamento...";
							}, 1000);
						},1500);
						importaTabelleNewPan();
						setTimeout(function()
						{ 
							controllaImportaDXF(interval);
						}, 2000);
					}
					else
					{
						document.getElementById("risultatoImportaDXF").innerHTML += "<b style='color:red'>Errore: </b>connessione Internet assente";
						document.getElementById("importaDXF").value="Ricarica la pagina";
						document.getElementById("importaDXF").setAttribute('onclick','location.reload()');
						document.getElementById("importaDXF").disabled="";
					}
				}
				function checkInternet(internet)
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							internet= this.responseText;
						}
					};
					xmlhttp.open("POST", "checkInternet.php?", true);
					xmlhttp.send();
				}
				function importaTabelleNewPan()
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById("risultatoImportaDXF").innerHTML += this.responseText;
						}
					};
					xmlhttp.open("POST", "importaTabelleNewPan.php?", true);
					xmlhttp.send();
				}
				function controllaImportaDXF(interval)
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById("risultatoImportaDXF").innerHTML += "<br>" + this.responseText;
							document.getElementById("importaDXF").value="Ricarica la pagina";
							document.getElementById("importaDXF").setAttribute('onclick','location.reload()');
							document.getElementById("importaDXF").disabled="";
							setTimeout(function()
							{ 
								clearInterval(interval);
							}, 500);
							setTimeout(function()
							{ 
								document.getElementById("divCaricamento").innerHTML="";
								document.getElementById("divTempo").innerHTML="";
							}, 1500);
						}
					};
					xmlhttp.open("POST", "controllaImportaDXF.php?", true);
					xmlhttp.send();
				}
			</script>
	</head>
	<body>
		<?php include('struttura.php'); ?>
		<div id="container">
			<div id="content">
				<div id="immagineLogo" class="immagineLogo" ></div>
				<input type="button" id="importaDXF" value="Importa dxf" onclick="importaDXF()" />
				<div id="divCaricamento"></div>
				<div id="divTempo"></div>
				<div id="risultatoImportaDXF">
					<div style='height:30px;line-height:30px;width:100%;font-weight:bold;border-bottom:1px solid #D1D1D1;background:#D1D1D1;margin-bottom:10px'>Risultato:</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<b>Marine&nbspInteriors&nbspS.p.A.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspSegaluzza&nbsp33170&nbspPordenone&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp0434612811
		</div>
	</body>
</html>
