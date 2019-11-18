<!DOCTYPE HTML>
<html>
	<head>
	</head>
	<body onload="javascript:document.getElementById('pdf').focus();" style="height:1080px;width:1920px;display: inline-block;margin:0px auto;">
	<?php
		include "Session.php";
		set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
		include "connessione.php";
		if(!$conn)
			echo "connessione fallita";
		
		$codPan=$_GET['codPan'];
		$codPan="+".substr($codPan,1);
		//echo $codPan;
		echo '<iframe id="pdf" src="/mi_incollaggio/stazioni/disegni/PdfPan/'.$codPan.'.pdf#zoom=200" type="application/pdf" width="99%" height="99%" ></iframe>';
	?>
	</body>
</html>