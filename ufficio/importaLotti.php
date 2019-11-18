<?php

	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
		
	echo "<b>Importa i lotti manualmente dal file Access 'importaLotti.accdb'</b><br>";
		
	//IMPORTA LOTTI-----------------------------------------------------------------------------------------------------------------------
	
	//if(set_time_limit(120))
	//{
	    //$output=shell_exec( '"C:\\Program Files (x86)\\Microsoft Office\\Office14\\MSACCESS.EXE" "C:\\xampp\\htdocs\\mi_incollaggio\\ufficio\\importaLotti.accdb" 2>&1');
		//echo $output;
		//echo "<b style='color:green'>Lotti importati</b><br>";
		//set_time_limit(30);
	//}
	//else
		//echo "<b style='color:red'>Errore di sistema: </b>impossibile importare i lotti. Contattare l' amministratore";
	
	

?>