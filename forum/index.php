<?php
include "../includes/session.php";
$titre ="Forums | SiteduSavoir.com";

include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

?>

<?php
  echo '<div class="fildariane">

                <ul>
                    <li>
                        <a href="../index.php">Accueil </a>
                    </li> <img src="../images/icones/fleche.png" class="fleche"/>

                    <li>
                        <a href="./index.php">Forum </a>
                     </li> 

                 </ul>      

         </div>

         <div class="page pageforum">

                <h1 class="titre">Forum</h1>';

                  $totaldesmessages = 0;
                  $categorie = NULL;
                  $add1='';

                  $add2 ='';

                  if($id != 0) //on est connecté
                  {
                      //Premièrement, sélection des champs

                      $add1 = ',tv_id, tv_post_id, tv_poste';

                      //Deuxièmement, jointure

                      $add2 = 'LEFT JOIN forum_topic_view
                               ON forum_topic.topic_id = forum_topic_view.tv_topic_id 
                               AND
                                 forum_topic_view.tv_id = :id';

                   }


//Cette requête permet d'obtenir tout sur le forum

                      $query = $bdd->prepare('SELECT cat_id, cat_nom, forum.forum_id, forum_name,forum_last_post_id, forum_desc, forum_post, forum_topic, auth_view, forum_topic.topic_id,
                               forum_topic.topic_post, post_id, DATE_FORMAT(post_time, \'%d/%m/%Y %H:%i:%s\') AS post_time  , post_createur, membre_pseudo, membre_id '.$add1.'

                        FROM categorie

                        LEFT JOIN forum ON categorie.cat_id = forum.forum_cat_id

                        LEFT JOIN forum_post ON forum_post.post_id = forum.forum_last_post_id

                        LEFT JOIN forum_topic ON forum_topic.topic_id = forum_post.topic_id

                        LEFT JOIN membres ON membres.membre_id = forum_post.post_createur '.$add2.'

                        WHERE auth_view <= :lvl 

                        ORDER BY cat_ordre, forum_ordre DESC');

                      $query->bindValue(':lvl',$lvl,PDO::PARAM_INT);

                      if($id!=0)
                          $query->bindValue(':id',$id,PDO::PARAM_INT);

 

                       $query->execute();




                echo '<div class="forums">

                        <ul class="listesforums">
                          <div>
                        ';

while($data = $query->fetch())

{

    //On affiche chaque catégorie

    if( $categorie != $data['cat_id'] )

    {

        //Si c'est une nouvelle catégorie on l'affiche
         $categorie = $data['cat_id'];

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
                                  <?php echo stripslashes(htmlspecialchars($data['cat_nom'])); ?>
                               </span>
                      </div>
                      

        <?php
}

    // Ce super echo de la mort affiche tous

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
        <li>
                <p class="forumlogo">
                    <img src="../images/icones/forum.png" alt="message"/>
                </p>

                <p class="infosforum">

                    <span>
                         <a href="./voirforum.php?f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
                    </span>

                    <span>'.stripslashes(htmlspecialchars($data['forum_desc'])).'</span>
               </p>

               <p class="nombresujets">
                    <span>'.$data['forum_topic'].'</span><span> Sujets </span>
               </p>

               <p class="nombremessages">
                    <span>'.$data['forum_post'].'</span><span> msgs</span>
               </p>';


    // Deux cas possibles :

    // Soit il y a un nouveau message, soit le forum est vide

    if (!empty($data['forum_post']))

    {

         //Selection dernier message

        $nombreDeMessagesParPage = 15;

        $nbr_post = $data['topic_post'] + 1;

        $page = ceil ($nbr_post / $nombreDeMessagesParPage);

         

                 echo'<p class="derniermessage">

                          <span>Dernier message </span>

                          <span>
                             Par <a href="./voirprofil.php?m='.stripslashes(htmlspecialchars($data['membre_id'])).'&amp;action=consulter">'.$data['membre_pseudo'].'</a>
                             le <a href="./voirtopic.php?t='.$data['topic_id'].'&amp;page='.$page.'#p_'.$data['post_id'].'">'.$data['post_time'].'</a>
                        </span>
                  </p>

                  </li>';
    }

    else

    {

         echo'<p class="derniermessage"><span>Pas de message</span></p>
         </li>';

    }


     

     $totaldesmessages += $data['forum_post'];

}

$query->CloseCursor();

echo '   </ul>
     </div>

  <div class="statistique"> 

     <span class="stat-entete"> Statistique </span>
       <div >';

          //On compte les membres

      $TotalDesMembres = $bdd->query('SELECT COUNT(*) FROM membres')->fetchColumn();

      $query->CloseCursor();  

      $query = $bdd->query('SELECT membre_pseudo, membre_id FROM membres ORDER BY membre_id DESC LIMIT 0, 1');

      $data = $query->fetch();

      $derniermembre = stripslashes(htmlspecialchars($data['membre_pseudo']));


      echo'<p>Le total des messages du forum est <strong>'.$totaldesmessages.'</strong>.</p>';

      echo'<p>Le site et le forum comptent <strong>'.$TotalDesMembres.'</strong> membres.</p>';

      echo'<p>Le dernier membre est <a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">'.$derniermembre.'</a>.</p>
  </div>
  </div>';

$query->CloseCursor();





echo '</div>';
include "../includes/footer.php";

echo '</body>
</html>';
