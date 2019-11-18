<!DOCTYPE HTML>
<?php
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	if(!$conn)
		echo "connessione fallita";
?>
<html>
	<head>
		<title>Login</title>
			<link rel="stylesheet" href="/mi_incollaggio/stazioni/css/styleA.css" />
			<style>
				/*@import url(http://fonts.googleapis.com/css?family=Exo:100,200,400);
				@import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro:700,400,300);*/
			</style>
			<script>
			function login(numero) 
			{
				//var numero=document.getElementById("numero").value;
				//window.alert(numero);
				var l= numero.toString().length;
				if(l==2)
				{
					try
					{
						document.getElementById(numero).style.background="#f1f1f1";
					}
					catch(e)
					{
					}
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById("result").innerHTML = this.responseText;
							if(document.getElementById("result").innerHTML=="Login fallito")
							{
								
							}
							else
								window.location = 'lana.php';
						}
					};
					xmlhttp.open("POST", "login1.php?numero=" + numero , true);
					xmlhttp.send();
					document.getElementById("numero").value="";
				}
			}
			function focusOnInput()
			{
				document.getElementById("numero").focus();
				setInterval(function()
				{
					document.getElementById("numero").focus();
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
		</script>
	</head>
	<body onload="eliminaFlagSvuotaLinea();focusOnInput();" onclick="focusOnInput()" onmouseover="focusOnInput()">
		<div id="container" class="container" onclick="focusOnInput()" onmouseover="focusOnInput()">
			<div id="immagine" class="immagine"></div>
			<div id="accedi" class="accedi">
				<div id="text" class="text">Accedi<input id="numero" type="text" name="numero" value="" onkeyup="login(this.value)" /></div>
				<div  id="input" class="input">
					<form id="autenticazioneF">
						<table id="myTable">
							<tr class="Theader" >
								<th>Numero</th>
								<th>Username</th>
							</tr>
							<?php
							$query="SELECT * FROM utenti ORDER BY numero ";
							$result=sqlsrv_query($conn,$query);
							if($result==FALSE)
							{
								$query=str_replace("'","*APICE*",$query);
								$testoErrore=print_r(sqlsrv_errors(),TRUE);
								$testoErrore=str_replace("'","*APICE*",$testoErrore);
								$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
								$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$query','".$testoErrore."','login')";
								$resultErrori=sqlsrv_query($conn,$queryErrori);
								$query=str_replace("*APICE*","'",$query);
								echo "<br><br>Errore esecuzione query<br>Query: ".$query."<br>Errore: ";
								die(print_r(sqlsrv_errors(),TRUE));
							}
							else
							{
								while($row=sqlsrv_fetch_array($result))
								{
									?><tr id="<?php echo $row['numero']; ?>" onclick="login(<?php echo "'".$row['numero']."'"; ?>)"><?php
										echo "<td style='font-weight:bold;'>".$row['numero']."</td>";
										echo "<td>".$row['username']."</td>";
									echo "</tr>";
								}
							}
							?>
						</table>
						<div id="result" class="result">&nbsp</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>