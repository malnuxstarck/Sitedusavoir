<?php

$titre="Blog | SiteduSavoir.com";
include("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

?>

<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Blog</a></li>
         </ul>
  </div>

 <div class="page">

<h2 class="titre" > Listes des Articles </h2>

<?php

$managerContenu = new ManagerContenu($bdd);
$page = (!empty($_GET['page']))?$_GET['page']:1;

$articles_par_page = 20 ;

$nombres_articles = $managerContenu->totalDeContenu('article');

$nbre_pages = ceil($nombres_articles / $articles_par_page);

?>


<p class="page">
      <?php paginationListe($page ,$nbre_pages, 'index.php'); ?>
</p>

<?php

	if($id)
	   echo '<p class="nouveau-sujet"><img src="../images/icones/new.png"/><a href="../contenus/debutercontenu.php">Ecrire un article </a></p>';



$premierarticle = ($page - 1) * $articles_par_page ;

$infosArticles = $managerContenu->tousLesContenus('article',$premierarticle);

if(!empty($infosArticles))
{

	foreach ($infosArticles as $infosArticle)
	{
		$article = new Contenu($infosArticle);
		$managerCategorie = new ManagerCategorie($bdd);
		$infosCategorie = $managerCategorie->infosCategorie($article->cat());
		$categorie = new Categorie($infosCategorie);
	  
	    echo 
	      '<div class="tutos">
	            <div class="banniere">
	                <img style="width:300px; height: 225px ;" src="../contenus/bannieres/'.$article->banniere().'" alt="banniere"/>
	            </div>
	            <div class="tutos-infos">
	               <h3 class="titre-tuto"><a href="lire.php?article='.$article->id().'">'.htmlspecialchars($article->titre()).'</a></h3>';

	                    $managerAuteur = new ManagerAuteur($bdd);
	                    $infosAuteurs = $managerAuteur->tousLesAuteurs($article->id());

                        foreach ($infosAuteurs as $infosAuteur)
                        {
                        	$auteur = new Auteur($infosAuteur);

		                    echo '<span class="auteur-tuto"><a href="../forum/voirprofil.php?m='.$auteur->membre().'">'.$auteur->pseudo().'</a></span>';
                        }
	               echo '<span class="cat-tuto">'.$categorie->nom().'</span>
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

<?php   paginationListe($page ,$nbre_pages, 'index.php');  ?>

</p>

</div>

<?php include "../includes/footer.php"; ?>