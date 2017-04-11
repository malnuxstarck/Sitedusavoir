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
	echo '<p class="nouveau-sujet"><img src="../images/icones/new.png"/><a href="debuterarticle.php">Ecrire un article </a></p>';



$premierarticle = ($page - 1) * $articles_par_page ;

$req = $bdd->prepare('SELECT articles_titre,articles.articles_id, articles_banniere ,cat_nom 
	                  FROM articles
	                  LEFT JOIN categorie
	                  ON categorie.cat_id = articles.articles_cat
	                  ORDER BY articles_date
	                  LIMIT :premier , :nombresparpages');

$req->bindParam(':premier',$premierarticle, PDO::PARAM_INT);
$req->bindParam(':nombresparpages',$articles_par_page,PDO::PARAM_INT);

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
	            <div class="tutos-infos">
	               <h3 class="titre-tuto"><a href="lirearticle.php?&article='.$article['articles_id'].'">'.htmlspecialchars($article['articles_titre']).'</a></h3>';

	               $auteurs = $bdd->prepare('SELECT membre_pseudo , membres.membre_id 
                        	                      FROM articles_par JOIN membres 
                        	                      ON membres.membre_id = articles_par.membre_id WHERE articles_id = :article');

                        $auteurs->execute(array('article' => $article['articles_id']));

                        while($auteur = $auteurs->fetch())
                        {
		                 echo '<span class="auteur-tuto"><a href="../forum/voirprofil.php?action=consulter&m='.$auteur['membre_id'].'">'.$auteur['membre_pseudo'].'</a></span>';
                        }
	               echo '<span class="cat-tuto">'.$article['cat_nom'].'</span>
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

<?php

for($i = 1 ; $i <= $nbre_pages ; $i++)
{
	if($i == $page)
	{
		echo '<strong>'.$i.'</strong>';
	}
	else{
		echo ' <a href="index.php?page='.$i.'">'.$i.'</a> ';
	}

}
   
?>

</p>

</div>

<?php include "../includes/footer.php"; ?>