<?php

class Configuration
{
	protected $_nom ;
	protected $_valeur;

	/* constructeur */

	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
	}

	/* getters */

	public function nom()
	{
		return $this->_nom;
	}

	public function valeur()
	{
		return $this->_valeur;
	}

	/** Les setters **/

	public function setNom($nom)
	{
		$this->_nom = $nom;
	}

	public function setValeur($valeur)
	{
		$this->_valeur = $valeur;
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