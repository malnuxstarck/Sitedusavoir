<?php


// Permet de travailler sur les articles du blog et les tutoriels

class Contenu
{
	protected $_id;
	protected $_type;
	protected $_cat;
	protected $_titre ;
	protected $_banniere;
	protected $_introduction;
	protected $_conclusion;
	protected $_validation;
	protected $_confirmation ;
	protected $_publication ; 

	/* le constructeur */

	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
	}

	/* les getters */

	public function id()
	{
		return $this->_id;
	}

	public function type()
	{
		return $this->_type;
	}

	public function titre()
	{
		return $this->_titre;
	}

	public function cat()
	{
		return $this->_cat;
	}

	public function banniere()
	{
		return $this->_banniere;
	}

	public function introduction()
	{
		return $this->_introduction;
	}

	public function conclusion()
	{
		return $this->_conclusion;
	}

	public function validation()
	{
		return $this->_validation;
	}

	public function confirmation()
	{
		return $this->_confirmation;
	}

	public function publication()
	{
		return $this->_publication;
	}

	/* les getters */

	public function setId($id)
	{
		$id = (int)$id;
		$this->_id = $id;
	}

	public function setType($type)
	{
		$this->_type = $type;
	}

	public function setTitre($titre)
	{
		$this->_titre = $titre ;
	}

	public function setCat($cat)
	{
		$cat = (int)$cat;
		$this->_cat = $cat ;
	}

	public function setBanniere($banniere)
	{
		$this->_banniere = $banniere ;
	}

	public function setIntroduction($introduction)
	{
		$this->_introduction = $introduction ;
	}

	public function setConclusion($conclusion)
	{
		$this->_conclusion = $conclusion ;
	}

	public function setValidation($validation)
	{
		$this->_validation = $validation ;
	}

	public function setConfirmation($confirmation)
	{
		$this->_confirmation = $confirmation ;
	}

	public function setPublication($datepublication)
	{
		$this->_publication = $datepublication ;
	}


	public function hydrate($donnees)
	{
		foreach ($donnees as $key => $value) {
			$method = 'set'.ucfirst($key);
			if(method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}



	

	


	
}