

<?php


// traitement des donnees envoyees par ajouter.php

$titre="Traitement | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

if(!$id)
{
	header('Location:../connexion.php');
}

$idContenu = isset($_GET['contenu'])?$_GET['contenu']:"";
$action = isset($_GET['action'])?$_GET['action']:"";


if(empty($idContenu) || empty($action))
{
	  header('Location:./index.php');
}

switch($action)
{
  case "auteur":

	$managerAuteur = new ManagerAuteur($bdd);
  $infosAuteur = $managerAuteur->auteurInfos($_POST['auteur']);

  if(!empty($infosAuteur))
	{
      $auteur = new Auteur($infosAuteur);
      $auteur->setIdcontenu($idContenu);

      if($id != $auteur->membre()){

            $managerAuteur->ajouterAuteur($auteur->membre() , $auteur->idcontenu());
            $_SESSION['flash']['success'] = "L' auteur a eté ajouter avecs succes , il peut desormer modifier ce contenu ";
      }
      else
      {
            $_SESSION['flash']['success'] = "Vous etes deja auteur du contenu :) ";
      }
          
      header('Location:./editioncontenu.php?contenu='.$auteur->idcontenu());

	}
	else
	{
        $_SESSION['flash']['danger'] = " L' auteur semble ne pas exister ";
        header('Location:./ajouter.php?action=auteur&contenu='.$idContenu);
	}

	break;

	case "partie":

      $partie = new Partie($_POST);
      $managerPartie = new ManagerPartie($bdd);

      $partie->setIdcontenu($_GET['contenu']);
      $managerPartie->verifierChamps($partie);

       if($managerPartie->_iErrors > 0 )
       {
          $message = '';
          foreach ($managerPartie->errors() as $value) {

                $message.= $value.'</br>';
              }

          $_SESSION['flash']['danger'] = $message;
          header('Location:./editioncontenu.php?='.$partie->idcontenu());
       }
       
       else
       {
            $managerPartie->ajouterPartie($partie);
            $_SESSION['flash']['success'] = "Partie creer avec success :) ";
            header('Location:./editioncontenu.php?contenu='.$partie->idcontenu());
        }
break;

  default:
         header('Location:./index.php');


}