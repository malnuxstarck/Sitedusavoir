<?php

/* Classe pour les categories */

class Categorie
{
	protected $_id;
	protected $_nom;
	protected $_ordre;

	/* les getters des attributs */

	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
	}

	public function id()
	{
		return $this->_id;
	}

	public function nom()
	{
		return $this->_nom;
	}

	public function ordre()
	{
		return $this->_ordre;
	}


	/* les setters */
    

    public function setId($id)
	{
		$id = (int)$id;
		$this->_id = $id;
	}

	public function setNom($nom)
	{
		$this->_nom = $nom ;
	}

	public function setOrdre($ordre)
	{
		$this->_ordre = $ordre;
	}

	public function hydrate(array $donnees)
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