<?php
session_start();

session_destroy();

unset($_SESSION['flash']);

setcookie('souvenir',NULL,-1);

include_once('./includes/debut.php');
include_once('./includes/menu.php');


if ($id == 0) 
{
	
	$_SESSION['flash']['danger'] = 'Vous devez etre Connecter pour vous deconnecter';

	header('Location:connexion.php');

}

else
{
	$_SESSION['flash']['success'] = 'Vous êtes à présent déconnecté';
	header('Location:connexion.php');
}




?>