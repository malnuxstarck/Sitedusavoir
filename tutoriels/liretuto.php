<?php

$titre="Lecture | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

$tuto_id = (!empty($_GET['tuto']))?(int)$_GET['tuto']:1;

$req = $bdd->prepare('SELECT *
                    FROM tutos 
                    WHERE tutos_id = :tuto');

$req->execute(array('tuto' => $tuto_id));
$tuto = $req->fetch();




echo '<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Tutoriels</a></li><img class="fleche" src="../images/icones/fleche.png" /> <li> <span style="color:black">'.$tuto['tutos_titre'].' </span></li>
         </ul>
  </div>

  <div class="page">';



echo '<div class="liretuto">
           <section class="liretuto-debut">
           
                 <div class="logo">
                      <img src="./tutos_ban/'.$tuto['tutos_banniere'].'" alt=""/>
                 </div>

                  <div class="liretutoTitre"><a href="./liretuto.php?tuto='.$tuto['tutos_id'].'">
                     '.htmlspecialchars($tuto['tutos_titre']).'</a>
                 </div>

                 <div class="liretutoAuteurs">
                          Par : 
                       <ul>';
                          

                             $auteur = $bdd->prepare('SELECT membre_pseudo , membres.membre_id ,membre_avatar
                                                      FROM membres
                                                      LEFT JOIN tutos_par
                                                      ON tutos_par.membre_id = membres.membre_id
                                                      WHERE tutos_id = :tuto');

                            $auteur->bindParam(':tuto',$tuto_id, PDO::PARAM_INT);
                            $auteur->execute();

                            while($auteurs = $auteur->fetch())
                            {
                                  echo '<li class="liretutoAut"><img src="../images/avatars/'.$auteurs['membre_avatar'].'"/><a href="../forum/voirprofil.php?m='.$auteurs['membre_id'].'&action=consulter">'.$auteurs['membre_pseudo'].'</a></li>';
                            }

                            echo '</ul>
                   </div>

                
          </section>

            

           <section class="lireuto-intro">
                    '.$tuto['tutos_intro'].'
           </section>';
                   
        $partie = $bdd->prepare('SELECT * 
        	                     FROM tutos_parties 
        	                     WHERE tutos_id = :tuto
        	                     ORDER BY parties_id ');
        $partie->bindParam(':tuto',$tuto_id,PDO::PARAM_INT);
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
                            '.$tuto['tutos_conc'].'
                   </section>

       </div>
</div>';

include "../includes/footer.php";