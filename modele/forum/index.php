<?php

if($id != 0) //on est connecté
{
    //Premièrement, sélection des champs

    $add1 = ',tv_id, tv_post_id, tv_poste';

    //Deuxièmement, jointure

    $add2 = 'LEFT JOIN 
                    forum_topic_view
            ON forum_topic.topic_id = forum_topic_view.tv_topic_id AND
               forum_topic_view.tv_id = :id';

}



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
