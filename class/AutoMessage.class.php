

<?php

class AutoMessage
{
	protected $_id;
	protected $_message;
	protected $_titre;

	/* Le constructeur */

	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
	}

	/* les getters */ 

	public function id()
	{
		return $this->_id;
	}

	public function titre()
	{
		return $this->_titre ;
	}

	public function message()
	{
		return $this->_message;
	}

	/* les setters */

	public function setId($id)
	{
		$this->_id = $id;
	}

	public function setTitre($titre)
	{
		$this->_titre = $titre;
	}

	public function setMessage($message)
	{
		$this->_message = $message;
	}

	/* hydratation */

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