
<?php

/* se charge des operations sur la base de donnees pour la class Post */

class ManagerPost
{
	const ERR_AUTH_EDIT = "Vous ne pouveez pas modifier ce Post ";
	const ERR_AUTH_DELETE = "Vous ne pouvez pas supprimer ce message ";
	protected $_db;
	public $_iErros = 0 ;
	protected $_errors = array();

	public function __construct(PDO $bdd)
	{
		$this->setDb($bdd);
	}

	public function setDb($bdd)
	{
		$this->_db = $bdd ;
	}

	public function infosPost($idPost)
	{
		$query = $this->_db->prepare('SELECT topic.id AS id ,post.createur AS createur, post.topic AS topic, titre, topic.forum AS forum, post.texte AS texte ,locked, posttime,name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
	                        FROM post
	                        LEFT JOIN topic 
	                        ON topic.id = post.topic
	                        LEFT JOIN forums 
	                        ON forums.id = topic.forum
	                        WHERE post.id = :post');

		$query->bindValue(':post',$idPost,PDO::PARAM_INT);
		$query->execute();

		$donnees = $query->fetch();

		if(!empty($donnees))
			return $donnees;
		else
			return array();

	}

	public function verifierChamps(Post $post)
	{
		if(empty($post->texte()))
		{
			$this->_iErros++;
			$this->_errors['texte'] = '<p>Le message du post est vides</p>';
		}

	}

	public function errors()
	{
		return $this->_errors;
	}

	public function nouveauPost(Post $post)
	{
		$query = $this->_db->prepare('INSERT INTO post(createur, texte, posttime, topic, forum)
		                             VALUES (:id, :mess, NOW(), :topic, :forum)');

		$query->bindValue(':id', $post->createur(), PDO::PARAM_INT);
		$query->bindValue(':mess', $post->texte(), PDO::PARAM_STR);
		$query->bindValue(':topic', (int)$post->topic(), PDO::PARAM_INT);
		$query->bindValue(':forum', $post->forum(), PDO::PARAM_INT);
		$query->execute();

		$idPost = $this->_db->lastInsertId();
		
		return $idPost;
		
	}

	public function positionDuPostEditer(Post $post)
	{
		$query = $this->_db->prepare('SELECT COUNT(*) AS nbr FROM post WHERE topic = :topic AND posttime < :temps');
		$query->bindValue(':topic',$post->topic(),PDO::PARAM_INT);
		$query->bindValue(':temps',$post->posttime(),PDO::PARAM_STR);
		$query->execute();

		$total=$query->fetchColumn();

		return $total;
	}

	public function miseAjoursPost(Post $post)
	{
		$query = $this->_db->prepare('UPDATE post SET texte = :message WHERE id = :post');
		$query->bindValue(':message',$post->texte(),PDO::PARAM_STR);
		$query->bindValue(':post',$post->id(),PDO::PARAM_INT);

		$query->execute();
		
	}

	public function deletePost($idPost)
	{
		$query = $this->_db->prepare('DELETE FROM post WHERE id = :post');
		$query->bindValue(':post',$idPost,PDO::PARAM_INT);

		$query->execute();
		$query->CloseCursor();
	}

	public function dernierPost($idTopicOuForum , $genre)
	{
		$query = $this->_db->prepare('SELECT *
			                    FROM post WHERE '.$genre.' = :valeur
		                        ORDER BY id DESC LIMIT 0,1');

		$query->bindValue(':valeur',$idTopicOuForum,PDO::PARAM_INT);
		$query->execute();
		$data=$query->fetch();

		if(!empty($data))
			return $data;
		else
			return array();
	}

	public function nombreDePostParMembreDuTopic($idTopic)
	{
		$query = $this->_db->prepare('SELECT createur, COUNT(*) AS nombre_mess FROM post
				              WHERE topic = :topic GROUP BY createur');

		$query->execute(array('topic' => $idTopic));

		$datas = $query->fetchAll();

		return $datas;
	}

	public function deletePostsDuTopic($idTopic)
	{
		$query=$this->_db->prepare('DELETE FROM post WHERE topic =:topic');
		$query->bindValue(':topic',$idTopic,PDO::PARAM_INT);

	    $query->execute();
		$query->CloseCursor();
	}

	public function deplacerPostsVersForum(Topic $topic)
	{
		$query = $this->_db->prepare('UPDATE post SET forum = :dest
			                    WHERE topic = :topic');

	    $query->bindValue(':dest',$topic->forum(),PDO::PARAM_INT);
	    $query->bindValue(':topic',$topic->id(),PDO::PARAM_INT);
	    $query->execute();
		$query->CloseCursor();
	}

	public function nombrePostPourCeTopic($idTopic)
	{
		$query=$this->_db->prepare('SELECT COUNT(*) AS nombre_post FROM post WHERE topic = :topic');
		$query->bindValue(':topic',$idTopic,PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchColumn();

		return $data;
	}


	public function tousLesPostsDuTopic($idTopic , $debut , $nombre)
	{
		$query = $this->_db->prepare('SELECT post.id As id, post.createur AS createur ,rang, texte ,DATE_FORMAT(posttime ,\'%d/%m/%Y à %H:%i:%s\') AS posttime,membres.id AS idMembre, pseudo, DATE_FORMAT(inscrit, \'%d/%m/%Y à %H:%i:%s\') 
                        AS inscrit, avatar,localisation, membres.posts AS posts, signature
                       FROM post
                       LEFT JOIN membres ON membres.id = post.createur
                       WHERE topic =:topic
                       ORDER BY id
                       LIMIT :premier, :nombre');

        $query->bindValue(':topic',$idTopic,PDO::PARAM_INT);
        $query->bindValue(':premier',(int)$debut,PDO::PARAM_INT);
        $query->bindValue(':nombre',(int)$nombre,PDO::PARAM_INT);

        $query->execute();

        $datas = $query->fetchAll();
        
        return $datas;

	}

}