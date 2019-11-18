	function topFunction() 
	{
		document.body.scrollTop = 0;
		document.documentElement.scrollTop = 0;
	}
	function apri()
	{
		topFunction();
		var body = document.body,html = document.documentElement;
		var offsetHeight = Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );
		document.getElementById('stato').innerHTML="Aperto";
		document.getElementById('navBar').style.width="300px";
		document.getElementById('nascondi2').style.display="inline-block";
		document.getElementById('navBar').style.height = offsetHeight+"px";
		var all = document.getElementsByClassName("btnGoToPath");
		for (var i = 0; i < all.length; i++) 
		{
			all[i].style.width = '100%';
			all[i].style.height='50px';
			all[i].style.borderBottom='1px solid #ddd';
		}
	}
	function chiudi()
	{
		document.getElementById('navBar').style.width = "0px";
		document.getElementById('stato').innerHTML="Chiuso";
		setTimeout(function()
		{ 
			document.getElementById('navBar').style.height = "0px";
			document.getElementById('nascondi2').style.display="none";
			var all = document.getElementsByClassName("btnGoToPath");
			for (var i = 0; i < all.length; i++) 
			{
				all[i].style.width = '0px';
				all[i].style.height='0px';
				all[i].style.borderBottom='';
			}
		}, 1000);
	}
	function logoutB()
	{
		window.location = 'logout.php';
	}
	function gotopath(path)
	{
		window.location = path;
	}
	function homepage()
	{
		window.location = 'index.php';
	}
	function nascondi()
	{
		var stato=document.getElementById('stato').innerHTML;
		if(stato=="Aperto")
		{
			chiudi();
		}
		else
		{
			apri();
		}
	}
	function goToPath(path)
	{
		window.location = path;
	}