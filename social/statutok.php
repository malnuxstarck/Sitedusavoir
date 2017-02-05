<?php

$titre="Social | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

if(!$id)
{
	$_SESSION['flash']['danger'] = " Vous devez etre connecté pour voir cette partie";
	header('Location:../connexion.php');
}


$action = (!empty($_GET['action']))?$_GET['action']:"";

switch ($action) 

{
  case "new":
  
    if(!empty($_POST))
    {
    	$statut = $_POST['statut'];
    	$photo = $_FILES['photo'];

        $i = 0 ;

    	if(empty($statut))
    	{
    		// statut  vide on redirige vers l'accueil de social
    		header('Location:./index.php');
    	}

    	if(isset($photo) AND $photo['error'] == 0)
        {
        	$extension = substr(strchr($photo['name'],'.'),1);

        	$extension_autorises = array('png','jpg','jpeg','gif');

        	$nom_photo = $id .'-'.time().'.'.$extension;

        	if(in_array($extension, $extension_autorises))
        	{
        		move_uploaded_file($photo['tmp_name'], './photos/'.$nom_photo);
        	}

        	$insertstatut = $bdd->prepare('INSERT INTO social_statut (statut_contenu ,staut_photo ,membre_id)
        		                           VALUES(:contenu,:photo, :membre)');

        	$insertstatut->execute(array(
                 'membre' => $id,
                  'photo' => $nom_photo,
                  'contenu' => $statut
               ));

        	$insertstatut->closeCursor();

        	$_SESSION['flash']['success'] = "Statut mis a jours ";

        	header('Location:./index.php');

        }

        else
        {
        	$insertstatut = $bdd->prepare('INSERT INTO social_statut (statut_contenu,membre_id)
        		                           VALUES(:contenu, :membre )');

        	$insertstatut->execute(array(
                 'membre' => $id,
                 'contenu' => $statut
               ));
          $insertstatut->closeCursor();

          $_SESSION['flash']['success'] = "Statut mis a jours ";

        	header('Location:./index.php');
        }
      }
    break;
    
    case "edit":

    //editer un statut 

    $statut_id = (!isset($_GET['s']))?$_GET['s']:"";
    $statut_text = (isset($_POST['statut']))?$_POST['statut']:"";

    if(empty($statut_id) || empty($statut_text))
    {
      header("Location:index.php");  
    }

      $photo = $_FILES['photo'];

      if(isset($photo) AND $photo['error'] == 0)
        {
          $extension = substr(strchr($photo['name'],'.'),1);

          $extension_autorises = array('png','jpg','jpeg','gif');

          $nom_photo = $id .'-'.time().'.'.$extension;

          if(in_array($extension, $extension_autorises))
          {
            move_uploaded_file($photo['tmp_name'], './photos/'.$nom_photo);
          }

          $updatestatut = $bdd->prepare('UPDATE social_statut SET statut_contenu = :contenu ,staut_photo = :photo 
                                         WHERE statut_id = :statut AND membre_id = :membre');

          $updatetstatut->execute(array(
                 'membre' => $id,
                  'photo' => $nom_photo,
                  'contenu' => $statut_text,
                   'statut' => $satut_id
               ));

          $updatestatut->closeCursor();

          $_SESSION['flash']['success'] = "Statut mis a jours ";

          header('Location:./index.php');

        }

        else
        {
          $updatestatut = $bdd->prepare('UPDATE social_statut SET statut_contenu = :contenu
                                         WHERE statut_id = :statut AND membre_id = :membre');

          $updatestatut->execute(array(
                 'membre' => $id,
                 'contenu' => $statut_text,
                  'statut' => $statut_id
               ));

          $updatestatut->closeCursor();

          $_SESSION['flash']['success'] = "Statut mis a jours ";

          header('Location:./index.php');
        }

     break;

    default:
       header("Location:index.php");  
}  
