<?php
	include("includes/identifiants.php");
	include("includes/debut.php");
	include("includes/menu.php");

	if(!empty($_GET))
	{
		$mots = $_GET['cherche'];
		$mots = explode(' ',$mots);	
	}

	else
	{
		header('Location:./index.php');
	}
?>
