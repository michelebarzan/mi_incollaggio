<!DOCTYPE HTML>
<?php
	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	if(!$conn)
		echo "connessione fallita";
	
	if(isset($_GET['lotto']))
		$lotto=$_GET['lotto'];
	else
		$lotto='';
?>
<html>
	<head>
		<title>Incollaggio lana</title>
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
				document.getElementById("codiceIncollaggio").focus();
			}
			function codice(codice)
			{
				if(codice=="C" || codice=="c")
				{
					document.getElementById('codiceIncollaggio').value="+K4PN";
				}
			}
			function process(e) 
			{
				var code = (e.keyCode ? e.keyCode : e.which);
				if (code == 13) 
				{
					if(document.getElementById('codiceIncollaggio').value!='')
					{
						//console.log("codpanScaricamentoManuale->"+document.getElementById('codpanScaricamentoManuale').value);
						//console.log("lottoScaricamentoManuale->"+document.getElementById('lottoScaricamentoManuale').value);
						//console.log("qntScaricamentoManuale->"+document.getElementById('qntScaricamentoManuale').value);
						
						
						if(document.getElementById('codpanScaricamentoManuale').value=='')
							checkPannello();
						if(document.getElementById('codpanScaricamentoManuale').value!='' && document.getElementById('lottoScaricamentoManuale').value=='')
							checkLotto();
						if(document.getElementById('codpanScaricamentoManuale').value!='' && document.getElementById('lottoScaricamentoManuale').value!='' && document.getElementById('qntScaricamentoManuale').value=='')
							checkQnt();
					}
					else
					{
						if(document.getElementById('codpanScaricamentoManuale').value!='' && document.getElementById('lottoScaricamentoManuale').value!='' && document.getElementById('qntScaricamentoManuale').value!='')
							scaricaPannello();
					}
					//window.alert("invio");
				}
				if (code == 27) 
				{
					window.location.href='incollaggio.php';
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
			function checkPannello()
			{
				var codpan=document.getElementById('codiceIncollaggio').value;
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText!="ok")
						{
							window.alert(this.responseText);
							document.getElementById('codiceIncollaggio').value="";
						}
						else
						{
							document.getElementById('codpanScaricamentoManuale').value=codpan;
							document.getElementById('comandiDisegno').innerHTML='Pannello:  <b style="color:#3367d6">'+codpan+'</b>';
							disegno(codpan);
							document.getElementById('codiceIncollaggio').value="";
							document.getElementById('codiceIncollaggio').setAttribute("placeholder","Scegli un lotto");
							checkLottoPreselezionato();
							getLotti();
						}
					}
				};
				xmlhttp.open("POST", "checkPannelloScaricamentoManuale.php?codpan="+codpan, true);
				xmlhttp.send();
			}
			function disegno(codPan)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('disegnoIncollaggio').innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "disegnoScaricaManuale.php?codPan="+codPan, true);
				xmlhttp.send();
			}
			function checkLottoPreselezionato()
			{
				var codpan=document.getElementById('codpanScaricamentoManuale').value;
				var lotto=document.getElementById('hiddenLotto').value;
				lotto=lotto.trim();
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText!="ok")
						{
							document.getElementById('bancaleCorrente').innerHTML="";
							document.getElementById('qntPannelliBancale').innerHTML="";
							document.getElementById('datiPannelloIncollaggio').innerHTML="";
							document.getElementById('hiddenLotto').value="";
						}
						else
							document.getElementById('codiceIncollaggio').value=lotto;
					}
				};
				xmlhttp.open("POST", "checkLottoPreselezionato.php?codpan="+codpan+"&lotto="+lotto, true);
				xmlhttp.send();
			}
			function getLotti()
			{
				var codpan=document.getElementById('codpanScaricamentoManuale').value;
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('pdfIncollaggio').innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "getLotti.php?codpan="+codpan, true);
				xmlhttp.send();
			}
			function selezionaLotto(i)
			{
				if(document.getElementById('lottoScaricamentoManuale').value=="")
				{
					var all = document.getElementsByClassName("colonnaLottoScaricamentoManuale");
					for (var j = 0; j < all.length; j++) 
					{
						all[j].style.color='gray';
						all[j].style.fontWeight='normal';
					}
					var lotto=document.getElementById('colonnaLotto'+i).innerHTML;
					lotto=lotto.trim();
					var completato=document.getElementById('colonnaCompletato'+i).innerHTML;
					if(completato=='X')
					{
						document.getElementById('bancaleCorrente').innerHTML="";
						document.getElementById('qntPannelliBancale').innerHTML="";
						document.getElementById('hiddenId_bancale').value="";
						document.getElementById('hiddenNumeroBancale').value="";
						document.getElementById('codiceIncollaggio').value=lotto;
						document.getElementById('colonnaLotto'+i).style.color="#3367d6";
						document.getElementById('colonnaLotto'+i).style.fontWeight="bold";
					}
					else
						window.alert("Il lotto e' stato completato");
				}
			}
			function checkLotto()
			{
				var lotto=document.getElementById('codiceIncollaggio').value;
				lotto=lotto.trim();
				var codpan=document.getElementById('codpanScaricamentoManuale').value;
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=="ok")
						{
							document.getElementById('datiPannelloIncollaggio').innerHTML='Lotto:  <b style="color:#3367d6">'+lotto+'</b>';
							document.getElementById('lottoScaricamentoManuale').value=lotto;
							document.getElementById('codiceIncollaggio').value="";
							document.getElementById('codiceIncollaggio').setAttribute("placeholder","Scegli la quantità");
							document.getElementById('hiddenLotto').value=lotto;
							getBancale();
						}
						else
							window.alert(this.responseText);
					}
				};
				xmlhttp.open("POST", "checkLotto.php?codpan="+codpan+"&lotto="+lotto, true);
				xmlhttp.send();
				
			}
			function checkQnt()
			{
				var lotto=document.getElementById('lottoScaricamentoManuale').value;
				lotto=lotto.trim();
				var codpan=document.getElementById('codpanScaricamentoManuale').value;
				var qnt=document.getElementById('codiceIncollaggio').value;
				if(qnt>0)
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText=="ok")
							{
								document.getElementById('codiceIncollaggio').value="";
								document.getElementById('codiceIncollaggio').setAttribute("placeholder","Scarica pannello");
								document.getElementById('qntScaricamentoManuale').value=qnt;
								document.getElementById('qntPannelliScaricamentoManuale').innerHTML='Qnt:  <b style="color:#3367d6">'+qnt+'</b>';
							}
							else
							{
								var message=this.responseText;
								var qntMancante = message.substring(message.lastIndexOf("#") + 1, message.lastIndexOf("%"));
								message=message.replace("#", "");
								message=message.replace("%", "");
								if (confirm(message)) 
								{
									aggiungiPannelli(qntMancante,qnt,codpan,lotto);
								} 
							}
						}
					};
					xmlhttp.open("POST", "checkQnt.php?codpan="+codpan+"&lotto="+lotto+"&qnt="+qnt, true);
					xmlhttp.send();
				}
				else
					window.alert("Valore non valido");
			}
			function getBancale()
			{
				var lotto=document.getElementById('hiddenLotto').value;
				lotto=lotto.trim();
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
			function aggiungiPannelli(qntMancante,qnt,codpan,lotto)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText!="ok")
							window.alert(this.responseText);
						else
						{
							document.getElementById('codiceIncollaggio').value="";
							document.getElementById('codiceIncollaggio').setAttribute("placeholder","Scarica pannelli");
							document.getElementById('qntScaricamentoManuale').value=qnt;
							document.getElementById('qntPannelliScaricamentoManuale').innerHTML='Qnt:  <b style="color:#3367d6">'+qnt+'</b>';
						}
					}
				};
				xmlhttp.open("POST", "aggiungiPannelli.php?qntMancante="+qntMancante+"&codpan="+codpan+"&lotto="+lotto, true);
				xmlhttp.send();
			}
			function scaricaPannello()
			{
				var lotto=document.getElementById('lottoScaricamentoManuale').value;
				lotto=lotto.trim();
				var codpan=document.getElementById('codpanScaricamentoManuale').value;
				var qnt=document.getElementById('qntScaricamentoManuale').value;
				var id_bancale=document.getElementById('hiddenId_bancale').value;
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText!="ok")
							window.alert(this.responseText);
						else
						{
							window.alert("Pannelli registrati");
							window.location.href = "scaricaManuale.php?lotto="+lotto;
						}
					}
				};
				xmlhttp.open("POST", "scaricaPannello.php?qnt="+qnt+"&codpan="+codpan+"&lotto="+lotto+"&id_bancale="+id_bancale, true);
				xmlhttp.send();
			}
		</script>
	</head>
	<body onload="focusOnInput();getBancale();qntPannelliBancale()" onclick="focusOnInput()" onmouseover="focusOnInput()" >
		<input type="hidden" id="codpanScaricamentoManuale" />
		<input type="hidden" id="lottoScaricamentoManuale" />
		<input type="hidden" id="qntScaricamentoManuale" />
		<input type="hidden" id="hiddenLotto" value="<?php echo $lotto;?>" />
		<input type="hidden" id="hiddenId_bancale" />
		<input type="hidden" id="hiddenNumeroBancale" />
		<input type="hidden" id="hiddenQntPannelliBancale" />
		<div id="container">
			<div id="header" class="header" style="text-align:center">
				<b style='font-size:280%;color:white;font-family:sans-serif'>Scaricamento manuale</b>
			</div>
			<div id="content">
				<div id="intestazioneIncollaggio">
					<div id="logo" ></div>
					<input type="text" name="codiceIncollaggio" id="codiceIncollaggio" onkeyup="codice(this.value);process(event, this);"  value="" placeholder="Codice pannello" />
					<div id="datiPannelloIncollaggio" style="color:gray;font-size:160%;font-family:sans-serif;font-weight:bold;line-height:80px">
						Lotto:  <b style="color:#3367d6"><?php echo $lotto;?></b>
					</div>
				</div>
				<div id="Intestazione2Incollaggio">
					<div id="bancaleEtichette">
						<div id="qntPannelliBancale"></div>
						<div id="bancaleCorrente"></div>
						<input type="button" value="Stampa etichetta" id="btnStampaEtichetta" onclick="stampaEtichetta()" />
					</div>
					<div id="comandiDisegno" style="color:gray;font-size:160%;font-family:sans-serif;font-weight:bold;display:inline-block;float:left;width:50%"></div>
					<div id="qntPannelliScaricamentoManuale" style="color:gray;font-size:160%;font-family:sans-serif;font-weight:bold;display:inline-block;float:right;width:50%"></div>
				</div>
				<div id="contenitorePdfIncollaggio">
					<div id="pdfIncollaggio" style="height:650px;overflow-y:auto">
					</div>
					<input type="button" value="Scarica pannelli" id="btnProssimoIncollaggio" onclick="scaricaPannello()">
					<input type="button" value="Annulla" id="btnEliminaIncollaggio" onclick="window.location.href='incollaggio.php';">
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