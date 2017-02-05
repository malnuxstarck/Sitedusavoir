

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

$tuto = isset($_GET['tuto'])?$_GET['tuto']:"";
$action = isset($_GET['action'])?$_GET['action']:"";


if(empty($tuto) || empty($action))
{
	header('Location:./index.php');
}

switch($action)
{



	case "auteur":

	$auteur_a = (!empty($_POST['auteur']))?$_POST['auteur']:"";

	$auteur = $bdd->prepare('SELECT membre_id 
		                     FROM membres 
		                     WHERE membre_pseudo = LOWER(:pseudo)');

	$auteur->bindParam(':pseudo',$auteur_a,PDO::PARAM_INT);
	$auteur->execute();
	$aut = $auteur->fetch();

	$aut = $aut['membre_id'];

	if($aut)
	{
      $req = $bdd->prepare('INSERT INTO tutos_par (membre_id , tutos_id) VALUES(:membre, :tuto)');
      $req->bindParam(':membre',$aut,PDO::PARAM_INT);
      $req->bindParam(':tuto',$tuto,PDO::PARAM_INT);

      $req->execute();

      $_SESSION['flash']['success'] = " L' auteur a eté ajouter avecs succes , il peut desormer modifier le tuto ";
      header('Location:./editiontuto.php?tuto='.$tuto);

	}
	else
	{
        $_SESSION['flash']['danger'] = " L' auteur semble ne pas exister ";
        header('Location:./ajouter.php?action=auteur&tuto='.$tuto);
	}

	break;

	case "partie":

       $partietitre = (!empty($_POST["partie_titre"]))?$_POST["partie_titre"]:"";
       $contenu = (!empty($_POST["contenu"]))?$_POST["contenu"]:"";

       if(empty($contenu) || empty($partietitre))
       {
          $_SESSION['flash']['success'] = " Le titre et/ou le contenu de la partie est vide ";
          header('Location:./editiontuto.php?tuto='.$tuto);
       }
       
       else
       {
         $insertpart = $bdd->prepare('INSERT INTO tutos_parties(parties_titre , parties_contenu , tutos_id) 
         	                          VALUES(:titre , :contenu , :tuto)');
         $insertpart->execute(array('titre' => $partietitre , 'contenu' => $contenu, 'tuto' => $tuto));

         $_SESSION['flash']['success'] = " Vous avez cree la partie avec succes";
         header('Location:./editiontuto.php?tuto='.$tuto);
       }




	break;


}