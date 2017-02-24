<?php

$titre="Lecture | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");





$art = (!empty($_GET['article']))?(int)$_GET['article']:1;

$req = $bdd->prepare('SELECT *
	                  FROM articles 
	                  WHERE articles_id = :article');

$req->execute(array('article' => $art));
$article = $req->fetch();



echo '<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Blog</a></li><img class="fleche" src="../images/icones/fleche.png" /> <li> <span style="color:black">'.$article['articles_titre'].' </span></li>
         </ul>
  </div>

  <div class="page">';


echo '<div class="liretuto">
           <section class="liretuto-debut">
                 <div class="logo">
                      <img src="./articles_ban/'.$article['articles_banniere'].'" alt=""/>
                 </div>
                  <div class="liretutoTitre">
                        '.htmlspecialchars($article['articles_titre']).'
                  </div>

                  <div class="liretutoAuteurs">
                     <ul>';

                     $auteur = $bdd->prepare('SELECT membre_pseudo , membres.membre_id ,membre_avatar
                                              FROM membres
                                              LEFT JOIN articles_par
                                              ON articles_par.membre_id = membres.membre_id
                                              WHERE articles_id = :article');

                    $auteur->bindParam(':article',$art, PDO::PARAM_INT);

                    $auteur->execute();

                    while($auteurs = $auteur->fetch())
                    {
                          echo '<li class="liretutoAut"><img src="../images/avatars/'.$auteurs['membre_avatar'].'"/><a href="../forum/voirprofil.php?m='.$auteurs['membre_id'].'&action=consulter">'.$auteurs['membre_pseudo'].'</a></li>';
                    }


                    echo '</ul>

                    </div>

           <section class="liretuto-intro">
                    '.$article['articles_intro'].'
           </section>';

   
$partie = $bdd->prepare('SELECT * 
	                     FROM articles_parties 
	                     WHERE articles_id = :article
	                     ORDER BY parties_id ');
$partie->bindParam(':article',$art,PDO::PARAM_INT);
$partie->execute();
$i = 1;
while($parties = $partie->fetch())
{
    echo '<section class="liretuto-partie">
                     <h3 class="liretuto-partie-titre">'.$i.' '.htmlspecialchars($parties['parties_titre']).'</h3>
                             <div class="contenu-part">
                                  '.$parties['parties_contenu'].'
                             </div>
     </section>';

     $i++;
}
         echo '<section class="liretuto-intro">
                    '.$article['articles_conc'].'
           </section>
      </div>
  </div>';

  include "../includes/footer.php";