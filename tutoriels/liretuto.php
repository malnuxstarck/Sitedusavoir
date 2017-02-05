<?php

$titre="Lecture | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");
?>
 <p id="fildariane"><i><a href="../index.php">Accueil</a> --> <a href="./index.php">Tutoriels</a>--> Lire un tuto </i></p>
<?php

$tuto_id = (!empty($_GET['tuto']))?(int)$_GET['tuto']:1;

$req = $bdd->prepare('SELECT *
	                  FROM tutos 
	                  WHERE tutos_id = :tuto');

$req->execute(array('tuto' => $tuto_id));
$tuto = $req->fetch();


echo '<div class="tuto">
           <section class="tuto-t">
                 <div class="icon">
                      <img src="./tutos_ban/'.$tuto['tutos_banniere'].'" alt=""/>
                 </div>
                <h2 class="titre_tuto">'.htmlspecialchars($tuto['tutos_titre']).'</h2>
           </section>
           <section class="intro-t">
                    '.$tuto['tutos_intro'].'
           </section>';

   
$partie = $bdd->prepare('SELECT * 
	                     FROM tutos_parties 
	                     WHERE tutos_id = :tuto
	                     ORDER BY parties_id ');
$partie->bindParam(':tuto',$tuto_id,PDO::PARAM_INT);
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
                    '.$tuto['tutos_conc'].'
           </section>';

 $auteur = $bdd->prepare('SELECT membre_pseudo , membres.membre_id 
                          FROM membres
                          LEFT JOIN tutos_par
                          ON tutos_par.membre_id = membres.membre_id
                          WHERE tutos_id = :tuto');

$auteur->bindParam(':tuto',$tuto_id, PDO::PARAM_INT);

$auteur->execute();
echo'<ul class="auteur"> Par : ';

while($auteurs = $auteur->fetch())
{
      echo '<li><a href="../forum/voirprofil.php?m='.$auteurs['membre_id'].'&action=consulter">'.$auteurs['membre_pseudo'].'</a></li>';
}

echo '</ul></div>';