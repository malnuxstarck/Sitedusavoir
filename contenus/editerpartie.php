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


$idPartie =(isset($_GET['partie']))?$_GET['partie']:"";
$idContenu = (isset($_GET['contenu']))?$_GET['contenu']:"";
$action = (isset($_GET['action']))?$_GET['action']:"";


if(empty($idPartie) || empty($idContenu))
{
	$_SESSION['flash']['danger'] = "Aucune action et/ou partie selectionner";
	header("Location:./index.php");
}

echo '<ul class="fildariane">
  <li><a href="../index.php">Accueil</a></li>
  <li><a href="./index.php">Blog</a></li>
  <li><a href="./editioncontenu.php?contenu='.$idContenu.'">Edition Article</a></li>
  <li><span>Edition partie</span></li>
</ul>
<div class="page">';


switch ($action) {

	case 'edit':

       if(empty($_POST))
	   {

	        $managerPartie = new ManagerPartie($bdd);
	        $donnees = $managerPartie->donnerLaPartie($idPartie);
	        $partie = new Partie($donnees);

			echo '<h1 class="titre">Editer partie </h1>

			<div class="formulaire formulaire-tuto">

				<form method="POST" action="editerpartie.php?action=edit&contenu='.$partie->idcontenu().'&partie='.$partie->id().'">
				     <div class="input input-tuto">
				          <label for="titre"></label>
				          <input type="text" name="titre" value="'.trim($partie->titre()).'"/>
				     </div>

				     <div class="textarea textarea-tuto">
				         <textarea name="texte">'.trim($partie->texte()).'</textarea>
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

	        $managerPartie = new ManagerPartie($bdd);
	        $partie = new Partie($_POST);
	        $partie->setIdcontenu($_GET['contenu']);

	        $partie->setId($_GET['partie']);

            $managerPartie->verifierChamps($partie);
            $managerPartie->verifierChamps($partie);

            if($managerPartie->_iErrors == 0){

            	$managerPartie->miseAjourPartie($partie);

            	$_SESSION['flash']['success'] = "La partie est mise a jour ";
		   	    header('Location:./editioncontenu.php?contenu='.$partie->idcontenu());
            }
            else
            {
            	$message = '';
            	foreach ($managerPartie->errors() as $value) {

            		$message .= $value .'</br>';
            	}

            	 $_SESSION['flash']['danger'] = $message;
		   	     header('Location:./editioncontenu.php?contenu='.$partie->idcontenu());
            }


	   }



		break;
	
	case "sup":

             $managerPartie = new ManagerPartie($bdd);
	         $donnees = array('id' => $_GET['partie'] , 'idcontenu' => $_GET['contenu']);
	         $partie = new Partie($donnees);

	         if(isset($_GET['sur']))
	         {
	         	$managerPartie->deletePartie($partie);
                $_SESSION['flash']['success'] = "Partie supprimer avec success ";
                header('Location:./editioncontenu.php?contenu='.$partie->idcontenu());
	         }
	         else
	         {
	         	 echo '<p>
                       Etes vous sur de vouloir supprimmer cette partie ?
                       <p><a href="editerpartie.php?action=sup&contenu='.$partie->idcontenu().'&partie='.$partie->id().'&sur=1">Oui</a></p>
                       <p><a href="editioncontenu.php?contenu='.$partie->idcontenu().'">Non</a></p>
	              </p>';
             }     


		
		break;

		default:
		      header('Location:./index.php');
}

