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
		<title>Taglio lana</title>
			<link rel="stylesheet" href="/mi_incollaggio/stazioni/css/styleV3.css" />
			<style>
				/*@import url(http://fonts.googleapis.com/css?family=Exo:100,200,400);
				@import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro:700,400,300);*/
			</style>
			<script>
			function focusOnInput()
			{
				document.getElementById("codiceLana").focus();
			}
			function logout()
			{
				window.location = 'logout.php';
			}
			function codice(codice)
			{
				//caso zoom Pdf
				if(codice=="B" || codice=="b")
				{
					zoomPDF();
					document.getElementById('codiceLana').value="";
				}
				//caso logout
				if(codice=="D" || codice=="d")
				{
					document.getElementById('flagProssimo').value="s";
					document.getElementById('btnLogout').click();
					document.getElementById('codiceLana').value="";
				}
				//caso pannelloPrecedente
				if(codice=="F" || codice=="f")
				{
					pannelloPrecedente();
					document.getElementById('codiceLana').value="";
				}
				//caso flagMacchina
				if(codice=="E" || codice=="e")
				{
					flagMacchina();
					document.getElementById('codiceLana').value="";
				}
			}
			function zoomPDF()
			{
				//window.alert("pesce");
				var codPan=document.getElementById('codPanLana').innerHTML;
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
					if(document.getElementById('codiceLana').value=="")
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
				var finitura="Rai 105";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=="nopannelli")
						{
							document.getElementById('datiLana2').innerHTML="<b style='font-size:300%;color:red;font-family:Monospace'>NESSUN PANNELLO IN CODA</b>";
						}
						else
						{
							var res = this.responseText.split("|");
							
							//document.getElementById('pageName').innerHTML=res[1];
							var codPan=res[0];
							document.getElementById('codicePannelloLana').innerHTML="Codice pannello: <b id='codPanLana' style='color:#3367d6' >"+res[0]+"</b>";
							document.getElementById('id_produzione').value=res[1];
							document.getElementById('id_produzionePannelloLana').innerHTML="Id pannello: <b style='color:#3367d6' >"+res[1]+"</b>";
							document.getElementById('posizionePannelloLana').innerHTML="Posizione: <b style='color:#3367d6' >"+res[5]+"</b>";
							document.getElementById('nLotto').innerHTML="Lotto: "+res[2];
							document.getElementById('finituraPannelloLana').innerHTML="Finitura: <b style='color:#3367d6' >"+res[3]+"</b>";
							
							if(res[4]=="true")
							{
								document.getElementById('eliminato').style.display="inline-block";
							}
							disegna2();
							datiDisegno2();
							disegna1();
							datiDisegno1();
							setTimeout(function()
							{ 
								controllaRuotato();
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
											document.getElementById('intestazioneLana').className="containerAlertProducibilita";
											document.getElementById('contenitorePdfLana').className="containerAlertProducibilita";
											document.getElementById('footer').className="containerAlertProducibilita";
										}
										pdfLana(alertProducibilita);
									}
								};
								xmlhttp2.open("POST", "controllaProducibilita.php?codPan=" + codPan, true);
								xmlhttp2.send();
							}, 500)
						}
					}
				};
				xmlhttp.open("POST", "getPannello.php?", true);
				xmlhttp.send();
			}
			function disegna1()
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('disegnoLana1').innerHTML+=this.responseText;
					}
				};
				xmlhttp.open("POST", "disegnoLana1.php?", true);
				xmlhttp.send();
			}
			function datiDisegno1()
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('datiLana1').innerHTML+=this.responseText;
					}
				};
				xmlhttp.open("POST", "datiDisegno1.php?", true);
				xmlhttp.send();
			}
			function disegna2()
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('disegnoLana2').innerHTML+=this.responseText;
					}
				};
				xmlhttp.open("POST", "disegnoLana2.php?", true);
				xmlhttp.send();
			}
			function datiDisegno2()
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						var res=this.responseText.split("|");
						//window.alert(res[1]);
						if(res[1]=="B15")
						{
							document.getElementById('disegnoLana1').style.background="#FBFF5C";
							document.getElementById('disegnoLana2').style.background="#FBFF5C";
							document.getElementById('contenitorePdfLana').style.background="#FBFF5C";
						}
						document.getElementById('datiLana2').innerHTML+=res[0];
					}
				};
				xmlhttp.open("POST", "datiDisegno2.php?", true);
				xmlhttp.send();
			}
			function controllaRuotato()
			{
				var codPan=document.getElementById('codPanLana').innerHTML;
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('ruotatoLana').innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "controllaRuotato.php?codPan="+codPan, true);
				xmlhttp.send();
			}
			function pdfLana(alertProducibilita)
			{
				try
				{
					var codPan=document.getElementById('codPanLana').innerHTML;
				}
				catch(err)
				{
					window.alert(err);
				}
				if(alertProducibilita==0)
					document.getElementById('pdfLana').innerHTML='<a href="/mi_incollaggio/stazioni/images/'+codPan+'.pdf" target="_blank" style="height:800px" ><object data="/mi_incollaggio/stazioni/disegni/PdfPan/'+codPan+'.pdf" type="application/pdf" width="380px" height="580"></object></a>';
				else
					document.getElementById('pdfLana').innerHTML='<a href="/mi_incollaggio/stazioni/images/'+codPan+'.pdf" target="_blank" style="height:800px" ><div class="alertProducibilita">Controlla il PDF</div><object data="/mi_incollaggio/stazioni/disegni/PdfPan/'+codPan+'.pdf" type="application/pdf" width="345px" height="530"></object></a>';
				//document.getElementById('pdfLana').innerHTML='<a href="/mi_incollaggio/stazioni/images/'+codPan+'.pdf" target="_blank" style="height:800px" ><div class="alertProducibilita">Controlla il PDF</div><object data="/mi_incollaggio/stazioni/disegni/PdfPan/'+codPan+'.pdf" type="application/pdf" width="380px" height="580"></object></a>';
			}
			function prossimoPannello()
			{
				try
				{
					var codPan=document.getElementById('codPanLana').innerHTML;
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
					//window.alert(codPan);
					var id_produzione=document.getElementById('id_produzione').value;
					//contolla mLana e setta vLana false
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText=="err")
							{
								window.alert("ERRORE: la macchina non ha ancora terminato le lavorazioni");
							}
							else
							{
								document.getElementById('flagProssimo').value="s";
								location.reload();
							}
						}
					};
					xmlhttp.open("POST", "vLana.php?codPan="+codPan+"&id_produzione="+id_produzione, true);
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
				var id_produzione=document.getElementById('id_produzione').value;
				if(id_produzione!=0)
				{
					//window.alert("Attendere...");
					document.getElementById('popupStazioni').style.display="inline-block";
					document.getElementById('popupStazioni').innerHTML="Attendere...";
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById('popupStazioni').innerHTML=this.responseText;
							setTimeout(function()
							{
								document.getElementById('popupStazioni').style.display="none";
							}, 2000)
						}
					};
					xmlhttp.open("POST", "flagMacchina.php?", true);
					xmlhttp.send();
				}
			}
			function ricarica() 
			{
				var flagProssimo=document.getElementById('flagProssimo').value;
				if(flagProssimo=="n")
				{
					var id_produzione=document.getElementById('id_produzione').value;
					var codpan="";
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							console.log(this.responseText);
						}
					};
					xmlhttp.open("POST", "f5_log_tagli.php?id_produzione="+id_produzione+"&codpan="+codpan, true);
					xmlhttp.send();
					
					return "Stai uscendo dal programma";
				}
			}
			function autoGetPannello()
			{
				setInterval(function()
				{
					var id_produzione=document.getElementById('id_produzione').value;
					if(id_produzione==0)
					{
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
									prossimoPannello();
								}
							}
						};
						xmlhttp.open("POST", "getPannello.php?", true);
						xmlhttp.send();
					}
					else
					{
						var codPan=document.getElementById('codPanLana').innerHTML;
						//contolla mLana e setta vLana false
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								//window.alert(this.responseText);
								if(this.responseText=="err")
								{
									//window.alert("ERRORE: la macchina non ha ancora terminato le lavorazioni");
								}
								else
								{
									document.getElementById('flagProssimo').value="s";
									location.reload();
								}
							}
						};
						xmlhttp.open("POST", "vLana.php?codPan="+codPan+"&id_produzione="+id_produzione, true);
						xmlhttp.send();
					}
				},500);
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
									document.getElementById('posizionePannelloLana1').innerHTML=this.responseText;
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
			function fullScreen()
			{
				var el = document.documentElement,
				rfs = el.requestFullscreen|| el.webkitRequestFullScreen|| el.mozRequestFullScreen|| el.msRequestFullscreen ;
				rfs.call(el);
			}
		</script>
	</head>
	<body onload="focusOnInput();getPannello();autoGetPannello();controllaStazionePannello();controllaFlagSvuotaLinea()" onclick="focusOnInput()" onmouseover="focusOnInput()" onbeforeunload="return ricarica()" >
		<input type="hidden" id="flagProssimo" value="n" />
		<input type="hidden" id="id_produzione" value="0" />
		<div id="eliminato">PANNELLO ELIMINATO</div>
		<div id='popupStazioni'></div>
		<div id="container">
			<div id="header" class="header" >
				<a href="zoomPDF.php" id="zoomPDFLink" target="_blank" style="display:none" ></a>
				<a href="pannelloPrecedente.php" id="pannelloPrecedenteLink" target="_blank" style="display:none" ></a>
				<div id="pageName" class="pageName">
					Stazione taglio lana
				</div>
				<div id="lottoSelezionato">
					<div id="nLotto" style="display:inline-block">Lotto:</div>
					<input type="button" value="Forza lavorazioni macchina" class="flagMacchina" onclick="flagMacchina()">
				</div>
				<div id="user" class="user">
					<div id="username"><?php echo $_SESSION['Username']; ?></div>
					<input type="button" value="Logout" id="btnLogout" onclick="logout()">
				</div>
			</div>
			<div id="content">
				<div id="intestazioneLana">
					<div id="logo" ></div>
					<input type="text" name="codiceLana" id="codiceLana" onkeyup="codice(this.value);process(event, this);"  value="" placeholder="Codice" />
					<div id="datiPannelloLana" >
						<div id="codicePannelloLana">Codice pannello:</div>
						<div id="finituraPannelloLana">Finitura:</div>
						<div id="id_produzionePannelloLana">Id pannello:</div>
						<div id="posizionePannelloLana">Posizione:</div>
					</div>
				</div>
				<div id="posizionePannelloLana1"></div>
				<div id="disegnoLana1">
					<div id="datiLana1">
					</div>
				</div>
				<div id="contenitorePdfLana">
					<div id="ruotatoLana">
					</div>
					<div id="pdfLana">
					</div>
					<input type="button" value="Prossimo pannello" id="btnProssimoLana" onclick="prossimoPannello()">
					<input type="button" value="Elimina pannello" id="btnEliminaLana" onclick="eliminaPannello()">
				</div>
				<div id="disegnoLana2">
					<div id="datiLana2">
					</div>
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