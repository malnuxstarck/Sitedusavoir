
<?php

// aueteur d'un contenu

class Auteur
{
	protected $_membre;
	protected $_pseudo ;
	protected $_idcontenu ;

	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
	}

	public function membre()
	{
		return $this->_membre ;
	}

	public function pseudo()
	{
		return $this->_pseudo ;
	}

	public function idcontenu()
	{
		return $this->_idcontenu ;
	}

	public function setMembre($id)
	{
		$id = (int)$id ;
		$this->_membre = $id ;
	}

	public function setPseudo($pseudo)
	{
		$this->_pseudo = $pseudo ;
	}

	public function setIdcontenu($idC)
	{
		$this->_idcontenu = (int)$idC ;
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