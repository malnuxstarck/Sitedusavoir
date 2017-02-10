<?php

$titre="Traitement | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

if(!$id)
{
	header('Location:../connexion.php');
}


$partie =(isset($_GET['partie']))?$_GET['partie']:"";
$art = (isset($_GET['article']))?$_GET['article']:"";
$action = (isset($_GET['action']))?$_GET['action']:"";


if(empty($partie) || empty($art) || empty($action))
{
	$_SESSION['flash']['danger'] = "Aucune action et/ou partie selectionner";
	header("Location:./index.php");
}

switch ($action) {

	case 'edit':

       if(empty($_POST))
	   {

	        $req = $bdd->prepare('SELECT * 
				                  FROM articles_parties 
				                  WHERE articles_id = :article AND parties_id = :partie');

			$req->execute(array('article' => $art , 
				                'partie' => $partie));

			$parties = $req->fetch();
		
			echo '<form method="POST" action="editerpartie.php?action=edit&article='.$art.'&partie='.$partie.'">
			     <div class="titre">
			          <input type="text" name="titre" value="'.$parties['parties_titre'].'"/>
			     </div>

			     <div class="textarea">
			         <textarea name="contenu">'.$parties['parties_contenu'].'</textarea>
			     </div>

			    <div class="submit">
			         <input type="submit" value="Editer"/>
			    </div>

			</form>';
	   }
	   else
	   {

	        $titre = $_POST['titre'];
	        $contenu = $_POST['contenu'];

		   	if(empty($titre) || empty($contenu))
		   	{
		   		$_SESSION['flash']['success'] = "Le titre et/ou la partie est vide ";
		   		header('Location:./editionarticle.php?article='.$art);
		   	}

		   	$insertpart = $bdd->prepare('UPDATE  articles_parties  SET parties_titre = :titre,parties_contenu = :contenu 
		   		                         WHERE articles_id = :article');
		   	$insertpart->bindParam(':article',$art,PDO::PARAM_INT);
		   	$insertpart->bindParam(':titre',$titre,PDO::PARAM_STR);
		   	$insertpart->bindParam(':contenu',$contenu,PDO::PARAM_STR);

		    $insertpart->execute();

		    $_SESSION['flash']['success'] = "La partie est mise a jour ";
		   	header('Location:./editionarticle.php?article='.$art);


	   }



		break;
	
	case "sup":

	        echo '<p>
                       Etes vous sur de vouloir supprimmer cette partie ?
                       <p><a href="editerpartie.php?action=sup&article='.$art.'&partie='.$partie.'&sur=1">Oui</a></p>
                       <p><a href="editionarticle.php?article='.$art.'">Non</a></p>
	              </p>';

	         if(isset($_GET['sur']))
	         {
	         	$req = $bdd->prepare('DELETE FROM articles_parties WHERE parties_id = :partie');

	         	$req->execute(array('partie' => $partie));

	         	$_SESSION['flash']['success'] = "Partie bien supprimer ";

                header('Location:./editionarticle.php?article='.$art);
	         }     


		# code...
		break;
}

