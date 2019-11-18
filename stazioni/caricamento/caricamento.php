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
		<title>Caricamento</title>
			<link rel="stylesheet" href="/mi_incollaggio/stazioni/css/styleV3.css" />
			<style>
				/* width */
				::-webkit-scrollbar 
				{
					width: 30px;
					/*height: 10px;*/
				}
				/* Track */
				::-webkit-scrollbar-track {
					background: #f1f1f1; 
				}
				 
				/* Handle */
				::-webkit-scrollbar-thumb {
					background: #888; 
				}

				/* Handle on hover */
				::-webkit-scrollbar-thumb:hover {
					background: #555; 
				}
			</style>
			<script>
			function focusOnInput()
			{
				document.getElementById("codiceCaricamento").focus();
			}
			function logout()
			{
				window.location = 'logout.php';
			}
			function codice(codice)
			{				
				document.getElementById('ruotatoCaricamento').innerHTML="";
				document.getElementById('ruotatoCaricamento').style.background="white";
				var l= codice.toString().length;
				//caso lotto
				if(l==2)
				{
					ripristinaSfondo();
					document.getElementById('pdfCaricamento').innerHTML='';
					document.getElementById('codicePannelloCaricamento').innerHTML="Codice pannello:";
					document.getElementById('finituraPannelloCaricamento').innerHTML="Finitura:";
					var myElem = document.getElementById('rigaLotto'+codice);
					if (myElem != null) 
					{
						if(document.getElementById('nLotto').innerHTML!="Lotto:")
						{
							var codiceOld=document.getElementById('nLottoScelto').value;
							document.getElementById('rigaLotto'+codiceOld).style.background="white";
							document.getElementById('colonnaLotto'+codiceOld).style.color="gray";
							document.getElementById('colonnaLotto'+codiceOld).style.fontWeight="normal";
							document.getElementById('colonnaNumeroLotto'+codiceOld).style.color="gray";
							document.getElementById('colonnaNumeroLotto'+codiceOld).style.fontWeight="normal";
							document.getElementById('nLotto').innerHTML="Lotto: ";
							document.getElementById('hiddenLotto').value=0;
							document.getElementById("pannelliMancanti").innerHTML ="";
						}
						var lotto = document.getElementById('colonnaLotto'+codice).innerHTML;
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								if(this.responseText=="ok")
								{
									document.getElementById('nLottoScelto').value=codice;
									myElem.style.background="#f1f1f1";
									document.getElementById('colonnaLotto'+codice).style.color="#3367d6";
									document.getElementById('colonnaLotto'+codice).style.fontWeight="bold";
									document.getElementById('colonnaNumeroLotto'+codice).style.color="#3367d6";
									document.getElementById('colonnaNumeroLotto'+codice).style.fontWeight="bold";
									document.getElementById('nLotto').innerHTML="Lotto: "+lotto;
									document.getElementById('hiddenLotto').value=lotto;
									pannelliMancanti(lotto);
								}
								else
								{
									window.alert(this.responseText);
								}
							}
						};
						xmlhttp.open("POST", "controllaLotto.php?lotto=" + lotto, true);
						xmlhttp.send();
						document.getElementById('codiceCaricamento').value="";
					}
				}
				
				//caso codpan
				if(codice.charAt(0)=="^")
				{
					if(document.getElementById('nLotto').innerHTML=="Lotto:")
					{
						window.alert("Devi prima selezionare un lotto");
						document.getElementById('codiceCaricamento').value="";
					}
					else
					{
						if(codice.length==10)
						{
							var lotto =document.getElementById("hiddenLotto").value;
							var codPan=codice;
							codPan=codPan.replace('^','+');
							//controllo codPan e mostra finitura
							var finitura="";
							var xmlhttp = new XMLHttpRequest();
							xmlhttp.onreadystatechange = function() 
							{
								if (this.readyState == 4 && this.status == 200) 
								{
									//window.alert(this.responseText);
									if(this.responseText=="errore")
									{
										document.getElementById('ruotatoCaricamento').style.background="red";
										document.getElementById('ruotatoCaricamento').style.height="300px";
										document.getElementById('ruotatoCaricamento').style.borderRadius="5px";
										document.getElementById('ruotatoCaricamento').innerHTML="<b style='height:300px;line-height:60px;color:white;font-family:Exo,Arial;font-size:200%'>Pannello "+codPan+" non presente o finito nel lotto<br> "+lotto+"</b>";
										document.getElementById('codiceCaricamento').value="";
										document.getElementById('pdfCaricamento').innerHTML='';
										document.getElementById('codicePannelloCaricamento').innerHTML="Codice pannello:";
										document.getElementById('finituraPannelloCaricamento').innerHTML="Finitura:";
									}
									else
									{
										ripristinaSfondo();
										finitura = this.responseText;
										document.getElementById('finituraPannelloCaricamento').innerHTML="Finitura: <b style='color:#3367d6' >"+finitura+"</b>";
										document.getElementById('hiddenFinitura').value=finitura;
										//Pannello ruotato
										var xmlhttp2 = new XMLHttpRequest();
										xmlhttp2.onreadystatechange = function() 
										{
											if (this.readyState == 4 && this.status == 200) 
											{
												document.getElementById("hiddenRuotato").value=this.responseText;
												if(this.responseText=="true")
													document.getElementById("ruotatoCaricamento").innerHTML = "<div style='height:110px;margin-top:345px;width:100%;overflow:hidden;border:3px solid red;color:red;background:yellow;font-family:Exo,Arial;font-size:120%;font-weight:bold;' ><input type='button' id='alert' value='' /><br>Attenzione !<br>Pannello ruotato</div>";
											}
										};
										xmlhttp2.open("POST", "controllaRuotato.php?codPan=" + codPan, true);
										xmlhttp2.send();
										
										//controllo producibilita
										var alertProducibilita=0;
										var xmlhttp2 = new XMLHttpRequest();
										xmlhttp2.onreadystatechange = function() 
										{
											if (this.readyState == 4 && this.status == 200) 
											{
												if(this.responseText.indexOf('1') > -1)
												{
													alertProducibilita=1;
													document.body.className="containerAlertProducibilita";
													document.getElementById('container').className="containerAlertProducibilita";
													document.getElementById('content').className="containerAlertProducibilita";
													document.getElementById('contenutoCaricamento').className="containerAlertProducibilita";
													document.getElementById('intestazioneCaricamento').className="containerAlertProducibilita";
													document.getElementById('ruotatoCaricamento').className="containerAlertProducibilita";
													document.getElementById('footer').className="containerAlertProducibilita";
												}
												if(alertProducibilita==0)
													document.getElementById('pdfCaricamento').innerHTML='<object data="/mi_incollaggio/stazioni/disegni/PdfPan/'+codPan+'.pdf" type="application/pdf" width="540" height="800"></object>';
												else
													document.getElementById('pdfCaricamento').innerHTML='<div class="alertProducibilita">Controlla il PDF</div><object data="/mi_incollaggio/stazioni/disegni/PdfPan/'+codPan+'.pdf" type="application/pdf" width="500" height="750"></object>';
											}
										};
										xmlhttp2.open("POST", "controllaProducibilita.php?codPan=" + codPan, true);
										xmlhttp2.send();
										
										
										document.getElementById('codicePannelloCaricamento').innerHTML="Codice pannello: <b id='codPanCaricamento' style='color:#3367d6' >"+codPan+"</b>";
										//document.getElementById('finituraPannelloCaricamento').innerHTML="Finitura: <b style='color:#3367d6' >"+finitura+"</b>";
										document.getElementById('codiceCaricamento').value="";
									}
								}
							};
							xmlhttp.open("POST", "getFinitura.php?codPan=" + codPan + "&lotto="+lotto, true);
							xmlhttp.send();
						}
					}
				}
				
				//caso carica pannello
				if(codice== "CARICAPANNELLO")
				{
					caricaPannello();
					document.getElementById('codiceCaricamento').value="";
				}
				
				//caso zoom Pdf
				if(codice=="B" || codice=="b")
				{
					zoomPDF();
					document.getElementById('codiceCaricamento').value="";
				}
				
				//caso C
				if(codice=="C" || codice=="c")
				{
					document.getElementById('codiceCaricamento').value="^K4PN";
				}
				
				//caso logout
				if(codice=="D" || codice=="d")
				{
					document.getElementById('flagProssimo').value="s";
					document.getElementById('btnLogout').click();
					document.getElementById('codiceCaricamento').value="";
				}
				
				//caso chiudi lotto
				if(codice=="A" || codice=="a")
				{
					chiudiLotto();
					document.getElementById('codiceCaricamento').value="";
				}
				
			}
			function zoomPDF()
			{
				//window.alert("pesce");
				var codPan=document.getElementById('codPanCaricamento').innerHTML;
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
			function caricaPannello()
			{
				if(document.getElementById('nLotto').innerHTML!="Lotto:" && document.getElementById('codicePannelloCaricamento').innerHTML!="Codice pannello:")
				{
					var lotto=document.getElementById('nLotto').innerHTML.substring(6);
					var codPan=document.getElementById('codPanCaricamento').innerHTML;
					//ruotato
					var ruotato=document.getElementById('hiddenRuotato').value;
					/*if(document.getElementById('ruotatoCaricamento').innerHTML=="")
						ruotato="false";
					else
						ruotato="true";*/
					
					var finitura=document.getElementById('hiddenFinitura').value;
					
					//window.alert(ruotato);
					
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							//window.alert(this.responseText);
							document.getElementById('pdfCaricamento').innerHTML='';
							document.getElementById('codicePannelloCaricamento').innerHTML="Codice pannello:";
							document.getElementById('finituraPannelloCaricamento').innerHTML="Finitura:";
							document.getElementById('ruotatoCaricamento').style.background="#07D000";
							document.getElementById('ruotatoCaricamento').style.height="300px";
							document.getElementById('ruotatoCaricamento').style.borderRadius="5px";
							document.getElementById('ruotatoCaricamento').innerHTML="<b style='height:300px;line-height:100px;color:white;font-family:Exo,Arial;font-size:200%'>Pannello "+codPan+" caricato</b>";
						
							var lotto = document.getElementById('hiddenLotto').value;
							pannelliMancanti(lotto);
							
							ripristinaSfondo();
						}
					};
					xmlhttp.open("POST", "caricaPannello.php?lotto=" + lotto + "&codPan=" + codPan + "&ruotato=" + ruotato + "&finitura=" + finitura, true);
					xmlhttp.send();
					
					//location.reload();
					//window.alert(codPan);
				}
				if(document.getElementById('nLotto').innerHTML=="Lotto:")
				{
					window.alert("Devi prima selezionare un lotto");
					document.getElementById('codiceCaricamento').value="";
				}
				if(document.getElementById('codicePannelloCaricamento').innerHTML=="Codice pannello:")
				{
					window.alert("Devi prima selezionare un pannello");
					document.getElementById('codiceCaricamento').value="";
				}
			}
			function ripristinaSfondo()
			{
				document.getElementById('pdfCaricamento').innerHTML='';
				document.body.className="";
				document.getElementById('container').className="";
				document.getElementById('content').className="";
				document.getElementById('contenutoCaricamento').className="";
				document.getElementById('intestazioneCaricamento').className="";
				document.getElementById('ruotatoCaricamento').className="";
				document.getElementById('footer').className="";
			}
			function process(e) 
			{
				var code = (e.keyCode ? e.keyCode : e.which);
				if (code == 13) 
				{
					//if(document.getElementById('codiceCaricamento').value=="")
						//caricaPannello();
					//window.alert("invio");
				}
				if (code == 27) 
				{
					//window.alert("esc");
					if(document.getElementById('codiceCaricamento').value=="")
						caricaPannello();
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
			function ricarica() 
			{
				var flagProssimo=document.getElementById('flagProssimo').value;
				if(flagProssimo=="n")
					return "Stai uscendo dal programma";
			}
			function svuotaLinea()
			{
				var password= prompt("Inserisci la password");
				if(password=="abc")
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							window.alert(this.responseText);
							document.getElementById("flagProssimo").value="s";
							location.reload();
						}
					};
					xmlhttp.open("POST", "svuotaLinea.php?" , true);
					xmlhttp.send();
				}
				else
					window.alert("Password errata");
			}
			function elencoLotti()
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById("elencoLotti").innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "elencoLotti.php?" , true);
				xmlhttp.send();
			}
			function chiudiLotto()
			{
				var lotto =document.getElementById("hiddenLotto").value;
				if(lotto!=0)
				{
					var codice =document.getElementById("nLottoScelto").value;
					var completato=document.getElementById('colonnaCompletato'+codice).innerHTML;
					if(completato=="X")
					{
						document.getElementById('popup').innerHTML="Il lotto <b>"+lotto+"</b> non e' stato completato.<br>Vuoi chiuderlo comunque?<br>(A = chiudi) (B = annulla)<br><input type='text' id='chiudiPopup' onkeyup='chiudiPopup()' />";
						document.getElementById('popup').style.display="inline-block";
						document.getElementById('chiudiPopup').focus();
					}
					else
					{
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								//window.alert(this.responseText);
								document.getElementById("flagProssimo").value="s";
								location.reload();
							}
						};
						xmlhttp.open("POST", "chiudiLotto.php?lotto="+lotto , true);
						xmlhttp.send();
					}
				}
			}
			function chiudiPopup()
			{
				var lotto =document.getElementById("hiddenLotto").value;
				var chiudo=document.getElementById('chiudiPopup').value;
				document.getElementById('popup').innerHTML="";
				document.getElementById('popup').style.display="none";
				if(chiudo=="a" || chiudo=="A")
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							//window.alert(this.responseText);
							document.getElementById("flagProssimo").value="s";
							location.reload();
						}
					};
					xmlhttp.open("POST", "chiudiLotto.php?lotto="+lotto , true);
					xmlhttp.send();
				}
				else
					focusOnInput();
			}
			function pannelliMancanti(lotto)
			{
				if(lotto!='' || lotto!=NULL || lotto!=0)
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById("pannelliMancanti").innerHTML = this.responseText;
							if(this.responseText.indexOf("reload")>0)
							{
								document.getElementById("pannelliMancanti").innerHTML = "<b style='height:300px;line-height:60px;color:blue;font-family:Exo,Arial;font-size:200%'>Lotto "+lotto+"<br> completato.<br> Premi 'A' per chiuderlo</b>";
								elencoLotti();
								//document.getElementById("flagProssimo").value="s";
								//location.reload();
							}
						}
					};
					xmlhttp.open("POST", "pannelliMancanti.php?lotto=" + lotto, true);
					xmlhttp.send();
				}
				else
					document.getElementById("pannelliMancanti").innerHTML ="";
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
								console.log("prova");
							}
						}
					};
					xmlhttp.open("POST", "controllaFlagSvuotaLinea.php?", true);
					xmlhttp.send();
				},500);
			}
			function controllaStatoMacchina()
			{
				setInterval(function()
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.split("|")[0]=="2")
							{
								window.alert("Errore macchinari.\nMessaggio: ["+this.responseText.split("|")[1]+"]");
							}
						}
					};
					xmlhttp.open("POST", "controllaStatoMacchina.php?", true);
					xmlhttp.send();
				},100);
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
			function caricaDiNuovo()
			{
				window.alert("Funzione disabilitata");
				/*var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
						{
							window.alert(this.responseText);
						}
						else
						{
							document.getElementById('codiceCaricamento').value=this.responseText;
							codice(this.responseText);
						}
					}
				};
				xmlhttp.open("POST", "caricaDiNuovo.php?", true);
				xmlhttp.send();*/
			}
		</script>
	</head>
	<body onload="focusOnInput();elencoLotti();controllaFlagSvuotaLinea();controllaStatoMacchina()" onclick="focusOnInput()" onmouseover="focusOnInput()" onbeforeunload="return ricarica()" >
		<input type="hidden" id="flagProssimo" value="n" />
		<input type="hidden" id="nLottoScelto" value="0" />
		<input type="hidden" id="hiddenFinitura" value="" />
		<input type="hidden" id="hiddenRuotato" value="" />
		<div id='popup'></div>
		<div id="container">
			<div id="header" class="header" >
				<a href="javascript:void(0)" onclick="fullscreen('zoomPDF.php')" id="zoomPDFLink" target="_blank" style="display:none" ></a>
				<div id="pageName" class="pageName">
					Stazione caricamento
				</div>
				<div id="lottoSelezionato">
					<div id="nLotto" style="display:inline-block">Lotto:</div>
					<!--<input type="button" id="svuotaLinea" value="SVUOTA LINEA" onclick="svuotaLinea()" />-->
					<input type="hidden" id="hiddenLotto" value="0" />
					<!--<input type="button" value="Chiudi lotto" id="btnChiudiLotto" onclick="ChiudiLotto();" />-->
				</div>
				<div id="user" class="user">
					<div id="username"><?php echo $_SESSION['Username']; ?></div>
					<input type="button" value="Logout" id="btnLogout" onclick="logout()">
				</div>
			</div>
			<div id="content">
				<div id="intestazioneCaricamento">
					<div id="logo" ></div>
					<input type="text" name="codiceCaricamento" id="codiceCaricamento" onkeyup="codice(this.value);process(event, this);"  value="" placeholder="Codice" />
					<div id="datiPannelloCaricamento" >
						<div id="codicePannelloCaricamento">Codice pannello:</div>
						<div id="finituraPannelloCaricamento">Finitura:</div>
					</div>
				</div>
				<div id="contenutoCaricamento">
					<div id="pdfCaricamento">
						<!--<object data="/mi_incollaggio/stazioni/images/+K4PN00193.pdf" type="application/pdf" width="565" height="800"></object>-->
					</div>
					<div id="ruotatoCaricamento"></div>
					<div id="colonnaPannelliMancanti">
						<div id="pannelliMancanti">
						</div>
						<input type="button" value="Carica pannello" id="btnCaricaCaricamento" onclick="caricaPannello()">
					</div>
					<div id="colonnaElencoLotti">
						<div id="elencoLotti">
						</div>
						<input type="button" value="Carica di nuovo" id="btnCaricaDiNuovo" onclick="caricaDiNuovo()">
					</div>
				</div>
			</div>
			<div id="footer">
				<hr size='1' style='border-color:#80B3E6;'>
				<b>Marine&nbspInteriors&nbspS.p.A.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspSegaluzza&nbsp33170&nbspPordenone&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp0434612811
			</div>
		</div>
	</body>
</html>


<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
