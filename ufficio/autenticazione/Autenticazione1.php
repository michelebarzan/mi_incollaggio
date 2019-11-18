<!DOCTYPE HTML>
<html>
	<head>
		<title>Autenticazione</title>
			<link rel="stylesheet" href="/mi_incollaggio/ufficio/css/styleA.css" />
			<style>
				/*@import url(http://fonts.googleapis.com/css?family=Exo:100,200,400);
				@import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro:700,400,300);*/
			</style>
			<script>
			function login() 
			{
				var username=document.getElementById("username").value;
				var password=document.getElementById("password").value;
				var ricordaPassword=document.getElementById("ricorda").checked;
				//window.alert(username+password+ricordaPassword);
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=="ok")
							window.location = '/mi_incollaggio/ufficio/index.php';
						else
						{
							document.getElementById("result").innerHTML = this.responseText;
							document.getElementById("password").style.border="1px solid red";
							document.getElementById("username").style.border="1px solid red";
							document.getElementById("password").value="";
						}
					}
				};
				xmlhttp.open("POST", "autenticazione.php?username=" + username + "&password=" + password + "&ricordaPassword=" + ricordaPassword, true);
				xmlhttp.send();
			}
		</script>
	</head>
	<body>
		<div id="container" class="container">
			<div id="immagine" class="immagine"></div>
			<div id="accedi" class="accedi">
				<div id="text" class="text">Accedi</div>
				<div  id="input" class="input">
					<form id="autenticazioneF">
						<?php
						if(isset($_COOKIE['username']) && $_COOKIE['username']!="no")
						{
							?>
							<input type="text" value="<?php echo $_COOKIE['username']; ?>" placeholder="Username" name="username" id="username" required><br>
							<?php
						}
						else
						{
							?>
							<input type="text" placeholder="Username" name="username" id="username" required><br>
							<?php
						}
						?>
						<?php
						if(isset($_COOKIE['password']) && $_COOKIE['password']!="no")
						{
							?>
							<input type="password" value="<?php echo $_COOKIE['password']; ?>" placeholder="Password" name="password"  id="password" required><br>
							<?php
						}
						else
						{
							?>
							<input type="password" placeholder="Password" name="password" id="password" required><br>
							<?php
						}
						?>
						<input type="button" value="Login" onclick="login()"><br>
						<div id="result" class="result">&nbsp</div>
						<div id="ricordaPassword" class="ricordaPassword"><input type="checkbox" name="ricordaPassword" id="ricorda" checked>Ricorda password</div>
					</form>
				</div>
				<div id="nuovaPassword" class="nuovaPassword"><a href="cambiaPassword.html" style="color:#666f77; font-weight:bold">Cambia password</a></div><br>
			</div>
		</div>
	</body>
</html>