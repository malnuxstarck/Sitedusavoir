
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

}