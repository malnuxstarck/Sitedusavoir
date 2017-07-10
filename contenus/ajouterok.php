

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

$art = isset($_GET['article'])?$_GET['article']:"";
$action = isset($_GET['action'])?$_GET['action']:"";


if(empty($art) || empty($action))
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
      $req = $bdd->prepare('INSERT INTO articles_par (membre_id , articles_id) VALUES(:membre, :article)');
      $req->bindParam(':membre',$aut,PDO::PARAM_INT);
      $req->bindParam(':article',$art,PDO::PARAM_INT);

      $req->execute();

      $_SESSION['flash']['success'] = " L' auteur a eté ajouter avecs succes , il peut desormer modifier l article ";
      header('Location:./editionarticle.php?article='.$art);

	}
	else
	{
        $_SESSION['flash']['danger'] = " L' auteur semble ne pas exister ";
        header('Location:./ajouter.php?action=auteur&art='.$art);
	}

	break;

	case "partie":

       $partietitre = (isset($_POST["partie_titre"]))?$_POST["partie_titre"]:"";
       $contenu = (isset($_POST["contenu"]))?$_POST["contenu"]:"";

       if(empty($contenu) || empty($partietitre))
       {
          $_SESSION['flash']['success'] = " Le titre et/ou le contenu de la partie est vide ";
          header('Location:./editionarticle.php?article='.$art);
       }
       
       else
       {
         $insertpart = $bdd->prepare('INSERT INTO articles_parties(parties_titre , parties_contenu , articles_id) 
         	                          VALUES(:titre , :contenu , :article)');
         $insertpart->execute(array('titre' => $partietitre , 'contenu' => $contenu, 'article' => $art));

         $_SESSION['flash']['success'] = " Vous avez cree la partie avec succes";
         header('Location:./editionarticle.php?article='.$art);
       }




	break;


}