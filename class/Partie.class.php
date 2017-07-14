
<?php


// les differentes parties d'un contenu ,soit article , soit tuto


class Partie
{
	protected $_id ;
    protected $_idcontenu ;
    protected $_titre ;
    protected $_texte ;

    /* le constructeur */

    public function __construct(array $donnees)
    {
    	$this->hydrate($donnees);
    }

    public function id()
    {
    	return $this->_id ;
    }

    public function idcontenu()
    {
    	return $this->_idcontenu ;
    }

    public function titre()
	{
		return $this->_titre;
	}

	public function texte()
	{
		return $this->_texte;
	}


	/** Les setters */

	public function setId($id)
    {
        $id = (int)$id;

        if(is_int($id) AND $id > 0)
    	    $this->_id = $id ;
    }

    public function setTitre($titre)
	{
		$this->_titre = $titre ;
	}

	public function setTexte($texte)
	{
		$this->_texte = $texte ;
	}

	public function setIdcontenu($idcontenu)
    {
        $idcontenu = (int)$idcontenu;

        if(is_int($idcontenu) AND $idcontenu > 0)
    	    $this->_idcontenu = $idcontenu ;
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