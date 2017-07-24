<?php


class ManagerTopicView
{
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

	public function nouvellevu(TopicView $topic_view)
	{
		$query = $this->_db->prepare('INSERT INTO topic_view(tv_id, tv_topic_id, tv_forum_id, tv_post_id, tv_poste)
                                      VALUES(:id, :topic, :forum, :post, :poste)');

		$query->bindValue(':id',$topic_view->tv_id(),PDO::PARAM_INT);
		$query->bindValue(':topic',$topic_view->tv_topic_id(),PDO::PARAM_INT);
		$query->bindValue(':forum',$topic_view->tv_forum_id() ,PDO::PARAM_INT);

		$query->bindValue(':post',$topic_view->tv_post_id(),PDO::PARAM_INT);
		$query->bindValue(':poste',$topic_view->tv_poste(),PDO::PARAM_STR);
		$query->execute();
		$query->closeCursor();
	}

	public function miseAjoursVu(TopicView $topic_view)
	{
		$query = $this->_db->prepare('UPDATE topic_view SET tv_post_id = :post, tv_poste = :poste
			                          WHERE tv_id = :id AND tv_topic_id = :topic');

		$query->bindValue(':post',$topic_view->tv_post_id(),PDO::PARAM_INT);
		$query->bindValue(':poste','1',PDO::PARAM_STR);
		$query->bindValue(':id',$topic_view->tv_id(),PDO::PARAM_INT);

		$query->bindValue(':topic',$topic_view->tv_topic_id(),PDO::PARAM_INT);
		$query->execute();
		$query->CloseCursor();
	}

	public function nombreVusTopicDuMembre($idMembre , $idTopic)
	{
		$query = $this->_db->prepare('SELECT COUNT(*) AS nbr_vu
                              FROM topic_view 
                              WHERE tv_topic_id = :topic 
                              AND tv_id = :id');

	    $query->bindValue(':topic',$idTopic,PDO::PARAM_INT);
	    $query->bindValue(':id',$idMembre,PDO::PARAM_INT);
	    $query->execute();
	    $nbr_vu = $query->fetchColumn();
	    $query->CloseCursor();

	    return $nbr_vu ;

	}
}