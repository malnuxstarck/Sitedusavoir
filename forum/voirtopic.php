<?php


include "../includes/session.php";
include("../includes/identifiants.php");
include("../includes/bbcode.php"); 

//On verra plus tard ce qu'est cefichier
//On récupère la valeur de t

if(isset($_GET['t']))
$topic = (int)$_GET['t'];
else
  $topic = 1 ;

//A partir d'ici, on va compter le nombre de messages pourn'afficher que les 15 premiers

$query = $bdd->prepare('SELECT topic_titre, topic_post, forum_topic.forum_id , topic_last_post,forum_name, auth_view, auth_topic, auth_post,auth_modo
                        FROM forum_topic
                        LEFT JOIN forum 
                        ON forum_topic.forum_id = forum.forum_id 
                        WHERE topic_id = :topic');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->execute();

$data = $query->fetch();

$titre = $data['topic_titre'];


include("../includes/debut.php");
include("../includes/menu.php");


if (!verif_auth($data['auth_view']))
{
  erreur(ERR_AUTH_VIEW);
}


$forum = $data['forum_id'];
$totalDesMessages = $data['topic_post'] + 1 ;
$nombreDeMessagesParPage = 15;
$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);




if($id!=0)
{
    $query=$bdd->prepare('SELECT COUNT(*) 
                          FROM forum_topic_view 
                          WHERE tv_topic_id = :topic 
                          AND tv_id = :id');

    $query->bindValue(':topic',$topic,PDO::PARAM_INT);
    $query->bindValue(':id',$id,PDO::PARAM_INT);
    $query->execute();
    $nbr_vu=$query->fetchColumn();
    $query->CloseCursor();

    if ($nbr_vu == 0) //Si c'est la première fois on insère une ligne entière
    {

        $query = $bdd->prepare('INSERT INTO forum_topic_view(tv_id, tv_topic_id, tv_forum_id, tv_post_id)
                                VALUES (:id, :topic, :forum, :last_post)');

        $query->bindValue(':id',$id,PDO::PARAM_INT);
        $query->bindValue(':topic',$topic,PDO::PARAM_INT);
        $query->bindValue(':forum',$forum,PDO::PARAM_INT);
        $query->bindValue(':last_post',$data['topic_last_post'],PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();

    }
    else 
    //Sinon, on met simplement à jour
    {
        $query= $bdd->prepare('UPDATE forum_topic_view 
                               SET tv_post_id =:last_post 
                               WHERE tv_topic_id = :topic 
                               AND tv_id = :id');

        $query->bindValue(':last_post',$data['topic_last_post'],PDO::PARAM_INT);
        $query->bindValue(':topic',$topic,PDO::PARAM_INT);
        $query->bindValue(':id',$id,PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();

    }

}

echo '<div class="fildariane">

         <ul>
         <li>
              <a href="../index.php"> Accueil </a>
        </li> 
              <img class="fleche" src="../images/icones/fleche.png"/>
            <li>
                <a href="./index.php">Forum</a>
            </li>

            <img class="fleche" src="../images/icones/fleche.png"/>

           <li>
               <a href="./voirforum.php?f='.$forum.'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
           </li>  <img class="fleche" src="../images/icones/fleche.png"/>   
           <li>
            <a href="./voirtopic.php?t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
            </li>
         </ul>
     <div>

 <div class="page">';

      echo '<h1 class="titre">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</h1><br/><br />';

//Nombre de pages
  $page = (isset($_GET['page']))?intval($_GET['page']):1;

//On affiche les pages 1-2-3 etc...

echo '<p class="pagination">';

for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
    if ($i == $page) //On affiche pas la page actuelle en lien
    {
       echo '<strong>'.$i.'</strong>';
    }
    else
    {
      echo '<a href="voirtopic.php?t='.$topic.'&page='.$i.'">'. $i . '</a> ';
    }

}
echo'</p>';


$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

//On affiche l'image répondre

if (verif_auth($data['auth_post']))
{
//On affiche l'image répondre
  echo'<p class="nouveau-sujet" title="Répondre à ce topic">
   <img src="../images/icones/mail.png"/><a href="./poster.php?action=repondre&amp;t='.$topic.'">Repondre</a>
   </p>';
}
 
 
if (verif_auth($data['auth_topic']))
{
//On affiche l'image nouveau topic
echo'<p class="nouveau-sujet" title="Poster un nouveau topic">
        <img src="../images/icones/new.png"/><a href="./poster.php?action=nouveautopic&amp;f='.$forum.'">Nouveau Sujet</a>
     </p>';
}

$query->CloseCursor();
//Enfin on commence la boucle !


$query = $bdd->prepare('SELECT post_id , post_createur ,membre_rang, post_texte ,DATE_FORMAT(post_time ,\'%d/%m/%Y à %H:%i:%s\') AS post_time,membre_id, membre_pseudo, DATE_FORMAT(membre_inscrit, \'%d/%m/%Y à %H:%i:%s\') 
                        AS membre_inscrit, membre_avatar,membre_localisation, membre_post, membre_signature
                       FROM forum_post
                       LEFT JOIN membres ON membres.membre_id = forum_post.post_createur
                       WHERE topic_id =:topic
                       ORDER BY post_id
                       LIMIT :premier, :nombre');

$query->bindValue(':topic',$topic,PDO::PARAM_INT);

$query->bindValue(':premier',(int)$premierMessageAafficher,PDO::PARAM_INT);

$query->bindValue(':nombre',(int)$nombreDeMessagesParPage,PDO::PARAM_INT);

$query->execute();

//On vérifie que la requête a bien retourné des messages


echo '<div class="touslesmessages">';

if ($query->rowCount()< 1 )
{
    echo'<p>Il n y a aucun post sur ce topic, vérifiez l url et
reessayez</p>';
}

else
{


    while ($data = $query->fetch())
    {


      //On commence à afficher le pseudo du créateur du message :
      //On vérifie les droits du membre
      

      echo'<div class="lesujet">

                <div class="membresujet">

                    <img src="../images/avatars/'.$data['membre_avatar'].'" alt="" />';

                    if($data['membre_rang']== 3)
                          echo'<span class="badge badgef"> Moderateur </span>';
                    else if($data['membre_rang'] == 4)
                          echo '<span class="badge badgef">Admin </span>';
              echo '</div>

            <div class="contenu-message">

               <p class="top-messagef">
                  <a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">
                    '.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a> <span>  le '.$data['post_time'].'</span>
               </p>';

                    /* Si on est l'auteur du message, on affiche des liens pour
                    Modérer celui-ci.
                    */
                    
                    if ($id == $data['post_createur'] OR verif_auth(MODO))
                    {

                    echo'<div class="lienmod">

                             <ul>
                             <li>
                                  <a href="./poster.php?p='.$data['post_id'].'&amp;action=delete"><span> Delete</span> <img src="../images/icones/del.png" alt="Supprimer" title="Supprimer ce message" />
                                 </a>
                            </li>

                            <li>
                                <a href="./poster.php?p='.$data['post_id'].'&amp;action=edit"><span>Editer</span><img src="../images/icones/edit.png" alt="Editer" title="Editer ce message" />
                                </a>
                            </li>

                       </ul>
                      </div>';
                    }

                    echo '<div class="lemessage">' .code(nl2br(stripslashes(htmlspecialchars($data['post_texte'])))).'</div>

                     <div class="signatureforum">';
                               echo  code(nl2br(stripslashes(htmlspecialchars($data['membre_signature'])))).'
                    </div>

                </div>
            </div>';
} 

//Fin de la boucle ! \o/

$query->CloseCursor();

   echo '</div>';

        if(verif_auth($data['auth_modo']))
        {

         echo '<div class="administrationf">

                 <div class="modification-entete adminf-entete">Moderation</div>
               <ul>
                   <li><a href="./moderer.php?action=deplacer&f='.$forum.'&t='.$topic.'"><img src="../images/icones/deplacer.png"/><span>Deplacer</span></a></li>
                   <li>';

                          $query = $bdd->prepare('SELECT topic_locked 
                                                  FROM forum_topic 
                                                  WHERE topic_id = :topic');
                                    $query->bindValue(':topic',$topic,PDO::PARAM_INT);
                                    $query->execute();
                                    $datas =$query->fetch();

                                  if ($datas['topic_locked'] == 1) // Topic verrouillé !
                                  {
                                    echo'<a href="./postok.php?action=unlock&t='.$topic.'">
                                    <img src="../images/icones/ver.png" alt="deverrouiller" title="Déverrouiller ce sujet" /><span>Déverrouiller</span></a>';
                                  }

                                  else //Sinon le topic est déverrouillé !
                                  {
                                    echo'<a href="./postok.php?action=lock&amp;t='.$topic.'">
                                    <img src="../images/icones/ver.png" alt="verrouiller" title="Verrouiller ce sujet" /><span>Verrouiller</span></a>';
                                  }

                       $query->CloseCursor();
                      
                   echo '</li>

                   <li>
                       <a href="moderer.php?action=autoreponse&f='.$forum.'&t='.$topic.'"><img src="../images/icones/autorep.png"/><span>Autoreponse</span></a> 
                  </li>
               </ul>

            </div>';
       }



 ?>


<p class="pagination">

<?php

for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
    if ($i == $page) //On affiche pas la page actuelle en lien
    {
       echo '<strong>'.$i.'</strong>';
    }
    else
    {
      echo '<a href="voirtopic.php?t='.$topic.'&page='.$i.'">'. $i . '</a> ';
    }

}

echo'</p>';


if (verif_auth($data['auth_post']))
{
//On affiche l'image répondre
  echo'<p class="nouveau-sujet" title="Répondre à ce topic">
   <img src="../images/icones/mail.png"/><a href="./poster.php?action=repondre&amp;t='.$topic.'">Repondre</a>
   </p>';
}
 
 
if (verif_auth($data['auth_topic']))
{
//On affiche l'image nouveau topic
echo'<p class="nouveau-sujet" title="Poster un nouveau topic">
        <img src="../images/icones/new.png"/><a href="./poster.php?action=nouveautopic&amp;f='.$forum.'">Nouveau Sujet</a>
     </p>';
}



//On ajoute 1 au nombre de visites de ce topic

$query=$bdd->prepare('UPDATE forum_topic
SET topic_vu = topic_vu + 1 WHERE topic_id = :topic');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->execute();
$query->CloseCursor();

} //Fin du if qui vérifiait si le topic contenait au moins un message
?>
</div>

</div>

</body>
</html>