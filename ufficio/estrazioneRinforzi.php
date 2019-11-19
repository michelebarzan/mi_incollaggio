<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
	
	$pageName="Estrazione rinforzi";
?>
<html>
	<head>
		<title><?php echo $pageName; ?></title>
		<link rel="stylesheet" href="/mi_incollaggio/ufficio/css/styleV4.css" />
		<script src="struttura.js"></script>
		<script src="tableToExcel.js"></script>
		<script>
			function importaTabelleNewPan()
			{
				newGridSpinner("Importazione dati in corso...","topRightCornerToast","","","font-size:100%;color:white");
				document.getElementsByClassName("topRightCornerToast")[0].style.width="250px";	
				document.getElementsByClassName("topRightCornerToast")[0].style.borderLeft="5px solid #4C91CB";	
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Sono stati importati")!=-1)
						{
							document.getElementById("topRightCornerToast").innerHTML ="<b style='color:#00cc66'>Dati importati</b>";
							setTimeout(function()
							{
								document.getElementsByClassName("topRightCornerToast")[0].style.width="0px";
								document.getElementsByClassName("topRightCornerToast")[0].style.borderLeft="none";										
								document.getElementById("topRightCornerToast").innerHTML ="";
							}, 5000);
						}
						else
						{
							document.getElementById("topRightCornerToast").innerHTML ="<b style='color:#F76E6E'>Errore: dati non importati</b>";
						}
					}
				};
				xmlhttp.open("POST", "importaTabelleNewPan.php?", true);
				xmlhttp.send();
			}
			function newGridSpinner(message,container,spinnerContainerStyle,spinnerStyle,messageStyle)
			{
				document.getElementById(container).innerHTML='<div id="gridSpinnerContainer"  style="'+spinnerContainerStyle+'"><div  style="'+spinnerStyle+'" class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div> <div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div><div id="messaggiSpinner" style="'+messageStyle+'">'+message+'</div></div>';
			}
			function confermaCodiciExcel()
			{
				newGridSpinner("Calcolo quantita'...","containerEstrazioneRinforzi","","","font-size:100%;color:gray");
				var elencoCodici=[];
				var excelData=document.getElementById('containerCodiciExcel').value;
				var lotto=document.getElementById('selectLottoEstrazioneRinforzi').value;
				
				if(lotto=='' && excelData=='')
					window.alert("Incolla dei codici o seleziona un lotto");
				else
				{
					if(lotto=='' && excelData!='')
					{
						// split into rows
						var excelRow = excelData.split(String.fromCharCode(10));
						for (i=0; i<excelRow.length; i++) 
						{
							var val=excelRow[i];
							val.trim();
							val=val.replace("+","|");
							elencoCodici.push("'"+val+"'");
						}
						//elencoCodici.pop();
						//console.log(elencoCodici.toString());
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								document.getElementById("containerEstrazioneRinforzi").innerHTML=this.responseText;
							}
						};
						xmlhttp.open("POST", "cercaRinforzi.php?elencoCodici="+elencoCodici, true);
						xmlhttp.send();
					}
					if(lotto!='' && excelData=='')
					{
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								document.getElementById("containerEstrazioneRinforzi").innerHTML=this.responseText;
							}
						};
						xmlhttp.open("POST", "cercaRinforziLotto.php?lotto="+lotto, true);
						xmlhttp.send();
					}
					if(lotto!='' && excelData!='')
					{
						window.alert("Compila un campo alla volta");
					}
				}
			}
		</script>
	</head>
	<body onload="importaTabelleNewPan()">
		<?php include('struttura.php'); ?>
		<div class="topRightCornerToast" id="topRightCornerToast"></div>
		<div id="container">
			<div id="content">
				<div id="immagineLogo" class="immagineLogo" ></div>
				<textarea id="containerCodiciExcel" placeholder="Incolla codici da Excel"></textarea>
				<br><div id="labelEstrazioneRinforzi">Oppure</div></br>
				<select  id="selectLottoEstrazioneRinforzi">
					<option value="" disabled selected>Seleziona un lotto</option>
					<?php
						$query="SELECT * FROM lotti where chiuso='false' order by lotto";
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
							while($row=sqlsrv_fetch_array($result))
							{
								echo '<option value="'.$row["lotto"].'">'.$row["lotto"].'</option>';
							}
						}
					?>
				</select>
				<button id="btnConfermaCodiceExcel" onclick="confermaCodiciExcel()">Conferma</button>
				<button id="btnScaricaExcelEstrazioneRinforzi" onclick="tableToExcel('myTableEstrazioneRinforzi')">Esporta in Excel</button>
				<div id="containerEstrazioneRinforzi"></div>
			</div>
		</div>
		<div id="push"></div>
		<div id="footer">
			<b>Marine&nbspInteriors&nbspS.p.A.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspSegaluzza&nbsp33170&nbspPordenone&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp0434612811
		</div>
	</body>
</html>











