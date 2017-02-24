<?php

$count_online = 0;
//Décompte des visiteurs
$count_visiteurs=$bdd->query('SELECT COUNT(*) AS nbr_visiteurs
                              FROM forum_whosonline 
                              WHERE online_id = 0')->fetchColumn();


//Décompte des membres

$texte_a_afficher = "<br />Liste des personnes en ligne : ";

$query = $bdd->prepare('SELECT membre_id, membre_pseudo
                        FROM forum_whosonline
                        LEFT JOIN membres 
                        ON online_id = membre_id
                        WHERE online_time > SUBDATE(NOW(), INTERVAL 5 MINUTE) 
                        AND online_id <> 0');
$query->execute();
$count_membres=0;

while ($data = $query->fetch())
{
    $count_membres ++;

    $texte_a_afficher .= '<a href="../forum/voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">'.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a> ,';
}


$texte_a_afficher = substr($texte_a_afficher, 0, -1);
$count_online = $count_visiteurs + $count_membres;
?>

<footer>
     <div class="whoisonline">
           <h2 class="quies"> Qui est en ligne ? </h2>
           <p class="text">
               Il y'a actuelment <?php echo $count_online.' connectés , dont '. $count_membres. ' membres et '. $count_visiteurs .' invités';
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
                <li> <a href="#"> Facebook </a></li>
                <li> <a href="#"> Twitter </a></li>
                <li> <a href="#"> Github </a></li>
                <li> <a href="#"> Siteperso </a></li>
            </ul>
       </div>
       <p class="copyright">
           Site du Savoir Tous droits reservés &copy copyright 2017  
       </p>

</footer>