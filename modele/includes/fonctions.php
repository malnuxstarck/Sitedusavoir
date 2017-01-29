<?php

//Est ce que le pseudo

function pseudoLibre($pseudo,$bdd)
{
  $query = $bdd->prepare('SELECT COUNT(*) AS nbr FROM membres WHERE membre_pseudo =:pseudo');
  $query->bindValue(':pseudo',$pseudo, PDO::PARAM_STR);
  $query->execute();
  $pseudo_free=($query->fetchColumn() == 0)?1:0;
  $query->CloseCursor();

  return $pseudo_free;
}

function mailLibre($email,$bdd)
{
  $query = $bdd->prepare('SELECT COUNT(*) AS nbr FROM membres WHERE membre_email =:mail');
  $query->bindValue(':mail',$email, PDO::PARAM_STR);

  $query->execute();
  $mail_free =($query->fetchColumn()==0)?1:0;

  $query->closeCursor();

  return $mail_free;
}


function miseAjourMdp( $id ,$password ,  $bdd )
{
        
        $req = $bdd->prepare('UPDATE membres SET membre_mdp = :pass, reset = NULL, reset_at = NULL WHERE membre_id = :id');
        $req->execute(array('pass'=> $password,'id'=> $id));
        $req->closeCursor();
}

function resetTakeToken($id, $token, $bdd)
{

     $req = $bdd->prepare('UPDATE membres SET reset = :token , reset_at = NOW() WHERE membre_id = :id');

      $req->execute(array(
        'token'=> $token,
        'id'  => $id
      ));

}


function getInfosById($id , $bdd)
{

        $query = $bdd->prepare('SELECT *
                                FROM membres
                                WHERE membre_id = :id
                                ');

        $query->bindValue(':id',$id,PDO::PARAM_INT);

        $query->execute();

        $user = $query->fetch();

        return $user;

}



function getInfosUtilisateurs($pseudo , $bdd)
{

        $query = $bdd->prepare('SELECT membre_mdp, membre_id,membre_rang, membre_pseudo,membre_inscrit
                                FROM membres
                                WHERE membre_pseudo = :pseudo
                                AND membre_inscrit IS NOT NULL');

        $query->bindValue(':pseudo',$pseudo,PDO::PARAM_STR);

        $query->execute();

        $data = $query->fetch();

        return $data;

}


function setDerniereVisite($membre_id, $bdd)
{
  $requete = $bdd->prepare('UPDATE membres
                                      SET membre_derniere_visite = NOW()
                                      WHERE membre_id = :id');

  $requete->bindValue(':id',$membre_id,PDO::PARAM_INT);
  $requete->execute();
}

function setToken($id , $bdd)
{
  $req = $bdd->prepare('UPDATE membres SET token = NULL WHERE membre_id = :id ');

    $req->execute(array('id' => $id));
    $req->closeCursor();

}

function cookieToken($cookie,$membre_id,$bdd)
{
   $req = $bdd->prepare('UPDATE membres
                                    SET cookie = :cookie
                                    WHERE membre_id = :id');
               $req->execute(array('cookie'=> $cookie, 'id' => $membre_id));
}


function totalDesMembres($bdd)
{
  return $bdd->query('SELECT COUNT(*) FROM membres')->fetchColumn();
}


function whoIsOnlineInfos($bdd)
{

  $query = $bdd->prepare('SELECT membre_id, membre_pseudo
                        FROM forum_whosonline
                        LEFT JOIN membres 
                        ON online_id = membre_id
                        WHERE online_time > SUBDATE(NOW(), INTERVAL 5 MINUTE) 
                        AND online_id <> 0');
    $query->execute();

     return $query;
}

function visiteursEnligne($bdd)
{
   return $bdd->query('SELECT COUNT(*) AS nbr_visiteurs
                              FROM forum_whosonline 
                              WHERE online_id = 0')->fetchColumn();
}

function dernierMembre($bdd)
{
  $query = $bdd->query('SELECT membre_pseudo, membre_id FROM membres ORDER BY membre_id DESC LIMIT 0, 1');

   return $data = $query->fetch();
}

function getTopicInfos($topic , $bdd)
{
  $query=$bdd->prepare('SELECT topic_titre, forum_topic.forum_id,forum_name,topic_post,topic_last_post,auth_view, auth_post, auth_topic, auth_annonce, auth_modo
                        FROM forum_topic
                        LEFT JOIN forum 
                        ON forum.forum_id = forum_topic.forum_id
                        WHERE topic_id =:topic');
  $query->bindValue(':topic',$topic,PDO::PARAM_INT);

  $query->execute();
  $data = $query->fetch();

  return $data;
   
}

function getForumInfos($forum , $bdd)
{
  
  $query= $bdd->prepare('SELECT forum_id ,forum_name,forum_topic, auth_view, auth_post, auth_topic,auth_annonce, auth_modo
                         FROM forum 
                         WHERE forum_id =:forum');

  $query->bindValue(':forum',$forum,PDO::PARAM_INT);
  $query->execute();
  $data = $query->fetch();
   return $data;
}

function getPostInfos($post , $bdd)
{
  $query = $bdd->prepare('SELECT post_createur, forum_post.topic_id, topic_titre,forum_topic.forum_id,forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
                          FROM forum_post
                          LEFT JOIN forum_topic 
                          ON forum_topic.topic_id = forum_post.topic_id
                          LEFT JOIN forum 
                          ON forum.forum_id = forum_topic.forum_id
                          WHERE forum_post.post_id = :post');

  $query->bindValue(':post',$post,PDO::PARAM_INT);
  $query->execute();

  $data = $query->fetch();
  
  return $data;
}


function EditPost($post , $bdd)
{

    $query = $bdd->prepare('SELECT post_createur, post_texte ,auth_modo
                        FROM forum_post
                        LEFT JOIN forum 
                        ON forum_post.post_forum_id = forum.forum_id
                        WHERE post_id= :post');
$query->bindValue(':post',$post,PDO::PARAM_INT);
$query->execute();
$data = $query->fetch();

return $data;

}

function getAllPost($topic,$premierMessageAafficher , $nombreDeMessagesParPage, $bdd)
{
  $query = $bdd->prepare('SELECT post_id , post_createur , post_texte , DATE_FORMAT(post_time ,\'%d/%m/%Y %H:%i:%s\') AS post_time, membre_id, membre_pseudo, DATE_FORMAT(membre_inscrit, \'%d/%m/%Y %H:%i:%s\') AS membre_inscrit, membre_avatar,
  membre_localisation, membre_post, membre_signature
                       FROM forum_post
                       LEFT JOIN membres ON membres.membre_id = forum_post.post_createur
                       WHERE topic_id =:topic
                       ORDER BY post_id
                       LIMIT :premier, :nombre');
  
  $query->bindValue(':topic',$topic,PDO::PARAM_INT);
  $query->bindValue(':premier',(int)$premierMessageAafficher,PDO::PARAM_INT);
  $query->bindValue(':nombre',(int)$nombreDeMessagesParPage,PDO::PARAM_INT);
  $query->execute();

  return $query;

}


function getAllAutoMess($bdd)
{
  $query = $bdd->query('SELECT automess_id, automess_titre FROM forum_automess');
  return $query ;
}


function isTopicLock($bdd, $topic)
{
  $query = $bdd->prepare('SELECT topic_locked FROM forum_topic WHERE topic_id = :topic');
    $query->bindValue(':topic',$topic,PDO::PARAM_INT);
    $query->execute();
    $datas = $query->fetch();
}

function getAllForumsExceptHe($bdd ,$forum)
{
  $query=$bdd->prepare('SELECT forum_id, forum_name 
                          FROM forum 
                          WHERE forum_id <> :forum');
    $query->bindValue(':forum',$forum,PDO::PARAM_INT);
    $query->execute();

    return $query ;
}

function selectForumByForum($signe,$bdd,$forum,$id)
{
  $add1='';
  $add2 ='';

  if ($id!=0) //on est connecté
  {
    //Premièrement, sélection des champs
    $add1 = ',tv_id, tv_post_id, tv_poste';

    //Deuxièmement, jointure
    $add2 = 'LEFT JOIN forum_topic_view
    ON forum_topic.topic_id = forum_topic_view.tv_topic_id AND
    forum_topic_view.tv_id = :id';
  }

  $query = $bdd->prepare('SELECT forum_topic.topic_id, topic_titre,
  topic_createur, topic_vu, topic_post, topic_time, topic_last_post,
  Mb.membre_pseudo AS membre_pseudo_createur, post_createur,
  post_time, Ma.membre_pseudo AS membre_pseudo_last_posteur,post_id '.$add1.' FROM forum_topic
  LEFT JOIN membres Mb ON Mb.membre_id = forum_topic.topic_createur
  LEFT JOIN forum_post ON forum_topic.topic_last_post = forum_post.post_id
  LEFT JOIN membres Ma ON Ma.membre_id = forum_post.post_createur
  '.$add2.' WHERE topic_genre '.$signe.' "Annonce" AND forum_topic.forum_id =:forum ORDER BY topic_last_post DESC');

  $query->bindParam(':forum',$forum,PDO::PARAM_INT);

  if($id!=0)
  $query->bindParam(':id',$id,PDO::PARAM_INT);
  $query->execute();

  return $query;

}