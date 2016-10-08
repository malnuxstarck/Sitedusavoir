<?php

$id = $_GET['id'];

$token = $_GET['token'];

require './includes/identifiants.php';

$req = $bdd->prepare('SELECT * FROM membres WHERE membre_id = :id');

$req->execute(array('id'=> $id));

$user = $req->fetch();

session_start();

if($user AND $user['token'] == $token)
{
	if(session_status() == PHP_SESSION_NONE)
	  session_start();

	
	
	$_SESSION['flash']['success'] =" votre compte a ete validee ! Vous pouvez vous connecter";

	$req = $bdd->prepare('UPDATE membres SET token = NULL, membre_inscrit = NOW() WHERE membre_id = :id ');

	$req->execute(array('id' => $id));

    header('Location:connexion.php');
}
else
{
	$_SESSION['flash']['danger'] = "Token plus valide";

	header('Location:connexion.php');

}