<?php
session_start();
$titre ="Forum | Sitedusavoir.com";

include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

?>

<?php

echo '<p id="fildariane"><i> Vous etes ici : </i><a href="index.php">Forum </a></p>';
?>
    <h1> Forum </h1>

    <?php

    $totaldesmessages = 0;
    $categorie = NULL;
?>

   <?php


//Cette requête permet d'obtenir tout sur le forum

$query = $bdd->prepare('SELECT cat_id, cat_nom, forum.forum_id, forum_name, forum_desc, forum_post, forum_topic, auth_view, forum_topic.topic_id,  forum_topic.topic_post, post_id, DATE_FORMAT(post_time, \'%d/%m/%Y %H:%i:%s\') AS post_time  , post_createur, membre_pseudo, membre_id 

FROM categorie

LEFT JOIN forum ON categorie.cat_id = forum.forum_cat_id

LEFT JOIN forum_post ON forum_post.post_id = forum.forum_last_post_id

LEFT JOIN forum_topic ON forum_topic.topic_id = forum_post.topic_id

LEFT JOIN membres ON membres.membre_id = forum_post.post_createur

WHERE auth_view <= :lvl 

ORDER BY cat_ordre, forum_ordre DESC');

$query->bindValue(':lvl',$lvl,PDO::PARAM_INT);

$query->execute();

?>


<table>

<?php

//Début de la boucle

while($data = $query->fetch())

{

    //On affiche chaque catégorie

    if( $categorie != $data['cat_id'] )

    {

        //Si c'est une nouvelle catégorie on l'affiche

       

        $categorie = $data['cat_id'];



           ?> 

          </table>

           <table>
           <?php


        ?>

        <tr>

        <th></th>

        <th class="titre1"><strong><?php echo stripslashes(htmlspecialchars($data['cat_nom'])); ?>

        </strong></th>             

        <th class="nombremessages"><strong>Sujets</strong></th>       

        <th class="nombresujets"><strong>Messages</strong></th>       

        <th class="derniermessage"><strong>Dernier message</strong></th>   

        </tr>

        <?php
}
?>

<?php

    // Ce super echo de la mort affiche tous

    // les forums en détail : description, nombre de réponses etc...


    echo'<tr><td><img src="../images/message.gif" alt="message"/></td>

    <td id="taillepr" class="titre"><strong>

    <a href="./voirforum.php?f='.$data['forum_id'].'">

    '.stripslashes(htmlspecialchars($data['forum_name'])).'</a></strong>

    <br />'.nl2br(stripslashes(htmlspecialchars($data['forum_desc']))).'</td>

    <td class="nombresujets">'.$data['forum_topic'].'</td>

    <td class="nombremessages">'.$data['forum_post'].'</td>';


    // Deux cas possibles :

    // Soit il y a un nouveau message, soit le forum est vide

    if (!empty($data['forum_post']))

    {

         //Selection dernier message

     $nombreDeMessagesParPage = 15;

         $nbr_post = $data['topic_post'] +1;

     $page = ceil ($nbr_post / $nombreDeMessagesParPage);

         

         echo'<td class="derniermessage">

         '.$data['post_time'].'<br />

         <a href="./voirprofil.php?m='.stripslashes(htmlspecialchars($data['membre_id'])).'&amp;action=consulter">'.$data['membre_pseudo'].'</a>

   <a href="./voirtopic.php?t='.$data['topic_id'].'&amp;page='.$page.'#p_'.$data['post_id'].'">

         <img src="./images/go.gif" alt="go" /></a></td></tr>';


?>



<?php


     }

     else

     {

         echo'<td class="nombremessages">Pas de message</td></tr>';

     }


     

     $totaldesmessages += $data['forum_post'];

}

echo '</table></div>';

$query->CloseCursor();



?>


<?php

//Le pied de page ici :

echo'<div id="footer">

<h2 class="titre">

Qui est en ligne ?

</h2>

';


//On compte les membres

$TotalDesMembres = $bdd->query('SELECT COUNT(*) FROM membres')->fetchColumn();

$query->CloseCursor();  

$query = $bdd->query('SELECT membre_pseudo, membre_id FROM membres ORDER BY membre_id DESC LIMIT 0, 1');

$data = $query->fetch();

$derniermembre = stripslashes(htmlspecialchars($data['membre_pseudo']));


echo'<p>Le total des messages du forum est <strong>'.$totaldesmessages.'</strong>.<br />';

echo'Le site et le forum comptent <strong>'.$TotalDesMembres.'</strong> membres.<br />';

echo'Le dernier membre est <a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">'.$derniermembre.'</a>.</p>';

$query->CloseCursor();


//Initialisation de la variable
$count_online = 0;
//Décompte des visiteurs
$count_visiteurs=$bdd->query('SELECT COUNT(*) AS nbr_visiteurs FROM
forum_whosonline WHERE online_id = 0')->fetchColumn();
$query->CloseCursor();
//Décompte des membres
$texte_a_afficher = "<br />Liste des personnes en ligne : ";

$query=$bdd->prepare('SELECT membre_id, membre_pseudo
FROM forum_whosonline
LEFT JOIN membres ON online_id = membre_id
WHERE online_time > SUBDATE(NOW(), INTERVAL 5 MINUTE) AND online_id <> 0');
$query->execute();
$count_membres=0;
while ($data = $query->fetch())
{
$count_membres ++;
$texte_a_afficher .= '<a href="./voirprofil.php?
m='.$data['membre_id'].'&amp;action=consulter">
'.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a> ,';
}
$texte_a_afficher = substr($texte_a_afficher, 0, -1);
$count_online = $count_visiteurs + $count_membres;
echo '<p>Il y a '.$count_online.' connectés ('.$count_membres.'
membres et '.$count_visiteurs.' invités)';
echo $texte_a_afficher.'</p>';
$query->CloseCursor();


?>
</div>


</body>

</html>






