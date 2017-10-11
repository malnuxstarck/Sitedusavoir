<?php

$titre="Tutos | SiteduSavoir.com";
include("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

?>

<ul class="fildariane">
  <li><a href="../index.php">Accueil</a></li>
  <li><a href="./index.php">Tuto's</a></li>
</ul>

 <div class="page">

<h2 class="titre" > Listes des Tutos </h2>

<?php

$managerContenu = new ManagerContenu($bdd);
$page = (!empty($_GET['page']))?$_GET['page']:1;

$tutos_par_page = 20;

$nombres_tutos = $managerContenu->totalDeContenu('tutoriel');

$nbre_pages = ceil($nombres_tutos / $tutos_par_page);

?>


<p class="pagination">
<?php  paginationListe($page ,$nbre_pages, 'index.php');  
?>

</p>

<?php

	if($id)
	   echo '<p class="nouveau-sujet"><img src="../images/icones/new.png"/><a href="../contenus/debutercontenu.php">Ecrire un Tuto </a></p>';



$premiertuto = ($page - 1) * $tutos_par_page ;

$infosTutos = $managerContenu->tousLesContenus('tutoriel',$premiertuto);

if(!empty($infosTutos))
{

	foreach ($infosTutos as $infosTuto)
	{
		$tuto = new Contenu($infosTuto);
		$managerCategorie = new ManagerCategorie($bdd);
		$infosCategorie = $managerCategorie->infosCategorie($tuto->cat());
		$categorie = new Categorie($infosCategorie);
	  
	    echo 
	      '<div class="tutos">
	            <div class="banniere">
	                <img style="width:300px; height: 225px ;" src="../contenus/bannieres/'.$tuto->banniere().'" alt="banniere"/>
	            </div>
	            <div class="tutos-infos">
	               <h3 class="titre-tuto"><a href="lire.php?tuto='.$tuto->id().'">'.htmlspecialchars($tuto->titre()).'</a></h3>';

	                    $managerAuteur = new ManagerAuteur($bdd);
	                    $infosAuteurs = $managerAuteur->tousLesAuteurs($tuto->id());

                        foreach ($infosAuteurs as $infosAuteur)
                        {
                        	$auteur = new Auteur($infosAuteur);

		                    echo '<span class="auteur-tuto"><a href="../forum/voirprofil.php?m='.$auteur->membre().'" >'.$auteur->pseudo().'</a></span>';
                        }
	               echo '<span class="cat-tuto">'.$categorie->nom().'</span>
	            </div>  


	       </div>';
    }
}
else
{
	
        echo '<p>  Il n y \' a aucun tutoriels actuelement Sur le site
	           <p>';
}

?>

<p class="pagination">

<?php
      paginationListe($page ,$nbre_pages, 'index.php');
?>

</p>

</div>

<?php include "../includes/footer.php"; ?>