
<?php

/* Manager les actions de la classe Forum */

class ManagerForum
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

	public function nouveauForum(Forum $forum)
	{
		$query = $this->_db->prepare('INSERT INTO forums (name ,cat ,description ,ordre) VALUES(:name , :cat ,:description,NULL)');
		$query->bindValue(':cat',$forum->cat() ,PDO::PARAM_INT);
		$query->bindValue(':name' , $forum->name() ,PDO::PARAM_STR);
		
		$query->bindValue(':description' , $forum->description() ,PDO::PARAM_STR);
		$query->execute();
	}

	public function miseAjoursForum(Forum $forum)
	{
		$query = $this->_db->prepare('UPDATE forums SET name = :nom , description = :descr , cat = :cat WHERE id = :id');
		$query->bindValue(':nom' ,$forum->name() , PDO::PARAM_STR);
		$query->bindValue(':descr' , $forum->description() , PDO::PARAM_STR);

		$query->bindValue(':id' , $forum->id() , PDO::PARAM_INT);
		$query->bindValue(':cat' , $forum->cat() , PDO::PARAM_INT);


		$query->execute();
	}

	public function infosForum($idForum)
	{
		$query = $this->_db->prepare('SELECT * FROM forums WHERE id = :id');
		$query->bindValue(':id', $idForum, PDO::PARAM_INT);
		$query->execute();

		$donnees = $query->fetch();

		if(!empty($donnees))
            return $donnees;
        else
        	return array();
	}

	public function ajoutJointuresViews($id)
	{
		if($id != 0)
		{
			$add1 = ',tv_id, tv_post_id, tv_poste';

			$add2 = 'LEFT JOIN topic_view
                               ON topic.id = topic_view.tv_topic_id 
                               AND
                                topic_view.tv_id = :id';

		}
		else
		{
			$add1 = '';
			$add2 = '';
		}

		return array('add1' => $add1 ,'add2' => $add2);
	}

	public function tousLesForums($jointures , $id , $levelMembre)
	{
		$query = $this->_db->prepare('SELECT categories.id AS idCat,categories.ordre AS ordreCat, categories.nom AS nom , forums.id AS id , name,last_post_id, description, forums.posts AS posts ,forums.ordre AS ordre, forums.topics AS topics , auth_view, topic.id AS idTopic,
                               topic.posts AS topicsPosts , post.id AS idPost, DATE_FORMAT(posttime, \'%d/%m/%Y %H:%i:%s\') AS posttime  , post.createur AS createur, pseudo, membres.id AS idMembre '.$jointures['add1'].'

                        FROM categories

                        LEFT JOIN forums ON categories.id = forums.cat

                        LEFT JOIN post ON post.id = forums.last_post_id

                        LEFT JOIN topic  ON topic.id = post.topic

                        LEFT JOIN membres ON membres.id = post.createur '.$jointures['add2'].'

                        WHERE auth_view <= :lvl 

                        ORDER BY categories.ordre, forums.ordre DESC');

                      $query->bindValue(':lvl',$levelMembre,PDO::PARAM_INT);

                      if($id!=0)
                          $query->bindValue(':id',$id,PDO::PARAM_INT);

 

                       $query->execute();

                       $donnees = $query->fetchAll();

                       if(!empty($donnees))
                       	    return $donnees;
                       	else
                       		return array();

	}

	public function obtenirToutSurCeForum($jointures ,$idForum , $idUser ,$genre,$debut,$totalAafficher)
	{
		$query = $this->_db->prepare('SELECT topic.id AS id , titre, topic.createur AS createur, vus , topic.posts AS posts, topictime, last_post, Mb.pseudo AS  pseudo, Mb.avatar AS avatar, post.createur AS createurPost ,posttime, Ma.pseudo AS pseudoPosteur,post.id AS idPost'.$jointures['add1'].' FROM topic
	                       LEFT JOIN membres Mb ON Mb.id = topic.createur
	                       LEFT JOIN post ON topic.last_post = post.id
	                       LEFT JOIN membres Ma ON Ma.id = post.createur
	                      '.$jointures['add2'].' 
	                      WHERE genre = :gen AND topic.forum = :forum ORDER BY last_post DESC LIMIT :debut, :nombre');

	    if($idUser != 0)
	        $query->bindValue(':id',$idUser,PDO::PARAM_INT);

	    $query->bindValue(':forum',$idForum,PDO::PARAM_INT);
		$query->bindValue(':debut' , $debut , PDO::PARAM_INT);
		$query->bindValue(':nombre' , $totalAafficher , PDO::PARAM_INT);
		$query->bindValue(':gen' ,$genre ,PDO::PARAM_STR);


	    $query->execute();

	    $donnees = $query->fetchAll();

	    if(!empty($donnees))
	    	return $donnees;
	    else
	    	return array();


	}

	public function augmenterNombreTopicEtPost($idForum , $last_post ,$nombrePost = 1 , $nombreTopic = 1)
	{
		$query = $this->_db->prepare('UPDATE forums SET posts = posts + :nbrPost ,topics = topics + :nbrTopic, last_post_id = :nouveaupost
		WHERE id = :forum');

		$query->bindValue(':nouveaupost', (int)$last_post,PDO::PARAM_INT);
		$query->bindValue(':forum', (int) $idForum, PDO::PARAM_INT);
		$query->bindValue(':nbrPost' , $nombrePost , PDO::PARAM_INT);
		$query->bindValue(':nbrTopic' , $nombreTopic ,PDO::PARAM_INT);

		$query->execute();
		$query->CloseCursor();
	}

	public function diminuerNombrePost(Forum $forum ,$nbrePost = -1)
	{
		$query = $this->_db->prepare('UPDATE forums SET posts = posts + :nbr , last_post_id = :last WHERE id = :forum');
		$query->bindValue(':last',$forum->last_post_id(),PDO::PARAM_INT);
		$query->bindValue(':forum',$forum->id(),PDO::PARAM_INT);
		$query->bindValue(':nbr' , $nbrePost , PDO::PARAM_INT);

		$query->execute();
		$query->CloseCursor();
	}

	public function diminuerNombrePostEtTopic(Forum $forum , $nombrePost = 1 , $nombreTopic = 1)
	{

		$query = $this->_db->prepare('UPDATE forums SET posts = posts - :nbrPost , topics = topics - :nbrTopic ,last_post_id = :last WHERE id = :forum');
		$query->bindValue(':last',$forum->last_post_id(),PDO::PARAM_INT);
		$query->bindValue(':forum',$forum->id(),PDO::PARAM_INT);
		$query->bindValue(':nbrPost' , $nombrePost , PDO::PARAM_INT);
		$query->bindValue(':nbrTopic' , $nombreTopic ,PDO::PARAM_INT);

		$query->execute();
		$query->CloseCursor();

	}

	public function forumsDifferentDeCeluiLa($idForum)
	{
		$query = $this->_db->prepare('SELECT * 
                        FROM forums
                        WHERE id <> :forum');
        $query->bindValue(':forum',$idForum,PDO::PARAM_INT);
        $query->execute();

        return $datas = $query->fetchAll();

	}

	public function modifierOrdre($forum)
	{
		 $query = $this->_db->prepare('UPDATE forum 
                                      SET ordre = :ordre
                                      WHERE id = :id');

        $query->bindValue(':ordre',$forum->ordre(),PDO::PARAM_INT);
        $query->bindValue(':id',$forum->id(),PDO::PARAM_INT);

        $query->execute();
        $query->CloseCursor();
	}

	public function miseAjoursDroits($forum)
	{
		$query = $this->_db->prepare('UPDATE forums 
                                    SET auth_view = :view, auth_post = :post, auth_topic = :topic, auth_annonce = :annonce, auth_modo = :modo 
                                    WHERE id = :id');

            $query->bindValue(':view',$forum->auth_view(),PDO::PARAM_INT);
            $query->bindValue(':post',$forum->auth_post(),PDO::PARAM_INT);

            $query->bindValue(':topic',$forum->auth_topic(),PDO::PARAM_INT);
            $query->bindValue(':annonce',$forum->auth_annonce(),PDO::PARAM_INT);
            $query->bindValue(':modo',$forum->auth_modo(),PDO::PARAM_INT);

            $query->bindValue(':id',(int)$forum->id(),PDO::PARAM_INT);

            $query->execute();

            $query->CloseCursor();
	}


}