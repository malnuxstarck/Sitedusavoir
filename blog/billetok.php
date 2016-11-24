<?php

session_start();

include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");


$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';


switch($action)
{
	case "creer":

	$titre = htmlspecialchars($_POST['titre']);
	$message = htmlspecialchars($_POST['message']);

	$logo = $_FILES['logo'];

	$banniere = $_FILES['banniere'];

	$taillemax = 100024;

	

	$extension_valides = array('png','jpeg','jpg','gif');

	$infosfichiers = pathinfo($logo['name']);

	$extension = $infosfichiers['extension'];


	if(!in_array($extension,$extension_valides) OR $logo['error'] != 0)
	{
		echo '<p> Extension du logo invalide ou une erreur lors de l\'envoi du fichier</p>';
	}

	else
	{

		if($logo['size'] > $taillemax)
		{
			echo '<p>  Fichier trop lourd </p>';

		}

		
     }


		

	if(verif_auth(MODO))
	{

        
		    if(empty($logo) || empty($titre) || empty($message))
			{
				$_SESSION['flash']['danger'] = 'Vous devez remplir tous les champs suivis d\'etoile';
				header('Location:billet.php?action=creer');
			}


           $nomlogo = move_logo($logo);

		$req = $bdd->prepare('INSERT INTO billets (billet_titre,billet_contenu,datebillet,billet_logo) VALUES(:titre,:message,NOW(),:nomlogo)');

		$req->bindValue(':titre',$titre,PDO::PARAM_STR);
		$req->bindValue(':message',$message,PDO::PARAM_STR);
		$req->bindValue(':nomlogo',$nomlogo,PDO::PARAM_STR);

		$req->execute();

        $billetid = $bdd->lastInsertId();
        
		$req->closeCursor();

		$requete = $bdd->prepare('INSERT INTO auteurs (membre_id,billet_id) VALUES(:id,:billet)');

		$requete->execute(array('id'=> $id, 'billet'=> $billetid));

		$requete->closeCursor();

		$_SESSION['flash']['danger'] = 'Vous avez creer un nouveau billet';

		header('Location:index.php');


	}

	break;
	
	case "edit":
	case "voir":
	case "comment":

	default: 

	header('Location:index.php');
}