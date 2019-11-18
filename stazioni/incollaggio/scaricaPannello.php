<?php
	


	include "Session.php";
	set_include_path('C:\xampp\htdocs\mi_incollaggio\stazioni');
	include "connessione.php";
	
	$codPan=$_REQUEST['codpan'];
	$codPan="+".substr($codPan,1);
	
	$lotto=$_REQUEST['lotto'];
	$qnt=$_REQUEST['qnt'];
	$id_bancale=$_REQUEST['id_bancale'];
		
	/*echo $codPan." ";
	echo $lotto." ";
	echo $qnt." ";
	echo $id_bancale."";*/
		
	$dataOra = date('d/m/Y h:i:s', time());
	$data = date('d/m/Y', time());
		
	$i=0;	
	while($i<$qnt)
	{
		$query2="INSERT INTO [dbo].[pannelli_prodotti]
			    ([id_produzione]
			   ,[posizione]
			   ,[codpan]
			   ,[ruotato]
			   ,[mCaricamento]
			   ,[vRinforzi]
			   ,[vLana]
			   ,[vIncollaggio]
			   ,[mRinforzi]
			   ,[mLana]
			   ,[mIncollaggio]
			   ,[eliminato]
			   ,[dataOraCaricamento]
			   ,[dataOraRinforzi]
			   ,[dataOraLana]
			   ,[dataOraIncollaggio]
			   ,[lotto]
			   ,[bancale]
			   ,[mAcqua]
			   ,[dataOraAcqua]
			   ,[mRinforzi1]
			   ,[vRinforzi1]
			   ,[finitura]
			   ,[utenteCaricamento]
			   ,[dataOraRinforzi1]
			   ,[utenteRinforzi]
			   ,[utenteRinforzi1]
			   ,[utenteLana]
			   ,[utenteIncollaggio]
			   ,[dataProduzione])
				VALUES
				(0
			   ,0
			   ,'$codPan'
			   ,'".getRuotato($conn,$codPan)."'
			   ,'true'
			   ,'false'
			   ,'false'
			   ,'false'
			   ,'true'
			   ,'true'
			   ,'true'
			   ,'manuale'
			   ,'$dataOra'
			   ,'$dataOra'
			   ,'$dataOra'
			   ,'$dataOra'
			   ,'$lotto'
			   ,$id_bancale
			   ,'true'
			   ,'$dataOra'
			   ,'true'
			   ,'false'
			   ,'".getFinitura($conn,$codPan)."'
			   ,".getUtente($conn,$_SESSION['Username'])."
			   ,'$dataOra'
			   ,".getUtente($conn,$_SESSION['Username'])."
			   ,".getUtente($conn,$_SESSION['Username'])."
			   ,".getUtente($conn,$_SESSION['Username'])."
			   ,".getUtente($conn,$_SESSION['Username'])."
			   ,'$data')";
			   
			   //echo $query2;
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
			die();
		}
		$i++;
	}
	echo "ok";
	
	
	
	
	
	
	
	
	
	function getFinitura($conn,$codPan)
	{
		$query="SELECT TOP(1) finitura FROM lotti_pannelli WHERE pannello='$codPan'";

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
				return $row['finitura'];
			}
		}
	}
	function getIdBancale($conn,$lotto)
	{
		$query="SELECT TOP (1) bancali.* FROM bancali WHERE bancali.chiuso='false' AND bancali.lotto='$lotto'";
	
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
				while($row=sqlsrv_fetch_array($result))
				{
					$id_bancale=$row['id_bancale'];
					$nome=$row['nome'];
					$numero=$row['numero'];
				}
				echo $id_bancale;
			}
			else
			{
				$numero=getNumero($conn,$lotto);
				$nome=creaBancale($conn,$lotto,$numero);
				$id_bancale=getId_bancale($conn,$nome);
				echo $id_bancale;
			}
		}
	}
	function creaBancale($conn,$lotto,$numero)
	{
		$nome="B".$lotto.".".$numero;
		$dataOraCreazione= date('d/m/Y h:i:s', time());
		$query="INSERT INTO bancali (nome,lotto,numero,dataOraCreazione,chiuso) VALUES ('$nome','$lotto',$numero,'$dataOraCreazione','false')";

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
			return $nome;
		}
	}
	
	function getNumero($conn,$lotto)
	{
		$query="SELECT ISNULL( MAX(numero),0) AS numero FROM bancali WHERE lotto='$lotto'";

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
				return $row['numero']+1;
			}
		}
	}
	function getId_bancale($conn,$nome)
	{
		$query="SELECT id_bancale FROM bancali WHERE nome='$nome'";
		//echo $query;
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
				return $row['id_bancale'];
			}
		}
	}
	function getRuotato($conn,$codPan)
	{
		$query="SELECT ruotato FROM pannelli_ruotati WHERE CODPAS='$codPan'";
		
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
				return $row['ruotato'];
			}
		}
	}
	function checkPannello($conn,$codPan)
	{
		$query="SELECT * FROM lotti_pannelli WHERE pannello='$codPan'";
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
				return true;
			else   
				return false;  
		}
	}
	
	function pannelli_prodotti($conn,$id_produzione)
	{
		$query="INSERT INTO [dbo].[pannelli_prodotti]([id_produzione],[posizione],[codpan],[ruotato],[mCaricamento],[vRinforzi],[vLana],[vIncollaggio],[mRinforzi],[mLana],[mIncollaggio],[eliminato],[dataOraCaricamento],[dataOraRinforzi],[dataOraLana],[dataOraIncollaggio],[lotto],[bancale],[mAcqua],[dataOraAcqua],[mRinforzi1],[vRinforzi1],[finitura],[utenteCaricamento],[dataOraRinforzi1],[utenteRinforzi],[utenteRinforzi1],[utenteLana],[utenteIncollaggio],[dataProduzione]) SELECT produzione.*,getDate() FROM produzione WHERE id_produzione=$id_produzione";
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
			svuotaProduzione($conn,$id_produzione);
	}
	
	function svuotaProduzione($conn,$id_produzione)
	{
		$query="DELETE produzione FROM produzione WHERE id_produzione=$id_produzione";
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
			echo "ok";
	}
	
	function getUtente($conn,$username)
	{
		$query="SELECT id_utente FROM utenti WHERE username='$username'";
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
				return $row['id_utente'];
			}
		}
	}	

?>