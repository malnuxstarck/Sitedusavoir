<?php

session_start();

include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");


$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';


switch($action)
{
	case "creer":

	if(verif_auth(MODO))
	{
		$titre = htmlspecialchars($_POST['titre']);
		$message = htmlspecialchars($_POST['message']);

		$req = $bdd->prepare('INSERT INTO billets (billet_titre,billet_contenu,datebillet) VALUES(:titre,:message,NOW())');

		$req->bindValue(':titre',$titre,PDO::PARAM_STR);
		$req->bindValue(':message',$message,PDO::PARAM_STR);

		$req->execute();
        $billetid= $bdd->lastInsertId();
		$req->closeCursor();

		$requete = $bdd->prepare('INSERT INTO auteurs (membre_id,billet_id) VALUES(:id,:billet)');

		$requete->execute(array('id'=> $id, 'billet'=> $billetid));

		$requete->closeCursor();

		header('Location:index.php');


	}

	break;
	
	case "edit":
	case "voir":
	case "comment":

	default: 

	header('Location:index.php');
}