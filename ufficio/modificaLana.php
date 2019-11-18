<?php
	
	set_include_path('C:\xampp\htdocs\mi_incollaggio\ufficio');
	include "connessione.php";
	include "Session.php";
	
	
	$id_lana=$_REQUEST['id_lana'];
	$Nome=$_REQUEST['Nome'];
	$Codmat=$_REQUEST['Codmat'];
	$Larg=$_REQUEST['Larg'];
	$halt=$_REQUEST['halt'];
	$pos1=$_REQUEST['pos1'];
	$pos2=$_REQUEST['pos2'];
	$pos3=$_REQUEST['pos3'];
	$pos4=$_REQUEST['pos4'];
	$pos5=$_REQUEST['pos5'];
	
	$Codmat=substr($Codmat,1);
	$Codmat='+'.$Codmat;
		
	$query="SELECT * FROM Lana_Prefresata WHERE Nome='$Nome' AND id_lana<>$id_lana";
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
		$rows = sqlsrv_has_rows( $result );
		if ($rows === true)
		{
			echo "Errore";
			die();
		}
	}
		
	$query2="UPDATE Lana_Prefresata SET Nome='$Nome', Larg=$Larg, halt=$halt, Codmat='$Codmat', pos1=$pos1, pos2=$pos2, pos3=$pos3, pos4=$pos4, pos5=$pos5 WHERE id_lana=$id_lana";
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		$query2=str_replace("'","*APICE*",$query2);
		$testoErrore=print_r(sqlsrv_errors(),TRUE);
		$testoErrore=str_replace("'","*APICE*",$testoErrore);
		$testoErrore=str_replace('"','*DOPPIOAPICE*',$testoErrore);
		$queryErrori="INSERT INTO erroriWeb (data,query,testo,utente) VALUES ('".date('d/m/Y H:i:s')."','$query2','".$testoErrore."','".$_SESSION['Username']."')";
		$resultErrori=sqlsrv_query($conn,$queryErrori);
		$query2=str_replace("*APICE*","'",$query2);
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else 
		echo "ok";
?>