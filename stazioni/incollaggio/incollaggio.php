<!DOCTYPE HTML>
<?php
	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	if(!$conn)
		echo "connessione fallita";
?>
<html>
	<head>
		<title>Incollaggio lana</title>
			<link rel="stylesheet" href="/mi_incollaggio/stazioni/css/styleV3.css" />
			<style>
				/*@import url(http://fonts.googleapis.com/css?family=Exo:100,200,400);
				@import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro:700,400,300);*/
			</style>
			<script>
			function focusOnInput()
			{
				document.getElementById("codiceIncollaggio").focus();
			}
			function logout()
			{
				window.location = 'logout.php';
			}
			function ripristinaSfondo()
			{
				document.getElementById('pdfIncollaggio').innerHTML='';
				document.body.className="";
				document.getElementById('container').className="";
				document.getElementById('content').className="";
				document.getElementById('disegnoIncollaggio').className="";
				document.getElementById('intestazioneIncollaggio').className="";
				document.getElementById('Intestazione2Incollaggio').className="";
				document.getElementById('footer').className="";
			}
			function codice(codice)
			{
				//caso zoom Pdf
				if(codice=="B" || codice=="b")
				{
					zoomPDF();
					document.getElementById('codiceIncollaggio').value="";
				}
				if(codice=="D" || codice=="d")
				{
					document.getElementById('flagProssimo').value="s";
					document.getElementById('btnLogout').click();
					document.getElementById('codiceIncollaggio').value="";
				}
				//caso cambia disegno
				if(codice=="-")
				{
					cambiaDisegno();
					document.getElementById('codiceIncollaggio').value="";
				}
				//caso stampaEtichetta()
				if(codice=="a" || codice=="A")
				{
					stampaEtichetta();
					document.getElementById('codiceIncollaggio').value="";
				}
				//caso scaricapannello
				if(codice=="C" || codice=="c")
				{
					/*scaricaPannello();
					document.getElementById('codiceIncollaggio').value="";*/
					//window.open('scaricaManuale.php');
					document.getElementById('flagProssimo').value="s";
					var lotto=document.getElementById('hiddenLotto').value;
					window.location.href = "scaricaManuale.php?lotto="+lotto;
				}
			}
			function scaricaPannello()
			{
				window.open('scaricaManuale.php','_blank','height='+screen.height+', width='+screen.width);
				//document.getElementById('codiceIncollaggio').style.width="20%";
				//document.getElementById('btnScarica').style.display="inline-block";
			}
			function zoomPDF()
			{
				//window.alert("pesce");
				var codPan=document.getElementById('codPanIncollaggio').innerHTML;
				try
				{
					document.getElementById('zoomPDFLink').setAttribute('href', 'zoomPDF.php?codPan=' + codPan);
					document.getElementById('zoomPDFLink').click();
				}
				catch(err) 
				{
					window.alert( err.message);
				}
			}
			function process(e) 
			{
				var code = (e.keyCode ? e.keyCode : e.which);
				if (code == 13) 
				{
					if(document.getElementById('codiceIncollaggio').value=="")
						prossimoPannello();
					//window.alert("invio");
				}
				if (code == 27) 
				{
					eliminaPannello();
					//window.alert("esc");
				}
				if (code == 32) 
				{
					//window.alert("spazio");
				}
				if (code == 08) 
				{
					//window.alert("bksp");
				}
				if (code == 18) 
				{
					//window.alert("alt");
				}
				if (code == 17) 
				{
					//window.alert("ctrl");
				}
				if (code == 115) 
				{
					//window.alert("f4");
					//window.open('http://www.google.com');
				}
				if (code == 46) 
				{
					//window.alert("canc");
				}
			}
			function getPannello()
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=="nopannelli")
						{
							getLastLotto();
							setTimeout(function(){ getBancale();}, 500);
							document.getElementById('disegnoIncollaggio').innerHTML="<br><br><b style='font-size:300%;color:red;font-family:Monospace'>NESSUN PANNELLO IN CODA</b>";
						}
						else
						{
							var res = this.responseText.split("|");
							var codPan=res[0];
							document.getElementById('codicePannelloIncollaggio').innerHTML="Codice pannello: <b id='codPanIncollaggio' style='color:#3367d6' >"+res[0]+"</b>";
							document.getElementById('id_produzione').value=res[1];
							document.getElementById('id_produzionePannelloIncollaggio').innerHTML="Id pannello: <b style='color:#3367d6' >"+res[1]+"</b>";
							document.getElementById('posizionePannelloIncollaggio').innerHTML="Posizione: <b style='color:#3367d6' >"+res[5]+"</b>";
							document.getElementById('nLotto').innerHTML="Lotto: "+res[2];
							document.getElementById('hiddenLotto').value=res[2];
							document.getElementById('finituraPannelloIncollaggio').innerHTML="Finitura: <b style='color:#3367d6' >"+res[3]+"</b>";
							
							if(res[4]=="true")
							{
								document.getElementById('eliminato').style.display="inline-block";
							}
							setTimeout(function()
							{ 
								var alertProducibilita=0;
								var xmlhttp2 = new XMLHttpRequest();
								xmlhttp2.onreadystatechange = function() 
								{
									if (this.readyState == 4 && this.status == 200) 
									{
										console.log(this.responseText);
										if(this.responseText.indexOf('1') > -1)
										{
											alertProducibilita=1;
											document.body.className="containerAlertProducibilita";
											document.getElementById('container').className="containerAlertProducibilita";
											document.getElementById('content').className="containerAlertProducibilita";
											document.getElementById('disegnoIncollaggio').className="containerAlertProducibilita";
											document.getElementById('intestazioneIncollaggio').className="containerAlertProducibilita";
											document.getElementById('Intestazione2Incollaggio').className="containerAlertProducibilita";
											document.getElementById('footer').className="containerAlertProducibilita";
										}
										pdfIncollaggio(alertProducibilita);
									}
								};
								xmlhttp2.open("POST", "controllaProducibilita.php?codPan=" + codPan, true);
								xmlhttp2.send();
								getBancale();
								disegnaRinforzi();
								controllaRuotato();
							}, 500)
						}
					}
				};
				xmlhttp.open("POST", "getPannello.php?", true);
				xmlhttp.send();
			}
			function getLastLotto()
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('nLotto').innerHTML="Lotto: "+this.responseText;
						document.getElementById('hiddenLotto').value=this.responseText;
					}
				};
				xmlhttp.open("POST", "getLastLotto.php?", true);
				xmlhttp.send();
			}
			function getBancale()
			{
				var lotto=document.getElementById('hiddenLotto').value;
				if(lotto!='')
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText=="chiuso")
							{
								window.alert("chiuso");
							}
							else
							{
								var res = this.responseText.split("|");
						
								document.getElementById('hiddenId_bancale').value=res[0];
								document.getElementById('bancaleCorrente').innerHTML="Bancale corrente: <b style='color:#3367d6' >"+res[1]+"</b>";
								document.getElementById('hiddenNumeroBancale').value=res[2];
							}
						}
					};
					xmlhttp.open("POST", "getBancale.php?lotto="+lotto, true);
					xmlhttp.send();
				}
			}
			function disegnaLana()
			{
				try
				{
					var codPan=document.getElementById('codPanIncollaggio').innerHTML;
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById('disegnoVisualizzato').innerHTML="Lana";
							document.getElementById('disegnoIncollaggio').innerHTML=this.responseText;
						}
					};
					xmlhttp.open("POST", "disegnoIncollaggioLana.php?codPan="+codPan, true);
					xmlhttp.send();
				}
				catch(e)
				{
					document.getElementById('disegnoIncollaggio').innerHTML="<br><br><b style='font-size:300%;color:red;font-family:Monospace'>NESSUN PANNELLO IN CODA</b>";
				}
			}
			function disegnaRinforzi()
			{
				try
				{
					var codPan=document.getElementById('codPanIncollaggio').innerHTML;
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById('disegnoVisualizzato').innerHTML="Pannello";
							document.getElementById('disegnoIncollaggio').innerHTML=this.responseText;
						}
					};
					xmlhttp.open("POST", "disegnoIncollaggioRinforzi.php?codPan="+codPan, true);
					xmlhttp.send();
				}
				catch(e)
				{
					document.getElementById('disegnoIncollaggio').innerHTML="<br><br><b style='font-size:300%;color:red;font-family:Monospace'>NESSUN PANNELLO IN CODA</b>";
				}				
			}
			function cambiaDisegno()
			{
				var disegnoVisualizzato =document.getElementById('disegnoVisualizzato').innerHTML;
				if(disegnoVisualizzato=='Pannello')
					disegnaLana();
				if(disegnoVisualizzato=='Lana')
					disegnaRinforzi();
			}
			function controllaRuotato()
			{
				try
				{
					var codPan=document.getElementById('codPanIncollaggio').innerHTML;
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById('ruotatoIncollaggio').innerHTML=this.responseText;
						}
					};
					xmlhttp.open("POST", "controllaRuotato.php?codPan="+codPan, true);
					xmlhttp.send();
				}
				catch(err)
				{
					
				}
			}
			function pdfIncollaggio(alertProducibilita)
			{
				try
				{
					var codPan=document.getElementById('codPanIncollaggio').innerHTML;
					if(alertProducibilita==0)
						document.getElementById('pdfIncollaggio').innerHTML='<a href="/mi_incollaggio/stazioni/images/'+codPan+'.pdf" target="_blank" style="height:800px" ><object data="/mi_incollaggio/stazioni/disegni/PdfPan/'+codPan+'.pdf" type="application/pdf" width="380px" height="580"></object></a>';
					else
						document.getElementById('pdfIncollaggio').innerHTML='<a href="/mi_incollaggio/stazioni/images/'+codPan+'.pdf" target="_blank" style="height:800px" ><div class="alertProducibilita">Controlla il PDF</div><object data="/mi_incollaggio/stazioni/disegni/PdfPan/'+codPan+'.pdf" type="application/pdf" width="345x" height="530"></object></a>';
					//document.getElementById('pdfIncollaggio').innerHTML='<a href="/mi_incollaggio/stazioni/images/'+codPan+'.pdf" target="_blank" style="height:800px" ><div class="alertProducibilita">Controlla il PDF</div><object data="/mi_incollaggio/stazioni/disegni/PdfPan/'+codPan+'.pdf" type="application/pdf" width="380px" height="580"></object></a>';
				}
				catch(err)
				{
					
				}
			}
			function prossimoPannello()
			{
				try
				{
					var codPan=document.getElementById('codPanIncollaggio').innerHTML;
				}
				catch(err)
				{
					codPan=0;
				}
				if(codPan==0)
				{
					document.getElementById('flagProssimo').value="s";
					location.reload();
				}
				else
				{
					var bancale= document.getElementById('hiddenId_bancale').value;
					var id_produzione=document.getElementById('id_produzione').value;
					//contolla mIncollaggio e setta vIncollaggio false
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText=="ok")
							{
								document.getElementById('flagProssimo').value="s";
								location.reload();
							}
							else
								window.alert(this.responseText);
						}
					};
					xmlhttp.open("POST", "vIncollaggio.php?codPan="+codPan+"&id_produzione="+id_produzione+"&bancale="+bancale, true);
					xmlhttp.send();
				}
			}
			function eliminaPannello()
			{
				var id_produzione=document.getElementById('id_produzione').value;
				if(id_produzione!=0)
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText=="ok")
							{
								document.getElementById('flagProssimo').value="s";
								location.reload();
							}
						}
					};
					xmlhttp.open("POST", "eliminaPannello.php?id_produzione="+id_produzione, true);
					xmlhttp.send();
				}
			}
			function ricarica() 
			{
				var flagProssimo=document.getElementById('flagProssimo').value;
				if(flagProssimo=="n")
					return "Stai uscendo dal programma";
			}
			function autoGetPannello()
			{
				setTimeout(function()
				{ 
					var id_produzione=document.getElementById('id_produzione').value;
					//window.alert(id_produzione);
					setInterval(function()
					{
						//window.alert(id_produzione);
						if(id_produzione==0)
						{
							var xmlhttp = new XMLHttpRequest();
							xmlhttp.onreadystatechange = function() 
							{
								if (this.readyState == 4 && this.status == 200) 
								{
									if(this.responseText=="nopannelli")
									{
										//document.getElementById('flagProssimo').value="s";
										//location.reload();
									}
									else
									{
										document.getElementById('flagProssimo').value="s";
										location.reload();
									}
								}
							};
							xmlhttp.open("POST", "getPannello.php?", true);
							xmlhttp.send();
						}
						else
						{
							//altrimenti controlla se il pannello con questo id ha passato l incollaggio Incollaggio, se si aggiorna, altrimenti no action
							var xmlhttp = new XMLHttpRequest();
							xmlhttp.onreadystatechange = function() 
							{
								if (this.readyState == 4 && this.status == 200) 
								{
									//console.log(id_produzione);
									//console.log(this.responseText);
									if(this.responseText=="true")
									{
										prossimoPannello();
										console.log("Pulsante premuto");
									}
									if(this.responseText=="false")
										console.log("Attesa pulsante");
									if(this.responseText!="false" && this.responseText!="true")
										console.log(this.responseText);
								}
							};
							xmlhttp.open("POST", "controllaMIncollaggio.php?id_produzione="+id_produzione, true);
							xmlhttp.send();
						}
					},1000);
				}, 2000)
			}
			function controllaFlagSvuotaLinea()
			{
				setInterval(function()
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText=="1")
							{
								document.getElementById('flagProssimo').value="s";
								//setTimeout(function(){eliminaFlagSvuotaLinea();}, 3000);
								window.location = 'logout.php';
							}
						}
					};
					xmlhttp.open("POST", "controllaFlagSvuotaLinea.php?", true);
					xmlhttp.send();
				},500);
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
			function stampaEtichetta()
			{
				var id_bancale=document.getElementById('hiddenId_bancale').value;
				chiudiBancale(id_bancale);
				var myWindow = window.open("stampaEtichetta.php?id_bancale="+ id_bancale,"","width=500,height=500");
				setTimeout(function(){ getBancale();}, 500);
			}
			function chiudiBancale(id_bancale)
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
				xmlhttp.open("POST", "chiudiBancale.php?id_bancale="+id_bancale, true);
				xmlhttp.send();
			}
			function qntPannelliBancale()
			{
				setInterval(function()
				{
					try
					{
						var bancale= document.getElementById('hiddenId_bancale').value;
					}
					catch(e)
					{
						var bancale=0;
					}
					
					if(bancale!=0)
					{
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								document.getElementById('qntPannelliBancale').innerHTML=this.responseText;
								document.getElementById('hiddenQntPannelliBancale').value=this.responseText;
								/*if(this.responseText==24)
									stampaEtichetta();*/
							}
						};
						xmlhttp.open("POST", "qntPannelliBancale.php?bancale="+bancale, true);
						xmlhttp.send();
					}
				},200);
			}
			function scarica()
			{
				var codice=document.getElementById('codiceIncollaggio').value;
				if(codice.charAt(0)=="+" && codice.length==10)
				{
					//var id_bancale= document.getElementById('hiddenId_bancale').value;
					//var id_produzione=document.getElementById('id_produzione').value;
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText=="ok")
							{
								document.getElementById('codiceIncollaggio').style.width="30%";
								document.getElementById('btnScarica').style.display="none";
								document.getElementById('codiceIncollaggio').value="Pannello scaricato";
								setTimeout(function()
								{ 
									document.getElementById('codiceIncollaggio').value="";
								}, 3000)
							}
							else
								window.alert(this.responseText);
						}
					};
					xmlhttp.open("POST", "scaricaPannello.php?codice="+codice+"&lotto="+lotto,true);
					xmlhttp.send();
				}
				else
					window.alert("Codice non valido");
			}
		</script>
	</head>
	<body onload="focusOnInput();getPannello();autoGetPannello();controllaFlagSvuotaLinea();qntPannelliBancale()" onclick="focusOnInput()" onmouseover="focusOnInput()" onbeforeunload="return ricarica()" >
		<input type="hidden" id="flagProssimo" value="n" />
		<input type="hidden" id="id_produzione" value="0" />
		<input type="hidden" id="hiddenLotto" value="" />
		<input type="hidden" id="hiddenId_bancale" value="" />
		<input type="hidden" id="hiddenNumeroBancale" value="" />
		<input type="hidden" id="hiddenQntPannelliBancale" value="" />
		<div id="eliminato">PANNELLO ELIMINATO</div>
		<div id="container">
			<div id="header" class="header" >
				<a href="zoomPDF.php" id="zoomPDFLink" target="_blank" style="display:none" ></a>
				<div id="pageName" class="pageName">
					Stazione incollaggio lana
				</div>
				<div id="lottoSelezionato">
					<div id="nLotto" style="display:inline-block">Lotto:</div>
				</div>
				<div id="user" class="user">
					<div id="username"><?php echo $_SESSION['Username']; ?></div>
					<input type="button" value="Logout" id="btnLogout" onclick="logout()">
				</div>
			</div>
			<div id="content">
				<div id="intestazioneIncollaggio">
					<div id="logo" ></div>
					<input type="text" name="codiceIncollaggio" id="codiceIncollaggio" onkeyup="codice(this.value);process(event, this);"  value="" placeholder="Codice" />
					<input type="button" value="Scarica" id="btnScarica" onclick="scarica()" />
					<div id="datiPannelloIncollaggio" >
						<div id="codicePannelloIncollaggio">Codice pannello:</div>
						<div id="finituraPannelloIncollaggio">Finitura:</div>
						<div id="id_produzionePannelloIncollaggio">Id pannello:</div>
						<div id="posizionePannelloIncollaggio">Posizione:</div>
					</div>
				</div>
				<div id="Intestazione2Incollaggio">
					<div id="bancaleEtichette">
						<div id="qntPannelliBancale"></div>
						<div id="bancaleCorrente"></div>
						<input type="button" value="Stampa etichetta" id="btnStampaEtichetta" onclick="stampaEtichetta()" />
					</div>
					<div id="comandiDisegno">
						<input type="button" value=" " id="btnCambiaDisegno1" onclick="cambiaDisegno()" />
						<div id="disegnoVisualizzato"></div>
						<input type="button" value=" " id="btnCambiaDisegno2" onclick="cambiaDisegno()" />
					</div>
				</div>
				<div id="contenitorePdfIncollaggio">
					<div id="ruotatoIncollaggio">
					</div>
					<div id="pdfIncollaggio">
					</div>
					<input type="button" value="Prossimo pannello" id="btnProssimoIncollaggio" onclick="prossimoPannello()">
					<input type="button" value="Elimina pannello" id="btnEliminaIncollaggio" onclick="eliminaPannello()">
				</div>
				<div id="disegnoIncollaggio"></div>
			</div>
			<div id="footer">
				<hr size='1' style='border-color:#80B3E6;'>
				<b>Marine&nbspInteriors&nbspS.p.A.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspSegaluzza&nbsp33170&nbspPordenone&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp0434612811
			</div>
		</div>
	</body>
</html>


<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php
?>