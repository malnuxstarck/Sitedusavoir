<?php

/* Pour les operations sur les categories */

class ManagerCategorie
{
	protected $_db;
	public $_errors;

	public function __construct(PDO $bdd)
	{
		$this->setDb($bdd);
	}

	public function setDb($bdd)
	{
		$this->_db = $bdd ;
	}
	
	public function infosCategorie($idCat)
	{
		$query = $this->_db->prepare("SELECT * FROM categories WHERE id = :idCat");
		$query->bindValue(':idCat',$idCat, PDO::PARAM_INT);
		$query->execute();
		$donnees = $query->fetch();

		if(!empty($donnees))
			return $donnees;
		else
			return array();
	}



	public function tousLesCategories()
	{
		$query = $this->_db->query('SELECT * FROM categories ORDER BY ordre DESC');
		$donnees = $query->fetchAll();

		if(!empty($donnees))
			return $donnees;
		else
			return array();
	}	

	public function nouvelleCategorie($cat)
	{
		$query = $this->_db->prepare('INSERT INTO categories (nom ,ordre) VALUES(:nom , NULL)');
		$query->bindValue(':nom',$cat->nom(), PDO::PARAM_STR);
		$query->execute();
	}

}