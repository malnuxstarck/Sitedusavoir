<?php

// Travailler sur l'amitier

class Ami
{
	protected $_fromt ; // representant la demande venant de 
	protected $_toa ; // reprensentant la demande en direction de */
	protected $_confirm ; // la confirmation 1: oui 0: non
	protected $_dateamitie ;
	protected $_idami; // contient l'id de l'ami ou son pseudo //

	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
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

	/* les setters */

	public function idami()
	{
		return $this->_idami;
	}

	public function fromt()
	{
		return $this->_fromt ;
	}

	public function toa()
	{
		return $this->_toa;
	}

	public function confirm()
	{
		return $this->_confirm ;
	}

	public function dateamitie()
	{
		return $this->_dateamitie;
	}



	/* les setters */

	public function setFromt($from)
	{
		$this->_fromt = $from ;
	}

	public function seTtoa($to)
	{
		$this->_toa = $to;
	}

	public function setConfirm($confirm)
	{
		$this->_confirm = $confirm;
	}

	public function setDateamitie($date)
	{
		$this->_dateamitie = $date;
	}

	public function setIdami($id)
	{
		$this->_idami = $id ;
	}


}