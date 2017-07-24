<?php
$titre="Contenus | SiteduSavoir.com";
include("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

?>

<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Contenus</a></li>
         </ul>
  </div>

 <div class="page">

<h2 class="titre" > Listes des Contenus(Blog /Tutos) </h2>

<form method="POST" action ="">
      <select name="type" id="">
      	<option value="tutoriel">Tutoriel</option>
      	<option value="article">Article</option>
      </select>
      <input type="submit" value="Envoyer">
</form>

<?php

$managerContenu = new ManagerContenu($bdd);
$managerAuteur = new ManagerAuteur($bdd);
$managerCategorie = new ManagerCategorie($bdd);
    
$type = (!empty($_POST['type']))?$_POST['type']:'tutoriel';
$page = (!empty($_GET['page']))?$_GET['page']:1;
$contenus_par_page = 20 ;

$nombres_contenus = $managerContenu->totalDeContenu($type);
$nbre_pages = ceil($nombres_contenus / $contenus_par_page);

?>


<p class="page">
<?php  paginationListe($page ,$nbre_pages, 'index.php'); ?>

</p>

<?php

$premiercontenu = ($page - 1) * $contenus_par_page ;
$infosContenus = $managerContenu->tousLesContenus($type ,$premiercontenu ,$contenus_par_page);


if(!empty($infosContenus))
{

	foreach ($infosContenus as $infosContenu) {

		$contenu = new Contenu($infosContenu);

		if($contenu->type() == 'tutoriel')
			$dossier = 'tutoriels/lire.php?tuto=';
		else
			$dossier = 'blog/lire.php?article=';
	 
	  echo 
	      '<div class="tutos">
	            <div class="banniere">
	                <img src="bannieres/'.$contenu->banniere().'" alt="banniere" style="width:300px; height: 225px ;"/>
	            </div>
	            <div class="tutos-infos">
	               <h3 class="titre-tuto"><a href="../'.$dossier.$contenu->id().'">'.htmlspecialchars($contenu->titre()).'</a></h3>';

	                   $infosAuteurs = $managerAuteur->tousLesAuteurs($contenu->id());

                        foreach ($infosAuteurs as $infosAuteur)
                        {
                        	$auteur = new Auteur($infosAuteur);
                            echo '<span class="auteur-tuto"><a href="../forum/voirprofil.php?action=consulter&m='.$auteur->membre().'">'.$auteur->pseudo().'</a></span>';
                        }

                        $donnneesCat = $managerCategorie->infosCategorie($contenu->cat());
                        $cat = new Categorie($donnneesCat);

	               echo '<span class="cat-tuto">'.$cat->nom().'</span>
	            </div>  


	       </div>';
    }
}
else
{
	
        echo '<p>  Il n y \' a aucun articles actuelement Sur le site
	           <p>';
}

?>

<p class="pagination">

<?php  paginationListe($page ,$nbre_pages, 'index.php') ; ?>

</p>

</div>

<?php include "../includes/footer.php"; ?>