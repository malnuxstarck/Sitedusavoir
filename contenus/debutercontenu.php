<?php

$titre="Nouveau Contenu | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

if(!$id)
    header('Location:../index.php');

$managerCategorie = new ManagerCategorie($bdd);
$categories = $managerCategorie->tousLesCategories();
$managerMembre = new ManagerMembre($bdd);

$infosMembre = $managerMembre->infosMembre($id);
$membre = new Membre($infosMembre);

if(empty($_POST))
{

?>



  <ul class="fildariane">
    <li><a href="../index.php">Accueil</a></li>
    <li><a href="../contenus/index.php">Contenus</a></li>
    <li><span>Commencer Contenu</span></li>
  </ul>
  <div class="page">

	<h1 class="titre"> Debuter un Contenu </h1>


	<div class="formulaire formulaire-tuto">

		   <form method="POST" action="debutercontenu.php" enctype="multipart/form-data">

			<div class="input input-tuto">
			      <label for="titre"></label>
			        <input type="text" name="titre" placeholder="Le titre du Contenu" required />
			</div>

			<div class="textarea textarea-tuto">
			      <textarea name="introduction" placeholder="Votre introduction" required ></textarea>
			</div>

			<div class="textarea textarea-tuto">
			      <textarea  name="conclusion" placeholder="Votre conclusion" required ></textarea>
			</div>

			<div class="input input-tuto">
			      <label for="banniere"></label>
			         <input type="file" name="banniere"/>
			</div>

			<div class="select">
			    <select name="cat">
			           <?php

			           foreach ($categories as $categorie) {

			           	   $cat = new categorie($categorie);
			                echo '<option value="'.$cat->id().'">'.$cat->nom().'</option>';
			           }

			           ?>
			    </select>
			</div>

			<div class="select">
			    <select name="type">
			              <option value="tutoriel">Tutoriel</option>

			              <?php if ($membre->verif_auth(Membre::MODO))
                                    echo '<option value="Article">article/Blog</option>';
			               ?>        
			    </select>
			</div>

			<div class="submit submit-tuto">
			     <input type="submit" value="Envoyer" />
			</div>

		</form>
   </div>
   </div>		

	<?php
}
 
else
{
    $managerContenu = new ManagerContenu($bdd);
    $contenu = new Contenu($_POST);
    $contenu->setBanniere($_FILES['banniere']);
    $managerContenu->verifierContenuChamps($contenu);
    
    $nomBanniere = $managerContenu->verifierBanniere($contenu);
    $contenu->setBanniere($nomBanniere);

    if($managerContenu->_iErrors == 0)
      {
	      	$contenu->setValidation('0');  // Pas encore valider par un admin , ou moderateur
	      	$contenu->setConfirmation('0'); // pas encore envoyer en validation , considerer comme brouillon
	      	$idContenu = (int) $managerContenu->ajouterNouveauContenu($contenu , $id);

      	    $_SESSION['flash']['success'] = " Votre Contenu a bien été creer , vous pouvez l'achever ici ";
      	    header('Location:./editioncontenu.php?contenu='.$idContenu);

      }
      else
      {
      	    $messageErreur = '';

      	    foreach ($managerContenu->errors() as $error){

      	    	$messageErreur.= $error;

      	    }

      	    $_SESSION['flash']['danger'] =  $messageErreur ;
      	    header('Location:./debutercontenu.php');
      }	

}







