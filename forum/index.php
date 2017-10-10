<?php

include "../includes/session.php";
$titre ="Forums | SiteduSavoir.com";
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

$managerForum = new ManagerForum($bdd);
echo '<ul class="fildariane">
  <li><a href="../index.php">Accueil</a></li>
  <li><a href="./index.php">Forum</a></li>
</ul>

<div class="page pageforum">

  <h1 class="titre">Forum</h1>';

$categorieAlaBoucle = NULL ;
$totaldesmessages = 0 ;

//Cette requête permet d'obtenir tout sur le forum

$jointures     = $managerForum->ajoutJointuresViews($id);
$donneesForums = $managerForum->tousLesForums($jointures , $id , $lvl);


 echo '<div class="forums">
              <ul class="listesforums">
                  <div>
                        ';

foreach ($donneesForums as $donneesForum ) {

    $forum = new Forum($donneesForum);
    $categorie = new Categorie($donneesForum);
    $categorie->setId($donneesForum['idCat']);

    $membre    = new Membre($donneesForum);
    $membre->setId($donneesForum['idMembre']);

    $topic = new Topic($donneesForum);
    $topic->setId($donneesForum['idTopic']);
    $topic->setPosts($donneesForum['topicsPosts']);

    $post = new Post($donneesForum);
    $post->setID($donneesForum['idPost']);

    $topic_view = new TopicView($donneesForum);

    //On affiche chaque catégorie

    if( $categorieAlaBoucle != $categorie->id())

    {

        //Si c'est une nouvelle catégorie on l'affiche , et On stocke l'id pour le prochain passage
         $categorieAlaBoucle = $categorie->id();

       ?>
          </div>
           </ul>

            <ul class="listesforums">

               <div class="forum">
                       <div class="categorie-nom">
                              <span class="cat">
                                  <img src="../images/icones/cat.png"/>
                              </span>
                              <span>
                                  <?php echo stripslashes(htmlspecialchars($categorie->nom())); ?>
                               </span>
                      </div>


        <?php
    }

    // Ce super echo de la mort affiche tous

    // les forums en détail : description, nombre de réponses etc...

     if (!empty($id)) // Si le membre est connecté
     {
        if ($topic_view->tv_id() == $id) //S'il a lu le topic
        {
                if ($topic_view->tv_poste() == '0') // S'il n'a pas posté
                {
                        if ($topic_view->tv_post_id() == $forum->last_post_id())
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
                        if ($topic_view->tv_post_id() == $forum->last_post_id())
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
        <li>
                <p class="forumlogo">
                    <img src="../images/icones/forum.png" alt="message"/>
                </p>

                <p class="infosforum">

                    <span>
                         <a href="./voirforum.php?f='.$forum->id().'">'.stripslashes(htmlspecialchars($forum->name())).'</a>
                    </span>

                    <span>'.stripslashes(htmlspecialchars($forum->description())).'</span>
               </p>

               <p class="nombresujets">
                    <span>'.$forum->topics().'</span><span style="font-weight:normal;"> Sujets </span>
               </p>

               <p class="nombremessages">
                    <span>'.$forum->posts().'</span><span style="font-weight:normal;"> msgs</span>
               </p>';


    // Deux cas possibles :

    // Soit il y a un nouveau message, soit le forum est vide

    if (!empty($topic->posts()))
    {

         //Selection dernier message

        $nombreDeMessagesParPage = 15;

        $nbr_post = $topic->posts() + 1;

        $page = ceil ($nbr_post / $nombreDeMessagesParPage);



                 echo'<p class="derniermessagef">

                          <span>Dernier message </span>

                          <span>
                            <a href="./voirprofil.php?m='.stripslashes(htmlspecialchars($membre->id())).'&amp;action=consulter">'.$membre->pseudo().'</a>
                             le <a href="./voirtopic.php?t='.$topic->id().'&amp;page='.$page.'#p_'.$post->id().'">'.$post->posttime().'</a>
                        </span>
                  </p>

                  </li>';
    }

    else

    {

         echo'<p class="derniermessagef"><span>Pas de message</span></p>
         </li>';

    }




     $totaldesmessages += $forum->posts();

}

echo '</ul>
     </div>

  <div class="statistique">

     <span class="stat-entete"> Statistique </span>
        <div>';

          //On compte les membres

            $managerMembre = new ManagerMembre($bdd);
            $totalDesMembres = $managerMembre->totalDesMembres();

            $dernierInscritDonnees = $managerMembre->dernierInscrit();
            $derniermembre = new Membre($dernierInscritDonnees);


            echo'<p>Le total des messages du forum est <strong>'.$totaldesmessages.'</strong>.</p>';

            echo'<p>Le site et le forum comptent <strong>'.$totalDesMembres.'</strong> membres.</p>';

            echo'<p>Le dernier membre est <a href="./voirprofil.php?m='.$derniermembre->id().'&amp;action=consulter">'.$derniermembre->pseudo().'</a>.</p>
       </div>
  </div>';


echo '</div>';

include "../includes/footer.php";

echo '</body>
</html>';
