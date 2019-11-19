<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
	
	$pageName="Gestione lane";
?>
<html>
	<head>
		<title><?php echo $pageName; ?></title>
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link rel="stylesheet" href="/mi_incollaggio/ufficio/css/styleV4.css" />
			<script src="struttura.js"></script>
			<script>
			function selezionaLana(id_lana,Larg,halt,pos1,pos2,pos3,pos4,pos5,Nome,Codmat)
				{
					document.getElementById('id_lana').value=id_lana;
					document.getElementById('Larg').value=Larg;
					document.getElementById('halt').value=halt;
					document.getElementById('pos1').value=pos1;
					document.getElementById('pos2').value=pos2;
					document.getElementById('pos3').value=pos3;
					document.getElementById('pos4').value=pos4;
					document.getElementById('pos5').value=pos5;
					document.getElementById('Nome').value=Nome;
					document.getElementById('Codmat').value=Codmat;
				}
				function elimina()
				{
					try
					{
						var Nome=document.getElementById('Nome').value;
					}
					catch(err)
					{
						window.alert(err.message);
					}
					if(Nome=='')
						window.alert("Lana non selezionata");
					else
					{
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								if(this.responseText!="ok")
									window.alert("Errore: impossibile eliminare");
								else
									location.reload();
							}
						};
						xmlhttp.open("POST", "eliminaLana.php?Nome="+Nome, true);
						xmlhttp.send();
					}
				}
				function conferma()
				{
					try
					{
						var Nome=document.getElementById('Nome').value;
						var Larg=document.getElementById('Larg').value;
						var halt=document.getElementById('halt').value;
						var Codmat=document.getElementById('Codmat').value;
					}
					catch(err)
					{
						window.alert(err.message);
					}
					if(Nome=='' || Larg=='' || halt=='' || Codmat=='')
					{
						document.getElementById('Nome').style.border="2px solid red";
						document.getElementById('Larg').style.border="2px solid red";
						document.getElementById('halt').style.border="2px solid red";
						document.getElementById('Codmat').style.border="2px solid red";
						window.alert("Complila i campi obbligatori");
					}
					else
					{
						var id_lana=document.getElementById('id_lana').value;
						var pos1=document.getElementById('pos1').value;
						var pos2=document.getElementById('pos2').value;
						var pos3=document.getElementById('pos3').value;
						var pos4=document.getElementById('pos4').value;
						var pos5=document.getElementById('pos5').value;
						if(pos1=='')
							pos1=0;
						if(pos2=='')
							pos2=0;
						if(pos3=='')
							pos3=0;
						if(pos4=='')
							pos4=0;
						if(pos5=='')
							pos5=0;
						
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								if(this.responseText=="Errore")
									window.alert("Errore: il nome '"+Nome+"' e' gia' in uso. Scegli un altro nome");
								else
									if(this.responseText!="ok")
										window.alert("Errore: impossibile modificare");
									else
										location.reload();
							}
						};
						xmlhttp.open("POST", "modificaLana.php?id_lana="+id_lana+"&Nome="+Nome+"&Larg="+Larg+"&halt="+halt+"&Codmat="+Codmat+"&pos1="+pos1+"&pos2="+pos2+"&pos3="+pos3+"&pos4="+pos4+"&pos5="+pos5, true);
						xmlhttp.send();
					}
				}
				function nuovo()
				{
					try
					{
						var Nome=document.getElementById('Nome').value;
						var Larg=document.getElementById('Larg').value;
						var halt=document.getElementById('halt').value;
						var Codmat=document.getElementById('Codmat').value;
					}
					catch(err)
					{
						window.alert(err.message);
					}
					if(Nome=='' || Larg=='' || halt=='' || Codmat=='')
					{
						document.getElementById('Nome').style.border="2px solid red";
						document.getElementById('Larg').style.border="2px solid red";
						document.getElementById('halt').style.border="2px solid red";
						document.getElementById('Codmat').style.border="2px solid red";
						window.alert("Complila i campi obbligatori");
					}
					else
					{
						var id_lana=document.getElementById('id_lana').value;
						var pos1=document.getElementById('pos1').value;
						var pos2=document.getElementById('pos2').value;
						var pos3=document.getElementById('pos3').value;
						var pos4=document.getElementById('pos4').value;
						var pos5=document.getElementById('pos5').value;
						if(pos1=='')
							pos1=0;
						if(pos2=='')
							pos2=0;
						if(pos3=='')
							pos3=0;
						if(pos4=='')
							pos4=0;
						if(pos5=='')
							pos5=0;
						
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								if(this.responseText=="Errore")
									window.alert("Errore: il nome '"+Nome+"' e' gia' in uso. Scegli un altro nome");
								else
									if(this.responseText!="ok")
										window.alert("Errore: impossibile modificare");
									else
										location.reload();
							}
						};
						xmlhttp.open("POST", "nuovaLana.php?Nome="+Nome+"&Larg="+Larg+"&halt="+halt+"&Codmat="+Codmat+"&pos1="+pos1+"&pos2="+pos2+"&pos3="+pos3+"&pos4="+pos4+"&pos5="+pos5, true);
						xmlhttp.send();
					}
				}
				function resetStyle()
				{
					var all = document.getElementsByClassName("btnIntestazioneGestisciLinea");
					for (var i = 0; i < all.length; i++) 
					{
						all[i].style.color = 'black';
						all[i].style.boxShadow="";
					}
					document.getElementById('containerMisureLaneAngoli').style.display="none";
					document.getElementById('containerLanePrefresate').style.display="none";
					//document.getElementById('comandiTabelle').style.height="0px";
				}
				function lanaPrefresata()
				{
					document.getElementById('btnLanaPrefresata').style.color="#3367d6";
					document.getElementById('btnLanaPrefresata').style.boxShadow=" 5px 5px 10px #9c9e9f";
					
					document.getElementById('containerLanePrefresate').style.display="inline-block";
				}
				function misureLanaAngoli(lotto)
				{
					document.getElementById('btnMisureLanaAngoli').style.color="#3367d6";
					document.getElementById('btnMisureLanaAngoli').style.boxShadow=" 5px 5px 10px #9c9e9f";
					document.getElementById('containerMisureLaneAngoli').style.display="inline-block";
					//document.getElementById('comandiTabelle').style.height="50px";
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById('containerMisureLaneAngoli').innerHTML= this.responseText;
						}
					};
					xmlhttp.open("POST", "misureLanaAngoli.php?lotto="+lotto, true);
					xmlhttp.send();
				}
				function filtroLotto()
				{
					var lotto=document.getElementById("filtroLotto").value;
					console.log(lotto);
					misureLanaAngoli(lotto);
				}
				function eliminaRigaLana(n)
				{
					var row = document.getElementById("rowLanaAngoli"+n);
					row.parentNode.removeChild(row);
				}
				function stampaTutti()
				{
					var oldTable=document.getElementById("myTableTabelleGestisciLinea").outerHTML;
					for (var i = 0, row; row = document.getElementById("myTableTabelleGestisciLinea").rows[i]; i++) 
					{
						row.deleteCell(5);
					}
					document.getElementById("filtroLotto").style.display="none";
					var mywindow = window.open('', 'new div', 'height=400,width=600');
					mywindow.document.write('<html><head><title></title>');
					mywindow.document.write('<link rel="stylesheet" href="css/printMisureLane.css" type="text/css" />');
					mywindow.document.write('</head><body >');
					mywindow.document.write(document.getElementById("myTableTabelleGestisciLinea").outerHTML);
					mywindow.document.write('</body></html>');
					mywindow.document.close();
					mywindow.focus();
					setTimeout(function(){mywindow.print();},100);
					setTimeout(function(){mywindow.close();},200);
					document.getElementById('containerMisureLaneAngoli').innerHTML=oldTable;
				}
			</script>
	</head>
	<body>
		<?php include('struttura.php'); ?>
		<div id="container">
			<div id="content">
				<div id="immagineLogo" class="immagineLogo" ></div>
				<div id="intestazioneGestisciLinea">
					<input type="button" id="btnLanaPrefresata" class="btnIntestazioneGestisciLinea" onclick="resetStyle();lanaPrefresata()" value="Lane prefresate" />
					<input type="button" id="btnMisureLanaAngoli" class="btnIntestazioneGestisciLinea" onclick="resetStyle();misureLanaAngoli('%')" value="Misure lana angoli" />
				</div>
				<!--<div id="comandiTabelle">
					<input type="button" id="btnStampaMisureLanaAngoli" class="btnGray" value="Stampa" onclick="" />
					<input type="button" id="btnLottoMisureLanaAngol" class="btnGray" value="Lotto" onclick="" />
				</div>-->
				<div id="containerMisureLaneAngoli"></div>
				<div id="containerLanePrefresate">
					<div id="tabellaLanePrefresate">
					<?php
						
						$query="SELECT * FROM Lana_Prefresata";
						$result=sqlsrv_query($conn,$query);
						if($result==FALSE)
						{
							$query=str_replace("'","*APICE*",$query);
							$testoErrore=print_r(sqlsrv_errors(),TRUE);
							$testoErrore=str_replace("'","*APICE*",$testoErrore);
							$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
							$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$query','".$testoErrore."','".$_SESSION['Username']."')";
							$resultErrori=sqlsrv_query($conn,$queryErrori);
							$query=str_replace("*APICE*","'",$query);
							echo "<br><br>Errore esecuzione query<br>Query: ".$query."<br>Errore: ";
							die(print_r(sqlsrv_errors(),TRUE));
						}
						else
						{
							echo '<table id="myTableLanePrefresate">';
							echo '<tr class="Theader">';
								echo '<th>Larghezza</th>';
								echo '<th>Altezza</th>';
								echo '<th>Pos1</th>';
								echo '<th>Pos2</th>';
								echo '<th>Pos3</th>';
								echo '<th>Pos4</th>';
								echo '<th>Pos5</th>';
								echo '<th>Nome</th>';
								echo '<th>Codmat</th>';
							echo '</tr>';
							$i=0;
							while($row=sqlsrv_fetch_array($result))
							{
								if ($i % 2 == 0)
								{
									?><tr style="background:white" onclick="selezionaLana(<?php echo $row['id_lana'].",".$row['Larg'].",".$row['halt'].",".$row['pos1'].",".$row['pos2'].",".$row['pos3'].",".$row['pos4'].",".$row['pos5'].",'".$row['Nome']."','".$row['Codmat']; ?>')"><?php
								}
								else
								{
									?><tr style="background:#D1D1D1" onclick="selezionaLana(<?php echo $row['id_lana'].",".$row['Larg'].",".$row['halt'].",".$row['pos1'].",".$row['pos2'].",".$row['pos3'].",".$row['pos4'].",".$row['pos5'].",'".$row['Nome']."','".$row['Codmat']; ?>')"><?php
								}
									echo '<td>';
										echo $row['Larg'];
									echo '</td>';
									echo '<td>';
										echo $row['halt'];
									echo '</td>';
									echo '<td>';
										echo $row['pos1'];
									echo '</td>';
									echo '<td>';
										echo $row['pos2'];
									echo '</td>';
									echo '<td>';
										echo $row['pos3'];
									echo '</td>';
									echo '<td>';
										echo $row['pos4'];
									echo '</td>';
									echo '<td>';
										echo $row['pos5'];
									echo '</td>';
									echo '<td>';
										echo $row['Nome'];
									echo '</td>';
									echo '<td>';
										echo $row['Codmat'];
									echo '</td>';
								echo '</tr>';
								$i++;
							}
							echo '</table>';
						}
					?>
					</div>
					
					<div id="disegnoLanePrefesate">
						<input type="hidden" id="id_lana" />
						<div style="width:100%;height:25px;text-align:left;padding-left:230px;" ><input type="number" value="" id="Larg" style="height:100%;width:62px;border:1px solid #ddd;font-weight:bold;color:gray;font-size:110%;" /></div>
						<div style="width:62px;height:650px;text-align:center;display:inline-block;float:left;" ><input type="number" value="" id="halt" style="margin-top:325px;height:25px;width:100%;border:1px solid #ddd;font-weight:bold;color:gray;font-size:110%" /></div>
						<svg height="651px" width="340px" style="display:inline-block;float:left;" >
							<!--Quota orrizzontale-->
							<line x1="65" y1="10" x2="335" y2="10" style="stroke:black;stroke-width:2" />
							<line x1="335" y1="0" x2="335" y2="20" style="stroke:black;stroke-width:2" />
							<line x1="65" y1="0" x2="65" y2="20" style="stroke:black;stroke-width:2" />
							<!--Quota verticale-->
							<line x1="0" y1="50" x2="20" y2="50" style="stroke:black;stroke-width:2" />
							<line x1="0" y1="650" x2="20" y2="650" style="stroke:black;stroke-width:2" />
							<line x1="10" y1="50" x2="10" y2="650" style="stroke:black;stroke-width:2" />
							<!--Rettangolo-->
							<rect x="65" y="50" width="270" height="600" style="fill:white;stroke:black;stroke-width:2" />
							<!--Pos5-->
							<line x1="65" y1="110" x2="335" y2="110" style="stroke:red;stroke-width:2" stroke-dasharray="10,10" />
							<!--Pos4-->
							<line x1="65" y1="170" x2="335" y2="170" style="stroke:red;stroke-width:2" stroke-dasharray="10,10" />
							<!--Pos3-->
							<line x1="65" y1="290" x2="335" y2="290" style="stroke:red;stroke-width:2" />
							<!--Pos2-->
							<line x1="65" y1="410" x2="335" y2="410" style="stroke:red;stroke-width:2" />
							<!--Pos1-->
							<line x1="65" y1="530" x2="335" y2="530" style="stroke:red;stroke-width:2" />
							Sorry, your browser does not support inline SVG.
						</svg> 
						<div style="line-height:0px;width:62px;height:600px;text-align:center;display:inline-block;float:left" >
							<input type="number" value="" placeholder="Pos5" id="pos5" style="display:block;margin-top:97.5px;height:25px;width:100%;border:1px solid #ddd;font-weight:bold;color:gray;font-size:110%" />
							<input type="number" value="" placeholder="Pos4" id="pos4" style="display:block;margin-top:35px;height:25px;width:100%;border:1px solid #ddd;font-weight:bold;color:gray;font-size:110%" />
							<input type="number" value="" placeholder="Pos3" id="pos3" style="display:block;margin-top:95px;height:25px;width:100%;border:1px solid #ddd;font-weight:bold;color:gray;font-size:110%" />
							<input type="number" value="" placeholder="Pos2" id="pos2" style="display:block;margin-top:95px;height:25px;width:100%;border:1px solid #ddd;font-weight:bold;color:gray;font-size:110%" />
							<input type="number" value="" placeholder="Pos1" id="pos1" style="display:block;margin-top:95px;height:25px;width:100%;border:1px solid #ddd;font-weight:bold;color:gray;font-size:110%" />
						</div>
						<div style="margin-top:20px;width:100%;height:25px;display:inline-block;text-align:left">
							<input type="text" value="" placeholder="Nome" id="Nome" style="margin-left:127px;display:inline-block;height:25px;width:120px;border:1px solid #ddd;font-weight:bold;color:gray;font-size:100%" />
							<input type="text" value="" placeholder="Codmat" id="Codmat" style="margin-left:27px;display:inline-block;height:25px;width:120px;border:1px solid #ddd;font-weight:bold;color:gray;font-size:100%" />
						</div>
						<div style="margin-top:20px;width:460px;height:50px;display:inline-block;text-align:center;float:left">
							<input type="button" id="btnEliminaLana" value="Elimina" onclick="elimina()" />
							<input type="button" id="btnConfermaLana" value="Modifica"  onclick="conferma()" />
							<input type="button" id="btnNuovoLana" value="Inserisci" onclick="nuovo()" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="push"></div>
		<div id="footer">
			<b>Marine&nbspInteriors&nbspS.p.A.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspSegaluzza&nbsp33170&nbspPordenone&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp0434612811
		</div>
	</body>
</html>