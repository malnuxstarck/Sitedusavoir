<?php
$titre="Blog | SiteduSavoir.com";
include("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

?>

<p id="fildariane"> <i><a href="../index.php">Accueil </a> --> <a href="index.php">Blog</a></i></p>

<h2 class="titre" style="text-align:center"> Listes des Articles </h2>

<?php

$page = (!empty($_GET['page']))?$_GET['page']:1;
$articles_par_page = 20 ;
$req = $bdd->query('SELECT COUNT(*) AS nbr_articles FROM articles');
$nombres_articles = $req->fetch();
$nombres_articles = $nombres_articles["nbr_articles"];


$req->closeCursor();

$nbre_pages = ceil($nombres_articles / $articles_par_page);

?>


<p class="page">
<?php

for($i = 1 ; $i <= $nbre_pages ; $i++)
{
	if($i == $page)
	{
		echo '<strong>'.$i.'</strong>';
	}
	else{
		echo ' <a href="index.php?page='.$i.'</a> ';
	}

}
   
?>

</p>

<?php

if(verif_auth(MODO))
	echo '<p><a href="debuterarticle.php">Ecrire un article </a></p>';



$premierarticle = ($page - 1) * $articles_par_page ;

$req = $bdd->prepare('SELECT articles_titre,articles.articles_id, articles_banniere ,membre_pseudo,membres.membre_id,cat_nom 
	                  FROM articles
	                  LEFT JOIN articles_par
	                  ON articles.articles_id = articles_par.articles_id
	                  LEFT JOIN membres 
	                  ON membres.membre_id = articles_par.membre_id
	                  LEFT JOIN categorie
	                  ON categorie.cat_id = articles.articles_cat
	                  ORDER BY articles_date,articles_cat
	                  LIMIT :premier , :nombres');

$req->bindParam(':premier',$premierarticle, PDO::PARAM_INT);
$req->bindParam(':nombres',$articles_par_page,PDO::PARAM_INT);

$req->execute();

if($req->rowCount() > 0)
{

	while($article = $req->fetch())
	{
	  
	  echo 
	      '<div class="tutos">
	            <div class="banniere">
	                <img src="articles_ban/'.$article['articles_banniere'].'" alt="banniere"/>
	            </div>
	            <div class="tutos_infos">
	               <h3 class="tuto_titre" style="color:#2b8bad;"><a href="lirearticle.php?&article='.$article['articles_id'].'">'.htmlspecialchars($article['articles_titre']).'</a></h3>
	               <span> Par <a href="../forum/voirprofil.php?action=consulter&m='.$article['membre_id'].'">'.$article['membre_pseudo'].'</a></span><span>'.$article['cat_nom'].'</span>
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

<p class="page">

<?php

for($i = 1 ; $i <= $nbre_pages ; $i++)
{
	if($i == $page)
	{
		echo '<strong>'.$i.'</strong>';
	}
	else{
		echo ' <a href="index.php?page='.$i.'</a> ';
	}

}
   
?>

</p>