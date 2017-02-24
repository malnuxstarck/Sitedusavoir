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
$tuto = (isset($_GET['tuto']))?$_GET['tuto']:"";
$action = (isset($_GET['action']))?$_GET['action']:"";


if(empty($partie) || empty($tuto) || empty($action))
{
	$_SESSION['flash']['danger'] = "Aucune action et/ou partie selectionner";
	header("Location:./index.php");
}

echo '<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Tutoriels</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./editiontuto.php?tuto=<?php echo $tuto;?>"> Edition tuto</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><span style="color:black;">Edition partie </span></li>
         </ul>
  </div>
 <div class="page">';

switch ($action) {

	case 'edit':



	   if(empty($_POST))
	   {

	        $req = $bdd->prepare('SELECT * 
				                  FROM tutos_parties 
				                  WHERE tutos_id = :tuto AND parties_id = :partie');

			$req->execute(array('tuto' => $tuto , 
				                'partie' => $partie));

			$parties = $req->fetch();
		
			echo ' <h1 class="titre"> Edition de partie </h1>
			   <div class="formulaire formulaire-tuto">
			          <form method="POST" action="editerpartie.php?action=edit&tuto='.$tuto.'&partie='.$partie.'">
			     <div class="input input-tuto">
			          <label for="titre"></label>
			          <input type="text" name="titre" value="'.$parties['parties_titre'].'"/>
			     </div>

			     <div class="textarea textarea-tuto">
			         <textarea name="contenu">'.trim($parties['parties_contenu']).'</textarea>
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
	   		header('Location:./editiontuto.php?tuto='.$tuto);
	   	}

	   	$insertpart = $bdd->prepare('UPDATE tutos_parties SET parties_titre = :titre, parties_contenu = :contenu
	   		                         WHERE tutos_id = :tuto ');
	   	$insertpart->bindParam(':tuto',$tuto,PDO::PARAM_INT);
	   	$insertpart->bindParam(':titre',$titre,PDO::PARAM_STR);
	   	$insertpart->bindParam(':contenu',$contenu,PDO::PARAM_STR);

	    $insertpart->execute();

	    $_SESSION['flash']['success'] = "La partie est mise a jour ";
	   	header('Location:./editiontuto.php?tuto='.$tuto);


	   }



		break;
	
	case "sup":

	        echo '<p>
                       Etes vous sur de vouloir supprimmer cette partie ?
                       <p class="nouveau-sujet" style="text-align:center;background:#e51c23;"><a href="editerpartie.php?action=sup&tuto='.$tuto.'&partie='.$partie.'&sur=1">Oui</a></p>
                       <p class="nouveau-sujet"  style="text-align:center;" ><a href="editiontuto.php?tuto='.$tuto.'">Non</a></p>
	              </p>';

	         if(isset($_GET['sur']))
	         {
	         	$req= $bdd->prepare('DELETE FROM tutos_parties WHERE parties_id = :partie');

	         	$req->execute(array('partie' => $partie));

	         	$_SESSION['flash']['success'] = "Partie bien supprimer ";

                header('Location:./editiontuto.php?tuto='.$tuto);
	         }     


		# code...
		break;
}

