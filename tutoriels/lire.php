<?php

$titre="Lecture | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");



$idTuto = (!empty($_GET['tuto']))?(int)$_GET['tuto']:1;

$managerContenu = new ManagerContenu($bdd);
$infosTuto = $managerContenu->donneLeContenu($idTuto);

if(empty($infosTuto))
{
    $_SESSION['flash']['success'] = "Le tuto n'existe pas ";
    header('Location:./index.php');
    exit();
}

$tuto = new Contenu($infosTuto);

echo '<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Tuto\'s</a></li><img class="fleche" src="../images/icones/fleche.png" /> <li> <span style="color:black">'.$tuto->titre().' </span></li>
         </ul>
  </div>

  <div class="page">';


echo '<div class="liretuto">
           <section class="liretuto-debut">
                 <div class="logo">
                      <img src="../contenus/bannieres/'.$tuto->banniere().'" alt=""/>
                 </div>
                  <div class="liretutoTitre">
                        '.htmlspecialchars($tuto->titre()).'
                  </div>

                  <div class="liretutoAuteurs">
                     <ul>';

                     $managerMembre = new ManagerMembre($bdd);
                     $managerAuteur = new ManagerAuteur($bdd);
                     $infosAuteurs = $managerAuteur->tousLesAuteurs($tuto->id());

                     foreach ($infosAuteurs as  $infoAteur) {

                          $infosMembre = $managerMembre->infosMembre($infoAteur['membre']);
                          $membre = new Membre($infosMembre);

                          echo '<li class="liretutoAut"><img src="../images/avatars/'.$membre->avatar().'"/><a href="../forum/voirprofil.php?m='.$membre->id().'&action=consulter">'.$membre->pseudo().'</a></li>';
                      }


                    echo '</ul>

                    </div>

           <section class="liretuto-intro">
                    '.$tuto->introduction().'
           </section>';

   
$managerPartie = new ManagerPartie($bdd);
$infosParties = $managerPartie->toutesLesPartiesDeCeContenu($tuto->id());

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
                    '.$tuto->conclusion().'
           </section>
      </div>
  </div>';

  include "../includes/footer.php";