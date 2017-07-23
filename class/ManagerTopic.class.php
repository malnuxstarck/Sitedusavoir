

<?php

class ManagerTopic
{
	const ERR_AUTH_DELETE_TOPIC = "Impossible de supprimer le Message ";
	protected $_db;
    public $_iErros = 0;
	protected $_errors;

	/* Le constructeur */

	public function __construct(PDO $bdd)
	{
		$this->setDb($bdd);
	}

	public function setDb($bdd)
	{
		$this->_db = $bdd ;
	}

	public function infosTopic($idTopic)
	{
		$query = $this->_db->prepare('SELECT topic.id AS id ,titre,topictime, topic.vus AS vus ,createur,last_post ,first_post , topic.posts AS posts, locked , forum ,name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
	                      FROM topic
	                      LEFT JOIN forums 
	                      ON forums.id = topic.forum
	                      WHERE topic.id =:topic');

	    $query->bindValue(':topic',$idTopic,PDO::PARAM_INT);

	    $query->execute();
	    $data = $query->fetch();
	    if(!empty($data))
	    	return $data;
	    else
	    	return array();
	}

	public function verifierChamps(Topic $topic)
	{
		if(empty($topic->titre()))
		{
			$this->_iErros++;
			$this->_errors['titre'] = '<p>Votre message ou votre titre est vide,cliquez <a href="./poster.php?action=nouveautopic&amp;f='.$topic->forum().'">ici</a> pour recommencer</p>';
		}

	}

	public function errors()
	{
		return $this->_errors;
	}

	public function nouveauTopic(Topic $topic)
	{
		$query = $this->_db->prepare('INSERT INTO topic(forum, titre, createur, vus, topictime,genre) 
			                          VALUES (:forum, :titre, :createur, 1, NOW(), :genre)');

		$query->bindValue(':forum', $topic->forum(), PDO::PARAM_INT);
		$query->bindValue(':titre', $topic->titre(), PDO::PARAM_STR);

		$query->bindValue(':createur', $topic->createur(), PDO::PARAM_INT);

		$query->bindValue(':genre', $topic->genre(), PDO::PARAM_STR);
		$query->execute();

		$idTopic = $this->_db->lastInsertId();

		//Notre fameuse fonction !

		return $idTopic;
	}

	public function miseAjoursTopic(Topic $topic)
	{
		$query=$this->_db->prepare('UPDATE topic SET last_post = :dernierpost, first_post = :nouveaupost , vus = :vus , locked = :lock
		                      WHERE id = :nouveautopic');

		$query->bindValue(':nouveaupost', (int)$topic->first_post(),PDO::PARAM_INT);
		$query->bindValue(':dernierpost' , $topic->last_post() , PDO::PARAM_INT);

		$query->bindValue(':nouveautopic', (int)$topic->id(),PDO::PARAM_INT);
		$query->bindValue(':vus' , $topic->vus() , PDO::PARAM_INT);
		$query->bindValue(':lock' , $topic->locked() , PDO::PARAM_STR);

		$query->execute();

		$query->CloseCursor();

	}

	public function diminuerNombrePostDuTopic(Topic $topic , $nbrePost = -1)
	{
		$query = $this->_db->prepare('UPDATE topic SET posts = posts + :nbr WHERE id = :topic');
		$query->bindValue(':topic',$topic->id(),PDO::PARAM_INT);
		$query->bindValue(':nbr' , $nbrePost , PDO::PARAM_INT);

		$query->execute();
		$query->CloseCursor();
	}

	public function deleteTopic($idTopic)
	{
		$query = $this->_db->prepare('DELETE FROM topic WHERE id = :topic');
		$query->bindValue(':topic',$idTopic,PDO::PARAM_INT);

		$query->execute();
		$query->CloseCursor();
	}

	public function changerForum($idTopic , $forum)
	{
		$query = $this->_db->prepare('UPDATE topic SET forum = :val WHERE id = :id');
		$query->bindValue(':val' ,$forum , PDO::PARAM_INT);
		$query->bindValue(':id' ,$idTopic , PDO::PARAM_INT );
		$query->execute();
	}

}