<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
	
	$pageName="Importa lotti";
?>
<html>
	<head>
		<title><?php echo $pageName; ?></title>
			<link rel="stylesheet" href="/mi_incollaggio/ufficio/css/styleV3.css" />
			<script src="struttura.js"></script>
			<script>
			function importaLotto()
				{
					//window.alert("ciao");
					document.getElementById("importaLotto").disabled="disabled";
					
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText=="true")
							{
								var interval=setInterval(function()
								{
									setTimeout(function()
									{ 
										document.getElementById("divTempo").innerHTML="L' operazione potrebbe richiedere fino a 5 minuti";
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
								operazioni(interval);
							}
							else
							{
								document.getElementById("risultatoImportaLotto").innerHTML += "<b style='color:red'>Errore: </b>connessione Internet assente";
								document.getElementById("importaLotto").value="Ricarica la pagina";
								document.getElementById("importaLotto").setAttribute('onclick','location.reload()');
								document.getElementById("importaLotto").disabled="";
							}
						}
					};
					xmlhttp.open("POST", "checkInternet.php?", true);
					xmlhttp.send();
				}
				function operazioni(interval)
				{
					importaLotti(interval);
				}
				function importaLotti(interval)
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById("risultatoImportaLotto").innerHTML += this.responseText;
							importaTabelleNewPan(interval);
						}
					};
					xmlhttp.open("POST", "importaLotti.php?", true);
					xmlhttp.send();
				}
				function importaTabelleNewPan(interval)
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById("risultatoImportaLotto").innerHTML += this.responseText;
							controllaImportaPDF(interval);
						}
					};
					xmlhttp.open("POST", "importaTabelleNewPan.php?", true);
					xmlhttp.send();
				}
				function controllaImportaPDF(interval)
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById("risultatoImportaLotto").innerHTML += "<br>" + this.responseText;
							document.getElementById("importaLotto").value="Ricarica la pagina";
							document.getElementById("importaLotto").setAttribute('onclick','location.reload()');
							document.getElementById("importaLotto").disabled="";
							tabellaLotti();
							tabellaLottiPannelli('%');
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
					xmlhttp.open("POST", "controllaImportaPDF.php?", true);
					xmlhttp.send();
				}
				function toggleDisplayMessaggiErrore(lotto)
				{
					var all = document.getElementsByClassName("messaggioErrore"+lotto);
					for (var j = 0; j < all.length; j++) 
					{
						if(all[j].style.display=='none')
							all[j].style.display='block';
						else if(all[j].style.display=='block')
							all[j].style.display='none';
					}
				}
				function tabellaLotti()
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById("tabellaLotti").innerHTML = "<div style='height:30px;line-height:30px;width:100%;font-weight:bold;border-bottom:1px solid #D1D1D1;background:#D1D1D1;color:gray;font-family:Monospace;font-size:115%'>Elenco lotti:</div>" + this.responseText;
						}
					};
					xmlhttp.open("POST", "tabellaLotti.php?", true);
					xmlhttp.send();
				}
				function tabellaLottiPannelli(lotto)
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById("tabellaLottiPannelli").innerHTML = "<div style='height:30px;line-height:30px;width:100%;font-weight:bold;border-bottom:1px solid #D1D1D1;background:#D1D1D1;color:gray;font-family:Monospace;font-size:115%'>Elenco pannelli dei lotti:</div>" + this.responseText;
						}
					};
					xmlhttp.open("POST", "tabellaLottiPannelli.php?lotto="+lotto, true);
					xmlhttp.send();
				}
				function filtroLotto()
				{
					var lotto=document.getElementById("filtroLotto").value;
					//window.alert(lotto);
					tabellaLottiPannelli(lotto);
				}
			</script>
	</head>
	<body onload="tabellaLotti();tabellaLottiPannelli('%')">
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
