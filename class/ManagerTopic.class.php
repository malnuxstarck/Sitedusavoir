

<?php

class ManagerTopic
{
	protected $_db;
	public $_errors;

	/* Le constructeur */

	public function __construct(PDO $bdd)
	{
		$this->setDb($bdd);
	}

	public function setDb($bdd)
	{
		$this->_db = $bdd ;
	}

	public function tousLesTopics($add1,$add2, $add3,$idMembre,$forum, $signe = '<>',$nombresDeMessages = 15 , $premierMessage)
	{
		$query = $bdd->prepare('SELECT topic.id, titre,createur, vus, topics.posts AS posts, topic_time, last_post, Mb.membre_pseudo 
		                   AS membre_pseudo_createur, Mb.membre_avatar AS avatar_createur, post_createur,post_time, Ma.membre_pseudo 
		                   AS membre_pseudo_last_posteur,post_id '.$add1.' FROM forum_topic
	                       LEFT JOIN membres Mb ON Mb.membre_id = forum_topic.topic_createur
	                       LEFT JOIN forum_post ON forum_topic.topic_last_post = forum_post.post_id
	                       LEFT JOIN membres Ma ON Ma.membre_id = forum_post.post_createur
	                      '.$add2.' 
	                       WHERE topic_genre = "Annonce" AND forum_topic.forum_id =:forum ORDER BY topic_last_post DESC');

		$query->bindParam(':forum',$forum->id(),PDO::PARAM_INT);

		if($id!=0)
			$query->bindParam(':id',$id,PDO::PARAM_INT);

		if(!empty($add3))
		{
			$query->bindValue(':premier',(int) $premierMessage,PDO::PARAM_INT);
	        $query->bindValue(':nombre',(int) $nombresDeMessages,PDO::PARAM_INT);
	    }
	        
		$query->execute();

		$donnees = $query->fetchAll();

		if(!empty($donnees))
			return $donnees;
        else
        	return array();
	}
}