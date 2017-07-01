<?php

$count_online = 0;

//Décompte des visiteurs

$managerWhoIsOnline =  new ManagerWhoIsOnline($bdd);
$count_visiteurs    =  $managerWhoIsOnline->nombresDeVisiteursEnLigne();

//Décompte des membres

$texte_a_afficher = "<br />Liste des personnes en ligne : ";

$membresEnLigneEtMessage = $managerWhoIsOnline->nombresDeMembresEnLigne();
$count_membres= $membresEnLigneEtMessage["nombre"] ;

$count_online = $count_visiteurs + $count_membres;
$texte_a_afficher.= $membresEnLigneEtMessage["texte_a_afficher"];

?>

<footer>
     <div class="whoisonline">
           <h2 class="quies"> Qui est en ligne ? </h2>
           <p class="text">
               Il y'a actuelment <?php echo $count_online.' connectés , dont '.$count_membres.' membres et '. $count_visiteurs .' invités';
               echo $texte_a_afficher ; ?> 
              </p>
       </div>
       <div class ="sitedusavoir">
             <h2 class="nomsite"> Site du Savoir </h2>
             <p>
                 Site du Savoir est un projet opensource c'est a dire libre , 
                 tout le monde peut y participer. Il consiste a reunir les 
                 pogrammers du monde et a s'entraider entre eux via le site
             </p>
       </div>
       <div class="apropos">
            <h2 class="nom-apropos"> Me contacter </h2>
            <ul class="apropos-content">
                <li> <a href="https://www.facebook.com/abdoulmalikhamidou"> Facebook </a></li>
                <li> <a href="https://twitter.com/AbdoulMalikH"> Twitter </a></li>
                <li> <a href="https://github.com/malnuxstarck"> Github </a></li>
                <li> <a href="http://malnuxstarck.alwaysdata.net"> Siteperso </a></li>
            </ul>
       </div>
       <p class="copyright">
           Site du Savoir Tous droits reservés &copy copyright <?php $annee = date('Y'); echo $annee ; ?>  
       </p>

</footer>