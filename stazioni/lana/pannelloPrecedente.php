<!DOCTYPE HTML>
<html>
	<head>
		<script>
			function getPannelloPrecedente()
			{
				var codPan= document.getElementById('codPan').value;
				
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText!='')
							document.getElementById('disegnoLana1Precedente').innerHTML=this.responseText+'<hr size="1" color="gray" />';
					}
				};
				xmlhttp.open("POST", "disegnoLana1Precedente.php?codPan="+codPan, true);
				xmlhttp.send();
				
				var xmlhttp2 = new XMLHttpRequest();
				xmlhttp2.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('disegnoLana2Precedente').innerHTML=this.responseText;
					}
				};
				xmlhttp2.open("POST", "disegnoLana2Precedente.php?codPan="+codPan, true);
				xmlhttp2.send();
			}
			function focusOnInput()
			{
				document.getElementById('textChiudi').focus();
			}
		</script>
	</head>
	<body onclick="focusOnInput();" onload="focusOnInput();getPannelloPrecedente()" style="height:1080px;width:1920px;display: inline-block;margin:0px auto;text-align:center;">
		<?php
			include "Session.php";
			set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
			include "connessione.php";
			if(!$conn)
				echo "connessione fallita";
			
			$codPan=$_GET['codPan'];
			$codPan="+".substr($codPan,1);

			echo '<input type="hidden" id="codPan" value="'.$codPan.'" />';
			
			echo '<br><input type="button" onclick="window.close()" value="Chiudi" style="background-color:#F0F0F0;display:inline-block;height:70px;margin-top:20px;width:400px;font-family:sans-serif;font-size:180%;font-weight:bold;outline:none;border:1px solid #D1D1D1;border-radius:5px;cursor:pointer;float:center;"  />';
			
			echo "<br><br><b style='font-size:300%;color:black;font-family:sans-serif;margin-top:50px' >$codPan</b><br>";
			
		?>
	<input type="text" id="textChiudi" onkeyup="window.close()" value=" " style="border:1px solid white;width:0px;height:0px;outline:none;" />	
	<hr size='1' color="gray" />
	<div id="disegnoLana1Precedente"></div>	
	
	<div id="disegnoLana2Precedente"></div>
	<hr size='1' color="gray" />
	</body>
</html>