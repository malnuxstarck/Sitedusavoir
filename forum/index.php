<?php
session_start();
$titre ="Forum";

include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

?>

 
<?php

echo '<section id="fildariane"><i> Vous etes ici : </i><a href="index.php">Forum </a></section>';
?>
    <h1 class="titre"> Forum Site du savoir </h1>


    <?php

    $totaldesmessages = 0;
    $categorie = NULL;
?>

   <?php


//Cette requête permet d'obtenir tout sur le forum

$query = $bdd->prepare('SELECT cat_id, cat_nom, forum.forum_id, forum_name, forum_desc, forum_post, forum_topic, auth_view, forum_topic.topic_id,  forum_topic.topic_post, post_id, post_time, post_createur, membre_pseudo, membre_id 

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

           ?> 

             </table>
             <table>
           <?php


            $categorie = $data['cat_id'];

            ?>

            <tr>

            <th></th>

            <th class="titre"><strong><?php echo stripslashes(htmlspecialchars($data['cat_nom'])); ?>

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


        echo'<tr><td><img src="./images/message.gif" alt="message"/></td>

        <td class="titre"><strong>

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

echo '</table>';

$query->CloseCursor();



?>


<?php

//Le pied de page ici :

echo'

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


echo'<footer><p>Le total des messages du forum est <strong>'.$totaldesmessages.'</strong>.<br />';

echo'Le site et le forum comptent <strong>'.$TotalDesMembres.'</strong> membres.<br />';

echo'Le dernier membre est <a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">'.$derniermembre.'</a>.</p> </footer>';

$query->CloseCursor();

?>
</div>

</body>

</html>






