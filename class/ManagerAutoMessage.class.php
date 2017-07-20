
<?php


class ManagerAutoMessage
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

	public function infosAutoMessage($idAutoMessage)
	{
		$query = $this->_db->prepare('SELECT * FROM automessages WHERE id = :id');
		$query->bindValue(':id' , $idAutoMessage , PDO::PARAM_INT);
		$query->execute();

		$data = $query->fetch();

		if(!empty($data))
			return $data;
		else
			return array();


	}

	public function tousLesAutoMessages()
	{
		$query = $this->_db->prepare('SELECT id, titre FROM automessages');
		$query->execute();

		$donnees = $query->fetchAll();

		return $donnees;
	}
}