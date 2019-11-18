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
		<title>Taglio rinforzi</title>
			<link rel="stylesheet" href="/mi_incollaggio/stazioni/css/styleV3.css" />
			<style>
				/*@import url(http://fonts.googleapis.com/css?family=Exo:100,200,400);
				@import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro:700,400,300);*/
			</style>
			<script>
			function focusOnInput()
			{
				document.getElementById("codiceRinforzi").focus();
			}
			function logout()
			{
				window.location = 'logout.php';
			}
			function ripristinaSfondo()
			{
				document.getElementById('pdfRinforzi').innerHTML='';
				document.body.className="";
				document.getElementById('container').className="";
				document.getElementById('content').className="";
				document.getElementById('contenutoRinforzi1').className="";
				document.getElementById('intestazioneRinforzi').className="";
				document.getElementById('disegnoRinforzi').className="";
				document.getElementById('footer').className="";
			}
			function codice(codice)
			{
				//caso zoom Pdf
				if(codice=="B" || codice=="b")
				{
					zoomPDF();
					document.getElementById('codiceRinforzi').value="";
				}
				if(codice=="D" || codice=="d")
				{
					document.getElementById('flagProssimo').value="s";
					document.getElementById('btnLogout').click();
					document.getElementById('codiceRinforzi').value="";
				}
				//caso pannelloPrecedente
				if(codice=="F" || codice=="f")
				{
					pannelloPrecedente();
					document.getElementById('codiceRinforzi').value="";
				}
				//caso flagMacchina
				if(codice=="E" || codice=="e")
				{
					flagMacchina();
					document.getElementById('codiceRinforzi').value="";
				}
			}
			function zoomPDF()
			{
				//window.alert("pesce");
				var codPan=document.getElementById('codPanRinforzi').innerHTML;
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
					if(document.getElementById('codiceRinforzi').value=="")
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
				//var finitura="Rai 105";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=="nopannelli")
						{
							
						}
						else
						{
							/*document.getElementById('codicePannelloRinforzi').innerHTML="Codice pannello: <b id='codPanRinforzi' style='color:#3367d6' >"+this.responseText.substring(0,10)+"</b>";
							document.getElementById('id_produzione').value=this.responseText.substring(11,15);
							document.getElementById('nLotto').innerHTML="Lotto: "+this.responseText.substring(16,this.responseText.length);
							document.getElementById('finituraPannelloRinforzi').innerHTML="Finitura: <b style='color:#3367d6' >"+finitura+"</b>";*/
							
							var res = this.responseText.split("|");
							var codPan=res[0];
							console.log(codPan);
							document.getElementById('codicePannelloRinforzi').innerHTML="Codice pannello: <b id='codPanRinforzi' style='color:#3367d6' >"+res[0]+"</b>";
							document.getElementById('id_produzione').value=res[1];
							document.getElementById('id_produzionePannelloRinforzi').innerHTML="Id pannello: <b style='color:#3367d6' >"+res[1]+"</b>";
							document.getElementById('posizionePannelloRinforzi').innerHTML="Posizione: <b style='color:#3367d6' >"+res[5]+"</b>";
							//document.getElementById('pageName').innerHTML=res[1];
							document.getElementById('nLotto').innerHTML="Lotto: "+res[2];
							document.getElementById('finituraPannelloRinforzi').innerHTML="Finitura: <b style='color:#3367d6' >"+res[3]+"</b>";
							
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
											document.getElementById('contenutoRinforzi1').className="containerAlertProducibilita";
											document.getElementById('intestazioneRinforzi').className="containerAlertProducibilita";
											document.getElementById('disegnoRinforzi').className="containerAlertProducibilita";
											document.getElementById('footer').className="containerAlertProducibilita";
										}
										pdfRinforzi(alertProducibilita);
									}
								};
								xmlhttp2.open("POST", "controllaProducibilita.php?codPan=" + codPan, true);
								xmlhttp2.send();
								controllaRuotato();
							}, 500)
						}
					}
				};
				xmlhttp.open("POST", "getPannello.php?", true);
				xmlhttp.send();
				disegna();
				tabellaRinforzi();
				nRinforziT();
				nRinforziP();
				
			}
			function disegna()
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('disegnoRinforzi').innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "disegnoRinforzi.php?", true);
				xmlhttp.send();
			}
			function tabellaRinforzi()
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('tabellaRinforzi').innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "tabellaRinforzi.php?", true);
				xmlhttp.send();
			}
			function controllaRuotato()
			{
				//try
				//{
					var codPan=document.getElementById('codPanRinforzi').innerHTML;
				//}
				//catch(err)
				//{
				//	window.alert(err);
				//}
				/*window.alert(codPan);*/
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('ruotatoRinforzi').innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "controllaRuotato.php?codPan="+codPan, true);
				xmlhttp.send();
			}
			function pdfRinforzi(alertProducibilita)
			{
				var codPan=document.getElementById('codPanRinforzi').innerHTML;
				if(alertProducibilita==0)
					document.getElementById('pdfRinforzi').innerHTML='<a href="/mi_incollaggio/stazioni/images/'+codPan+'.pdf" target="_blank" style="height:800px" ><object data="/mi_incollaggio/stazioni/disegni/PdfPan/'+codPan+'.pdf" type="application/pdf" width="435px" height="645"></object></a>';
				else
					document.getElementById('pdfRinforzi').innerHTML='<a href="/mi_incollaggio/stazioni/images/'+codPan+'.pdf" target="_blank" style="height:800px" ><div class="alertProducibilita">Controlla il PDF</div><object data="/mi_incollaggio/stazioni/disegni/PdfPan/'+codPan+'.pdf" type="application/pdf" width="395px" height="595"></object></a>';
				//document.getElementById('pdfRinforzi').innerHTML='<a href="/mi_incollaggio/stazioni/images/'+codPan+'.pdf" target="_blank" style="height:800px" ><object data="/mi_incollaggio/stazioni/disegni/PdfPan/'+codPan+'.pdf" type="application/pdf" width="435px" height="645"></object></a>';
			}
			function nRinforziT()
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('nRinforziT').innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "nRinforziT.php?", true);
				xmlhttp.send();
			}
			function nRinforziP()
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('nRinforziP').innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "nRinforziP.php?", true);
				xmlhttp.send();
			}
			function prossimoPannello()
			{
				try
				{
					var codPan=document.getElementById('codPanRinforzi').innerHTML;
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
					var id_produzione=document.getElementById('id_produzione').value;
					//contolla mRinforzi1 e setta vRinforzi false
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
								window.alert("Errore");
						}
					};
					xmlhttp.open("POST", "vRinforzi.php?codPan="+codPan+"&id_produzione="+id_produzione, true);
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
			function flagMacchina()
			{
				//try
				//{
					var codPan=document.getElementById('codPanRinforzi').innerHTML;
				//}
				//catch(err)
				//{
				//	window.alert(err);
				//}
				//contolla mRinforzi1 e setta vRinforzi false
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						//document.getElementById('nRinforziP').innerHTML=this.responseText;
						window.alert(this.responseText);
					}
				};
				xmlhttp.open("POST", "flagMacchina.php?codPan="+codPan, true);
				xmlhttp.send();
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
							//altrimenti controlla se il pannello con questo id ha passato l incollaggio rinforzi, se si aggiorna, altrimenti no action
							var xmlhttp = new XMLHttpRequest();
							xmlhttp.onreadystatechange = function() 
							{
								if (this.readyState == 4 && this.status == 200) 
								{
									if(this.responseText=="true")
									{
										prossimoPannello();
										/*document.getElementById('flagProssimo').value="s";
										location.reload();*/
									}
								}
							};
							xmlhttp.open("POST", "controllaMRinforzi.php?id_produzione="+id_produzione, true);
							xmlhttp.send();
						}
					},500);
				}, 2000)
			}
			function controllaStazionePannello()
			{
				setTimeout(function()
				{
					var id_produzione=document.getElementById('id_produzione').value;
					if(id_produzione!=0)
					{
						setInterval(function()
						{
							var xmlhttp = new XMLHttpRequest();
							xmlhttp.onreadystatechange = function() 
							{
								if (this.readyState == 4 && this.status == 200) 
								{
									document.getElementById('posizionePannelloRinforzi1').innerHTML=this.responseText;
								}
							};
							xmlhttp.open("POST", "controllaStazionePannello.php?id_produzione="+id_produzione, true);
							xmlhttp.send();
						},500);
					}
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
			function pannelloPrecedente()
			{
				var id_produzione=document.getElementById('id_produzione').value;
				if(id_produzione!=0)
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							//window.alert(this.responseText);
							if(this.responseText=='')
								window.alert("Il pannello precedente e gia uscito dalla linea");
							else
							{
								var codPan=this.responseText;
								try
								{
									document.getElementById('pannelloPrecedenteLink').setAttribute('href', 'pannelloPrecedente.php?codPan=' + codPan);
									document.getElementById('pannelloPrecedenteLink').click();
								}
								catch(err) 
								{
									window.alert( err.message);
								}
							}
						}
					};
					xmlhttp.open("POST", "getPannelloPrecedente.php?id_produzione="+id_produzione, true);
					xmlhttp.send();
				}
				else
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							//window.alert(this.responseText);
							if(this.responseText=='')
								window.alert("Il pannello precedente e gia uscito dalla linea");
							else
							{
								var codPan=this.responseText;
								document.getElementById('pannelloPrecedenteLink').setAttribute('href', 'pannelloPrecedente.php?codPan=' + codPan);
								document.getElementById('pannelloPrecedenteLink').click();
							}
						}
					};
					xmlhttp.open("POST", "getPannelloPrecedenteMax.php?", true);
					xmlhttp.send();
				}
			}
		</script>
	</head>
	<body onload="focusOnInput();getPannello();autoGetPannello();controllaStazionePannello();controllaFlagSvuotaLinea()" onclick="focusOnInput()" onmouseover="focusOnInput()" onbeforeunload="return ricarica()" >
		<input type="hidden" id="flagProssimo" value="n" />
		<input type="hidden" id="id_produzione" value="0" />
		<div id="eliminato">PANNELLO ELIMINATO</div>
		<div id="container">
			<div id="header" class="header" >
				<a href="zoomPDF.php" id="zoomPDFLink" target="_blank" style="display:none" ></a>
				<a href="pannelloPrecedente.php" id="pannelloPrecedenteLink" target="_blank" style="display:none" ></a>
				<div id="pageName" class="pageName">
					Stazione taglio rinforzi
				</div>
				<div id="lottoSelezionato">
					<!--<input type="button" value="FLAG MACCHINA" onclick="flagMacchina()">-->
					<div id="nLotto" style="display:inline-block">Lotto:</div>
				</div>
				<div id="user" class="user">
					<div id="username"><?php echo $_SESSION['Username']; ?></div>
					<input type="button" value="Logout" id="btnLogout" onclick="logout()">
				</div>
			</div>
			<div id="content">
				<div id="intestazioneRinforzi">
					<div id="logo" ></div>
					<input type="text" name="codiceRinforzi" id="codiceRinforzi" onkeyup="codice(this.value);process(event, this);"  value="" placeholder="Codice" />
					<div id="datiPannelloRinforzi" >
						<div id="codicePannelloRinforzi">Codice pannello:</div>
						<div id="finituraPannelloRinforzi">Finitura:</div>
						<div id="id_produzionePannelloRinforzi">Id pannello:</div>
						<div id="posizionePannelloRinforzi">Posizione:</div>
					</div>
				</div>
				<div id="posizionePannelloRinforzi1"></div>
				<div id="contenutoRinforzi1">
					<div id="nRinforzi">
						<div id="nRinforziT">
						</div>
						<div id="nRinforziP">
						</div>
					</div>
					<div id="tabellaRinforzi">
					</div>
					<div id="ruotatoRinforzi">
					</div>
				</div>
				<div id="contenitorePdfRinforzi1">
					<div id="pdfRinforzi">
					</div>
					<input type="button" value="Prossimo pannello" id="btnProssimoRinforzi" onclick="prossimoPannello()">
					<input type="button" value="Elimina pannello" id="btnEliminaRinforzi" onclick="eliminaPannello()">
				</div>
				<div id="disegnoRinforzi">
				</div>
			</div>
			<div id="footer">
				<hr size='1' style='border-color:#80B3E6;'><!--<input type="button" onclick="disegna()" value="disegna" />-->
				<b>Marine&nbspInteriors&nbspS.p.A.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspSegaluzza&nbsp33170&nbspPordenone&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp0434612811
			</div>
		</div>
	</body>
</html>


<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php
?>