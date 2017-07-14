
<?php

$titre="Edition Contenu | SiteduSavoir.com";

include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

$idContenu =(isset($_GET['contenu']))?$_GET['contenu']:"";
$managerContenu = new ManagerContenu($bdd);
$donnees = $managerContenu->donneLeContenu($idContenu , $id);
$contenu = new Contenu($donnees);

?>

<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Contenus</a></li><img class="fleche" src="../images/icones/fleche.png"/><li> <span style="color:black;">Edition Contenu</span> </li>
         </ul>
  </div>

  <div class="page">

 <?php 
 
 if(empty($_POST))
 {

?>

<h1 class="titre"> Editer un Contenu </h1>
	<div class="formulaire formulaire-edition">
	    <form action="editioncontenu.php?contenu=<?php echo $contenu->id();?>" method="POST">

        <?php
	     echo '<div class="input">
	          <label for="titre"></label>
	          <input title="Votre titre" type="text"  name="titre" value="'.trim($contenu->titre()).'" required />
	         </div>
	         <div class="textarea">
	              <textarea name="introduction" title="Votre introduction" required>'.trim($contenu->introduction()).'</textarea>
	         </div>';

	         /* recuperer les restes des parties */
	    $managerPartie = new ManagerPartie($bdd);
	    $donnees = $managerPartie->toutesLesPartiesdeCeContenu($contenu->id());     

	    foreach ($donnees as $donnee) {

	    	$partie = new Partie($donnee);

	     	echo '<div class="partie">
	     	               
	     	                    <h3 class="titre titre-partie">'.$partie->titre().'</h3>

	     	                    <div class="partie-text">
	     	                    <ul>
	     	                        <li> <a href="editerpartie.php?partie='.$partie->id().'&action=edit&contenu='.$contenu->id().'"><span>Editer</span><img src="../images/icones/edit.png"/></a> </li>
	     	                        <li> <a href="editerpartie.php?partie='.$partie->id().'&action=sup&contenu='.$contenu->id().'"> <span>Delete</span><img src="../images/icones/edit.png"/></a> </li>
	     	                    </ul>
	     	                    <p>'.htmlspecialchars($partie->texte()).'</p>
	     	               </div>
	     
	     	      </div>';
	     }

	     echo '<div class="textarea">
	              <textarea name="conclusion" title="Votre conclusion" required>'.htmlspecialchars($contenu->conclusion()).'</textarea>
	         </div>';

	     ?>

	     <div class="select">
		    <select name="cat">
		           <?php
		               $managerCategorie = new ManagerCategorie($bdd);
		               $donnees = $managerCategorie->tousLesCategories();

		           foreach ($donnees as $donnee) {

		           	    $categorie = new Categorie($donnee);

		           	    if($contenu->cat() == $categorie->id())
                            echo '<option value="'.trim($categorie->id()).'" selected="selected" >'.$categorie->nom().'</option>';
                        else
                            echo '<option value="'.$categorie->id().'">'.$categorie->nom().'</option>';
		           }

		           ?>
		    </select>
	    </div>

	        <div class="submit submit-tuto">
		       <input name="brouillon" type="submit" value="Brouillon" />
		   </div>

		    <div class="submit submit-tuto">
		       <input name="validation" type="submit" value="validation" />
		   </div>

    </form>
</div>

<div class="modification">
<span class="modification-entete">Modification </span>
    <ul>
      <?php

        echo '<li><a href="ajouter.php?contenu='.$contenu->id().'&action=partie">Ajouter une partie </a></li>
        <li><a href="ajouter.php?contenu='.$contenu->id().'&action=auteur">Ajouter un auteur </a></li>';

        ?>
    </ul>
</div>

<?php

}
else
{
   
    $idContenu = (isset($_GET['contenu']))?(int)$_GET['contenu']:"";
    $contenu = new Contenu($_POST);
    $contenu->setId($idContenu);

  
    if (isset($_POST['validation']))
   	   $contenu->setValidation('1');
    else
    	$contenu->setValidation('0');

    $managerContenu = new ManagerContenu($bdd);
    $managerContenu->verifierContenuChamps($contenu);

    if($managerContenu->_iErrors == 0)
        $managerContenu->miseAjourContenu($contenu);
    else{

	    	$message ='';
	    	foreach ($contenu->errors() as $error) {
	    		$message.=$error.'</br>';
	    	}

	        $_SESSION['flash']['danger'] = $message ;
	        header('Location:./editioncontenu.php?contenu='.$contenu->id());
        }
} 

