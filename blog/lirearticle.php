<?php

$titre="Lecture | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");
?>
 <p id="fildariane"><i><a href="../index.php">Accueil</a> --> <a href="./index.php">Blog</a>--> Lire un Article </i></p>
<?php

$art = (!empty($_GET['article']))?(int)$_GET['article']:1;

$req = $bdd->prepare('SELECT *
	                  FROM articles 
	                  WHERE articles_id = :article');

$req->execute(array('article' => $art));
$article = $req->fetch();


echo '<div class="tuto">
           <section class="tuto-t">
                 <div class="icon">
                      <img src="./articles_ban/'.$article['articles_banniere'].'" alt=""/>
                 </div>
                <h2 class="titre_tuto">'.htmlspecialchars($article['articles_titre']).'</h2>
           </section>
           <section class="intro-t">
                    '.$article['articles_intro'].'
           </section>';

   
$partie = $bdd->prepare('SELECT * 
	                     FROM articles_parties 
	                     WHERE articles_id = :article
	                     ORDER BY parties_id ');
$partie->bindParam(':article',$art,PDO::PARAM_INT);
$partie->execute();

while($parties = $partie->fetch())
{
    echo '<section class="corps-t">
                     <h3 class="titre-sec">'.htmlspecialchars($parties['parties_titre']).'</h3>
                     <div class="contenu-part">
                          '.$parties['parties_contenu'].'
                     </div>
     </section>';
}
         echo '<section class="intro-t">
                    '.$article['articles_conc'].'
           </section>';

 $auteur = $bdd->prepare('SELECT membre_pseudo , membres.membre_id 
                          FROM membres
                          LEFT JOIN articles_par
                          ON articles_par.membre_id = membres.membre_id
                          WHERE articles_id = :article');

$auteur->bindParam(':article',$art, PDO::PARAM_INT);

$auteur->execute();
echo'<ul class="auteur"> Par : ';

while($auteurs = $auteur->fetch())
{
      echo '<li><a href="../forum/voirprofil.php?m='.$auteurs['membre_id'].'&action=consulter">'.$auteurs['membre_pseudo'].'</a></li>';
}

echo '</ul></div>';