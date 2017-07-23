
<?php

class ManagerConfiguration
{
	protected $_db;
	protected $_errors = array();
	public $nombreErreurs = 0;

	public function __construct(PDO $db)
	{
		$this->setDb($db);
	}

	public function setDb($db)
	{
		$this->_db = $db;
	}

	public function errors()
	{
		return $this->_errors ;
	}

	public function toutesLesConfigurations()
	{
		$query = $this->_db->query('SELECT nom, valeur FROM config');
		$donnees = $query->fetchAll();

		return $donnees;
	}

	public function miseAjoursConfiguration(Configuration $config)
	{
		$query = $this->_db->prepare('UPDATE config SET valeur = :val WHERE nom = :nom');
		$query->bindValue(':val' , $config->valeur() , PDO::PARAM_STR);
		$query->bindValue(':nom' , $config->nom() , PDO::PARAM_STR);
		
		$query->execute();
	}

}
