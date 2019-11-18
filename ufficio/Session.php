<?php
session_start();
if(!isset($_SESSION['Username']))
{
	header("Location: autenticazione/autenticazione1.php");
}
?>