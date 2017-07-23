<?php

$titre="Lecture | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");



$idArticle = (!empty($_GET['article']))?(int)$_GET['article']:1;

$managerContenu = new ManagerContenu($bdd);
$infosArticle = $managerContenu->donneLeContenu($idArticle);
$article = new Contenu($infosArticle);

echo '<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Tuto\'s</a></li><img class="fleche" src="../images/icones/fleche.png" /> <li> <span style="color:black">'.$article->titre().' </span></li>
         </ul>
  </div>

  <div class="page">';


echo '<div class="liretuto">
           <section class="liretuto-debut">
                 <div class="logo">
                      <img src="../contenus/bannieres/'.$article->banniere().'" alt=""/>
                 </div>
                  <div class="liretutoTitre">
                        '.htmlspecialchars($article->titre()).'
                  </div>

                  <div class="liretutoAuteurs">
                     <ul>';

                     $managerMembre = new ManagerMembre($bdd);
                     $managerAuteur = new ManagerAuteur($bdd);
                     $infosAuteurs = $managerAuteur->tousLesAuteurs($article->id());

                     foreach ($infosAuteurs as  $infoAteur) {

                          $infosMembre = $managerMembre->infosMembre($infoAteur['membre']);
                          $membre = new Membre($infosMembre);

                          echo '<li class="liretutoAut"><img src="../images/avatars/'.$membre->avatar().'"/><a href="../forum/voirprofil.php?m='.$membre->id().'&action=consulter">'.$membre->pseudo().'</a></li>';
                      }


                    echo '</ul>

                    </div>

           <section class="liretuto-intro">
                    '.$article->introduction().'
           </section>';

   
$managerPartie = new ManagerPartie($bdd);
$infosParties = $managerPartie->toutesLesPartiesDeCeContenu($article->id());

foreach ($infosParties as $infosPartie) {
    
    $partie = new Partie($infosPartie);

    echo '<section class="liretuto-partie">
                     <h3 class="liretuto-partie-titre">'.htmlspecialchars($partie->titre()).'</h3>
                             <div class="contenu-part">
                                  '.$partie->texte().'
                             </div>
     </section>';
}
         echo '<section class="liretuto-intro">
                    '.$article->conclusion().'
           </section>
      </div>
  </div>';

  include "../includes/footer.php";