<?php
$titre = "Forums | SiteduSavoir.com"; 
require "../includes/session.php";
require "../../modele/includes/identifiants.php";
require "../../modele/includes/fonctions.php";
require "../includes/debut.php";
include("../includes/constantes.php");
include ("../../modele/includes/debut.php");
$balises=(isset($balises))?$balises:0;
if($balises)
{
    include('../../vue/includes/debut.php');
}
require "../../vue/includes/menu.php";
require "../includes/menu.php";
require "../includes/fonctions.php";


 echo '<p id="fildariane"><i> Vous etes ici : </i><a href="index.php">Forum </a></p>';
?>
    
    <h1> Forum </h1>

<?php

 $totaldesmessages = 0;
$categorie = NULL;

$add1='';
$add2 ='';
require "../../modele/forum/index.php";
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
        require "../../vue/forum/index.php";
    }

?>

<?php

    

    // les forums en détail : description, nombre de réponses etc...


     if (!empty($id)) // Si le membre est connecté
     {
        if ($data['tv_id'] == $id) //S'il a lu le topic
        {
                if ($data['tv_poste'] == '0') // S'il n'a pas posté
                {
                        if ($data['tv_post_id'] == $data['forum_last_post_id'])
                        //S'il n'y a pas de nouveau message
                        {
                             $ico_mess = 'message.png';
                        }

                        else
                        {
                            $ico_mess = 'messagec_non_lus.png'; //S'il y a un nouveau message
                        }
                }

                else // S'il a posté
                {
                        if ($data['tv_post_id'] == $data['forum_last_post_id'])
                        //S'il n'y a pas de nouveau message
                        {
                             $ico_mess = 'messagep_lu.png';
                        }
                        else //S'il y a un nouveau message
                        {
                              $ico_mess = 'messagep_non_lu.png';
                        }
                }
        }


        else //S'il n'a pas lu le topic
        {
              $ico_mess = 'message_non_lu.png';

        }
}
 //S'il n'est pas connecté
else
{
    $ico_mess = 'message.png';
}

    echo'
        <tr>
            <td>
                <img src="../images/'.$ico_mess.'" alt="message"/>
            </td>

        <td id="taillepr" class="titre">
            <strong>
                 <a href="./voirforum.php?f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
            </strong>

            <br />'.nl2br(stripslashes(htmlspecialchars($data['forum_desc']))).'
        </td>

        <td class="nombresujets">
             '.$data['forum_topic'].'
        </td>

        <td class="nombremessages">
              '.$data['forum_post'].'
        </td>';


    // Deux cas possibles :

    // Soit il y a un nouveau message, soit le forum est vide

    if (!empty($data['forum_post']))

    {

         //Selection dernier message

        $nombreDeMessagesParPage = 15;

        $nbr_post = $data['topic_post'] + 1;

        $page = ceil ($nbr_post / $nombreDeMessagesParPage);

         

                 echo'<td class="derniermessage">

                         '.$data['post_time'].'<br />

                          <a href="./voirprofil.php?m='.stripslashes(htmlspecialchars($data['membre_id'])).'&amp;action=consulter">'.$data['membre_pseudo'].'</a>

                          <a href="./voirtopic.php?t='.$data['topic_id'].'&amp;page='.$page.'#p_'.$data['post_id'].'">

                         <img src="./images/go.gif" alt="go" /></a>
                        </td>
              </tr>';


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

$TotalDesMembres = totalDesMembres($bdd);

$query->CloseCursor();  

$data = dernierMembre($bdd);

$derniermembre = stripslashes(htmlspecialchars($data['membre_pseudo']));


echo'<p>Le total des messages du forum est <strong>'.$totaldesmessages.'</strong>.<br />';

echo'Le site et le forum comptent <strong>'.$TotalDesMembres.'</strong> membres.<br />';

echo'Le dernier membre est <a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">'.$derniermembre.'</a>.</p>';

$query->CloseCursor();


//Initialisation de la variable

$count_online = 0;
//Décompte des visiteurs
$count_visiteurs= visiteursEnLigne($bdd);
$query->CloseCursor();

//Décompte des membres

$texte_a_afficher = "<br />Liste des personnes en ligne : ";

$query = whoIsOnlineInfos($bdd);

$count_membres=0;

while ($data = $query->fetch())
{
    $count_membres ++;

    $texte_a_afficher .= '<a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">'.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a> ,';
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