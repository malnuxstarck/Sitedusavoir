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
	
}