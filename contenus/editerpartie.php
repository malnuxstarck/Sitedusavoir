<?php

$titre="Edition partie | SiteduSavoir.com";
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

echo '<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Blog</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./editionarticle.php?article=<?php echo $art;?>"> Edition Article</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><span style="color:black;">Edition partie </span></li>
         </ul>
  </div>
 <div class="page">';


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
		
			echo '<h1 class="titre">Editer partie </h1>

			<div class="formulaire formulaire-tuto">

				<form method="POST" action="editerpartie.php?action=edit&article='.$art.'&partie='.$partie.'">
				     <div class="input input-tuto">
				          <label for="titre"></label>
				          <input type="text" name="titre" value="'.$parties['parties_titre'].'"/>
				     </div>

				     <div class="textarea textarea-tuto">
				         <textarea name="contenu">'.$parties['parties_contenu'].'</textarea>
				     </div>

				    <div class="submit submit-tuto">
				         <input type="submit" value="Editer"/>
				    </div>

				</form>
			</div>
			</div>';
			include '../includes/footer.php';
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

