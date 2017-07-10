<?php


// Message privée

class Mp
{
	protected $_id;    // l'dentifiant du message //
	protected $_expediteur; // l'id de celui qui envoie le message //
	protected $_receveur;  // l'id de celui qui recoie le message  //
	protected $_lu;       // 0 : non -lu , 1: lu //
	protected $_mptime;   // la date de l'envoie //
	protected $_texte;     // le contenue du message //
	protected $_titre;     // le titre du message //
	protected $_to ; // contient le pseudo du receveur lors de l'envoie du formulaire */

	/* le constructeur */

	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
	}

	/* les getters */ 

	public function id()
	{
		return $this->_id ;
	}
    
    public function to()
    {
    	return $this->_to ;
    }
    
	public function expediteur()
	{
		return $this->_expediteur ;
	}

	public function receveur()
	{
		return $this->_receveur;
	}

	public function lu()
	{
		return $this->_lu;
	}

	public function mptime()
	{
		return $this->_mptime;
	}

	public function texte()
	{
		return $this->_texte ;
	}
	
	public function titre()
	{
		return $this->_titre ;
	}

	/* les setters */ 


	public function setId($id)
	{
		$this->_id = $id ;
	}

	public function setExpediteur($expediteur)
	{
		$this->_expediteur = $expediteur ;
	}

	public function setReceveur($receveur)
	{
		$this->_receveur = $receveur;
	}

	public function setLu($lu)
	{
		$this->_lu = $lu;
	}

	public function setMptime($temps)
	{
		$this->_mptime = $temps;
	}

	public function setTexte($texte)
	{
		$this->_texte =  $texte;
	}
	
	public function setTitre($titre)
	{
		$this->_titre  = $titre;
	}

	public function setTo($to)
	{
		$this->_to = $to ;
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