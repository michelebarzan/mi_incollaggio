<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
	
	$pageName="Scaffalatura rinforzi";
?>
<html>
	<head>
		<title><?php echo $pageName; ?></title>
			<link rel="stylesheet" href="/mi_incollaggio/ufficio/css/styleV3.css" />
			<script src="struttura.js"></script>
			<script>
				function modifica(riga)
				{
					try
					{
						var valore = document.getElementById(riga).innerHTML;
					}
					catch(err)
					{
						window.alert(err.message);
					}
					//window.alert(riga);
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							//document.getElementById("risultato").innerHTML = "&nbspRisultato:&nbsp" +  this.responseText;
							//window.alert(this.responseText);
							if(this.responseText!="ok")
								window.alert("Errore: impossibile modificare");
						}
					};
					xmlhttp.open("POST", "aggiornaScaffalaturaRinforzi.php?valore="+valore+"&riga="+riga, true);
					xmlhttp.send();
				}
			</script>
	</head>
	<body>
		<?php include('struttura.php'); ?>
		<div id="container">
			<div id="content">
				<div id="immagineLogo" class="immagineLogo" ></div>
				<div id="tabellaRinforzi">
				<?php
					$valori=array();
					$riga=array();
					
					$query="SELECT * FROM Pc_griT";
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
						echo '<div style="background:#D1D1D1;margin-top:10px;box-sizing: border-box;width:90%;margin-left:5%;border-left: 1px solid black;border-right: 1px solid black;border-top: 1px solid black;font-weight:bold;font-size:150%;color:gray;font-family:Arial;height:50px;line-height:50px" >Prelievo rinforzi</div>';
						echo '<table id="myTableRinforzi">';
							while($row=sqlsrv_fetch_array($result))
							{
								array_push($valori,$row['min']);
								array_push($riga,$row['Riga']);
							}
							$i=0;
							echo '<tr>';
							while($i<(count($valori)/2))
							{
								?>
								<td id="<?php echo $riga[$i]; ?>" onfocusout='modifica("<?php echo $riga[$i]; ?>")' contenteditable><?php echo $valori[$i] ?>  </td>
								<?php
								$i++;
							}
							echo '</tr>';
							echo '<tr>';
							while($i<count($valori))
							{
								?>
								<td id="<?php echo $riga[$i]; ?>" onfocusout='modifica("<?php echo $riga[$i]; ?>")' contenteditable><?php echo $valori[$i] ?>  </td>
								<?php
								$i++;
							}
							echo '</tr>';
						echo '</table>';
					}
				?>
				</div>
			</div>
		</div>
		<div id="footer">
			<b>Marine&nbspInteriors&nbspS.p.A.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspSegaluzza&nbsp33170&nbspPordenone&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp0434612811
		</div>
	</body>
</html>

