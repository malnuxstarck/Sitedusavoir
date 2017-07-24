<?php

include "../includes/session.php";
include("../includes/identifiants.php");
include("../includes/bbcode.php");

include "../class/ManagerTopic.class.php";
include "../class/Topic.class.php";

//On récupère la valeur de t

$idTopic = (isset($_GET['t']))?(int)$_GET['t']:1;

$managerTopic = new ManagerTopic($bdd);
$donneesTopic = $managerTopic->infosTopic($idTopic);

if(empty($donneesTopic)) // si on ne trouve rien on redirige sur le premier topic :D
{
   /* A activer apres la premier creation de topic
     $idTopic = 1 ;
     $donneesTopic = $managerTopic->infosTopic($idTopic);
    */

     $_SESSION["flash"]["success"] = "Le topic n'existe pas ou a été supprimer ";
     header('Location:./index.php');
     exit();
}     


$topic = new Topic($donneesTopic);

//A partir d'ici, on va compter le nombre de messages pourn'afficher que les 15 premiers

$titre = $topic->titre();
include("../includes/debut.php");
include("../includes/menu.php");

$forum = new Forum($donneesTopic);
$forum->setId($topic->forum());

if (!Membre::verif_auth($forum->auth_view()))
{
    erreur(ManagerTopic::ERR_AUTH_VIEW);
}

$totalDesMessages = $topic->posts() + 1 ;
$nombreDeMessagesParPage = 15;
$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);
$managerTopicView = new ManagerTopicView($bdd);

if($id != 0 AND $topic->id() != NULL)
{
    $nbr_vu = $managerTopicView->nombreVusTopicDuMembre($id , $topic->id());

    $topic_view = new TopicView(array('tv_id' => $id , 
                                          'tv_forum_id' => $topic->forum() , 
                                          'tv_topic_id' => $topic->id() , 
                                           'tv_poste' => '0',
                                           'tv_post_id' => $topic->last_post()
                                         ));

    if ($nbr_vu == 0) //Si c'est la première fois on insère une ligne entière
    {
        $managerTopicView->nouvellevu($topic_view);
    }
    else 
    //Sinon, on met simplement à jour
    {
        $managerTopicView->miseAjoursVu($topic_view);
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
               <a href="./voirforum.php?f='.$forum->id().'">'.stripslashes(htmlspecialchars($forum->name())).'</a>
           </li>  <img class="fleche" src="../images/icones/fleche.png"/>   
           <li>
            <a href="./voirtopic.php?t='.$topic->id().'">'.stripslashes(htmlspecialchars($topic->titre())).'</a>
            </li>
         </ul>
     <div>

 <div class="page">';

      echo '<h1 class="titre">'.stripslashes(htmlspecialchars($topic->titre())).'</h1><br/><br />';

//Nombre de pages
  $page = (isset($_GET['page']))?intval($_GET['page']):1;

//On affiche les pages 1-2-3 etc...

echo '<p class="pagination">';
           paginationListe($page ,$nombreDePages, 'voirtopic.php?t='.$topic->id()) ;
echo'</p>';


$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

if (Membre::verif_auth($forum->auth_post()))
{

  echo'<p class="nouveau-sujet" title="Répondre à ce topic">
   <img src="../images/icones/mail.png"/><a href="./poster.php?action=repondre&amp;t='.$topic->id().'">Repondre</a>
   </p>';
}
 
 
if (Membre::verif_auth($forum->auth_topic()))
{
    echo'<p class="nouveau-sujet" title="Poster un nouveau topic">
        <img src="../images/icones/new.png"/><a href="./poster.php?action=nouveautopic&amp;f='.$forum->id().'">Nouveau Sujet</a>
     </p>';
}

$managerPost = new ManagerPost($bdd);
$donneesPostsDuTopic = $managerPost->tousLesPostsDuTopic($topic->id() , $premierMessageAafficher ,$nombreDeMessagesParPage );
//On vérifie que la requête a bien retourné des messages


echo '<div class="touslesmessages">';

if (empty($donneesPostsDuTopic))
{
    echo'<p>Il n y a aucun post sur ce topic, vérifiez l url et reessayez</p>';
}
else
{
    foreach ($donneesPostsDuTopic as $donneesPost) {

      $post = new Post($donneesPost);
      $membre = new Membre($donneesPost);
      $membre->setId($post->createur());

      //On commence à afficher le pseudo du créateur du message :
      //On vérifie les droits du membre
      
      echo'<div class="lesujet">

                <div class="membresujet">

                    <img src="../images/avatars/'.$membre->avatar().'" alt="" />';

                    if($membre->rang()== 3)
                          echo'<span class="badge badgef"> Moderateur </span>';
                    else if($membre->rang()== 4)
                          echo '<span class="badge badgef">Admin </span>';
              echo '</div>

            <div class="contenu-message">

               <p class="top-messagef">
                  <a href="./voirprofil.php?m='.$membre->id().'&amp;action=consulter">
                    '.stripslashes(htmlspecialchars($membre->pseudo())).'</a> <span>  le '.$post->posttime().'</span>
               </p>';

                    /* Si on est l'auteur du message, on affiche des liens pour
                    Modérer celui-ci.
                    */
                    
                    if ($id == $post->createur() OR Membre::verif_auth(MODO))
                    {

                    echo'<div class="lienmod">

                             <ul>
                             <li>
                                  <a href="./poster.php?p='.$post->id().'&amp;action=delete"><span> Delete</span> <img src="../images/icones/del.png" alt="Supprimer" title="Supprimer ce message" />
                                 </a>
                            </li>

                            <li>
                                <a href="./poster.php?p='.$post->id().'&amp;action=edit"><span>Editer</span><img src="../images/icones/edit.png" alt="Editer" title="Editer ce message" />
                                </a>
                            </li>

                       </ul>
                      </div>';
                    }

                    echo '<div class="lemessage">' .code(nl2br(stripslashes(htmlspecialchars($post->texte())))).'</div>

                     <div class="signatureforum">';
                               echo  code(nl2br(stripslashes(htmlspecialchars($membre->signature())))).'
                    </div>

                </div>
            </div>';
    } 

//Fin de la boucle ! \o/

   echo '</div>';

        if(Membre::verif_auth($forum->auth_modo()))
        {

            echo '<div class="administrationf">

                 <div class="modification-entete adminf-entete">Moderation</div>
               <ul>
                   <li><a href="./moderer.php?action=deplacer&f='.$forum->id().'&t='.$topic->id().'"><img src="../images/icones/deplacer.png"/><span>Deplacer</span></a></li>
                   <li>';

                                  if ($topic->locked() == 1) // Topic verrouillé !
                                  {
                                    echo'<a href="./postok.php?action=unlock&t='.$topic->id().'">
                                    <img src="../images/icones/ver.png" alt="deverrouiller" title="Déverrouiller ce sujet" /><span>Déverrouiller</span></a>';
                                  }

                                  else //Sinon le topic est déverrouillé !
                                  {
                                    echo'<a href="./postok.php?action=lock&amp;t='.$topic->id().'">
                                    <img src="../images/icones/ver.png" alt="verrouiller" title="Verrouiller ce sujet" /><span>Verrouiller</span></a>';
                                  }
                      
                   echo '</li>

                   <li>
                       <a href="moderer.php?action=autoreponse&f='.$forum->id().'&t='.$topic->id().'"><img src="../images/icones/autorep.png"/><span>Autoreponse</span></a> 
                  </li>
               </ul>

            </div>';
       }

    echo '<p class="pagination">';
           paginationListe($page ,$nombreDePages, 'voirtopic.php?t='.$topic->id()); 
    echo'</p>';

    if (Membre::verif_auth($forum->auth_post()))
    {
    //On affiche l'image répondre
      echo'<p class="nouveau-sujet" title="Répondre à ce topic">
       <img src="../images/icones/mail.png"/><a href="./poster.php?action=repondre&amp;t='.$topic->id().'">Repondre</a>
       </p>';
    }
     
     
    if (Membre::verif_auth($forum->auth_topic()))
    {
    //On affiche l'image nouveau topic
    echo'<p class="nouveau-sujet" title="Poster un nouveau topic">
            <img src="../images/icones/new.png"/><a href="./poster.php?action=nouveautopic&amp;f='.$forum->id().'">Nouveau Sujet</a>
         </p>';
    }



    //On ajoute 1 au nombre de visites de ce topic

    $topic->setVus($topic->vus() + 1 );
    $managerTopic->miseAjoursTopic($topic);

}
 //Fin du if qui vérifiait si le topic contenait au moins un message
?>
</div>

</div>

</body>
</html>