<div id="header" class="header" >
	<input type="button" id="nascondi" value="" onclick="nascondi()" data-toggle='tooltip' title='Apri menu' />
	<div id="pageName" class="pageName"><?php echo $pageName; ?></div>
	<div id="user" class="user">
		<script>
			document.getElementById("user").addEventListener("mouseover", function()
			{
				document.getElementById('user').style.width = "415px";
				document.getElementById('btnLogout').style.width = "90px";
				/*setTimeout(function()
				{ 
					document.getElementById('btnLogout').value="Logout";
				}, 500);*/
			});
			document.getElementById("user").addEventListener("mouseout", function()
			{
				//document.getElementById('btnLogout').value=" ";
				document.getElementById('user').style.width = "300px";
				document.getElementById('btnLogout').style.width = "0px";							
			});
		</script>
		<div id="username"><?php echo $_SESSION['Username']; ?></div>
		<input type="submit" value=" " id="btnUser">
		<input type="button" value="Logout" id="btnLogout" onclick="logoutB()">
	</div>
</div>
<div id="navBar">
	<input type="button" id="nascondi2" value="" onclick="nascondi()" data-toggle='tooltip' title='Chiudi menu' />
	<div id="stato" style="display:none">Chiuso</div>
	<input type="button" value="Homepage" data-toggle='tooltip' title='Homepage' class="btnGoToPath" onclick="goToPath('index.php')" />
	<input type="button" value="Gestione linea" data-toggle='tooltip' title='Gestione linea' class="btnGoToPath" onclick="goToPath('gestisciLinea.php')" />
	<input type="button" value="Importa lotti" data-toggle='tooltip' title='Importa lotti' class="btnGoToPath" onclick="goToPath('gestisciLotti.php')" />
	<!--<input type="button" value="Gestione dxf" data-toggle='tooltip' title='Gestione dxf' class="btnGoToPath" onclick="goToPath('gestisciDXF.php')" />-->
	<input type="button" value="Gestione lane" data-toggle='tooltip' title='Gestione lane' class="btnGoToPath" onclick="goToPath('gestioneLane.php')" />
	<input type="button" value="Scaffalatura rinforzi" data-toggle='tooltip' title='Scaffalatura rinforzi' class="btnGoToPath" onclick="goToPath('scaffalaturaRinforzi.php')" />
	<input type="button" value="Tabelle parametri" data-toggle='tooltip' title='Tabelle parametri' class="btnGoToPath" onclick="goToPath('tabelleParametri.php')" />
	<input type="button" value="Estrazione rinforzi" data-toggle='tooltip' title='Estrazione rinforzi' class="btnGoToPath" onclick="goToPath('estrazioneRinforzi.php')" />
</div>