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

if(empty($action))
{
   header('Location:../index.php');
}

switch ($action) 

{
  case "new":

  //Creer un nouveau statut
  
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

        	$insertstatut = $bdd->prepare('INSERT INTO social_statut (statut_contenu ,staut_photo ,membre_id,statut_date)
        		                           VALUES(:contenu,:photo, :membre,NOW())');

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



     case "comment":

     $statut = (isset($_GET['s']))?$_GET['s']:"";
     $com = (isset($_POST['text']))?$_POST['text']:"";

     if(empty($statut) || empty($com))
     {
       header('Location:./index.php');
     }

     $insertcom = $bdd->prepare('INSERT INTO social_st_comment (commentaires_text , membre_id , statut_id)
                                 VALUES (:com , :membre , :statut)');
     $insertcom->execute(array('com' => $com , 
                               'membre' => $id ,
                              'statut' => $statut
                               ));

     $_SESSION['flash']['success'] = " Commentaire enregistreé ";

      header('Location:index.php');

          break;

       case "editco":

       /* c: commentaire , 
          s: statut , 
          text : contenu du commentaire
       */

                $com_id = (isset($_GET['c']))?$_GET['c']:"";
                $statut = (isset($_GET['s']))?$_GET['s']:"";
                $contenu_com = (isset($_POST['text']))?$_POST['text']:"";

                if(empty($com_id) || empty($statut) || empty($contenu_com))
                {
                  header('Location:./index.php');
                }

        $updatecom = $bdd->prepare('UPDATE social_st_comment 
                                    SET commentaires_text = :commentaire 
                                    WHERE commentaires_id = :com AND statut_id = :statut');
        $updatecom->execute(array(
                                   'commentaire' => $contenu_com,
                                    'com' => $com_id,
                                    'statut' => $statut
                                  ));

        $_SESSION['flash']['success'] = 'Commentaire mis a jour';

       break;

    default:
       header('Location:index.php');  
}  
