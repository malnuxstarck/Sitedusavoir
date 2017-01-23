<?php

   if($id!=0)
    {
  		$query=$bdd->prepare('SELECT COUNT(*) 
                            FROM forum_topic_view 
                            WHERE tv_topic_id = :topic 
                            AND tv_id = :id');

  		$query->bindValue(':topic',$topic,PDO::PARAM_INT);
  		$query->bindValue(':id',$id,PDO::PARAM_INT);
  		$query->execute();
  		$nbr_vu = $query->fetchColumn();
  		$query->CloseCursor();

  		if ($nbr_vu == 0) //Si c'est la première fois on insère une ligne entière
  		{
    		$query = $bdd->prepare('INSERT INTO forum_topic_view (tv_id, tv_topic_id, tv_forum_id, tv_post_id)
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
                               SET tv_post_id = :last_post 
                               WHERE tv_topic_id = :topic 
                               AND tv_id = :id');

    		$query->bindValue(':last_post',$data['topic_last_post'],PDO::PARAM_INT);
    		$query->bindValue(':topic',$topic,PDO::PARAM_INT);
    		$query->bindValue(':id',$id,PDO::PARAM_INT);
    		$query->execute();
    		$query->CloseCursor();
		  }
    }