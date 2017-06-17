<?php

/* Definition de class Membre 
Objet Membre 
*/

class Membre
{
	// Les differents rangs qu'un membre peut avoir

	const BANNI      = 0 ;
	const VISITEUR   = 1 ;
	const MEMBRE     = 2 ;
	const MODO       = 3 ;
	const ADMIN      = 4 ;

	// Les attributs d'un membre

    protected $_id ;
    protected $_pseudo ;
    protected $_password ;
    protected $_confirmPassword ;
    protected $_email ;
    protected $_localisation ;
    protected $_siteweb ;
    protected $_visite ;
    protected $_inscrit ;
    protected $_token ;
    protected $_reset ;
    protected $_reset_at ;
    protected $_signature ;
    protected $_posts ;
    protected $_rang ;
    protected $_avatar ;
    protected $_colkiee ;

    // le constructeur 

    public function __construct(array $membreA)
    {
    	$this->hydrate($membreA);
    }

    public function str_random($taille)
    {
    	$alphabet ="0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alphabet,$taille)) ,0, $taille);
    }


    // les getters  , qui renvoie la valeur d'un attribut 

    public function id()
    {
    	return $this->_id ;
    }
    
    public function pseudo()
    {
    	return $this->_pseudo ;
    }

    public function password()
    {
    	return $this->_password ;
    }

    public function confirmPassword()
    {
    	return $this->_confirmPassword ;
    }

    public function rang()
    {
    	return $this->_rang ;
    }

    public function email()
    {
    	return $this->_email ;
    }

    public function posts()
    {
    	return $this->_posts ;
    }

    public function avatar()
    {
    	return $this->_avatar ;
    }

    public function visite()
    {
    	return $this->_visite ;
    }

    public function token()
    {
    	return $this->_token ;
    }

    public function localisation()
    {
    	return $this->_localisation ;
    }

    public function cookiee()
    {
    	return $this->_colkiee ;
    }

    public function siteweb()
    {
    	return $this->_siteweb ; 
    }

    public function inscrit()
    {
    	return $this->_inscrit ;
    }

    public function signature()
    {
    	return $this->_signature ;
    }

    punlic function reset()
    {
    	return $this->_reset ;
    }

    public function reset_at()
    {
    	return $this->_reset_at ;
    }

    // Les setters , permetent de modifier le contenu ou la valeur d'un attribut ;


    public function setId($id)
    {
    	if (is_int($id) AND $id > 0)
    	{
    		$this->_id = $id ;

    	}
    }

    public function setPseudo($pseudo)
    {
       $this->_pseudo = $pseudo ;
    }


    public function setPassword()
    {
    	$this->_password = $password;
    }

    public function setConfirmPassword($confirmP)
    {
    	 $this->_confirmPassword = $confirmP;
    }

    public function setRang($rang)
    {
    	$this->_rang = $rang ;
    }

    public function setEmail($email)
    {
    	$this->_email = $email ;
    }

    public function setPosts($posts)
    {
    	 $this->_posts = $posts
    }

    public function setAvatar($avatar)
    {
    	 $this->_avatar = $avatar;
    }

    public function setVisite($visite)
    {
    	$this->_visite = $visite ;
    }

    public function setToken($token)
    {
    	$this->_token = $token ;
    }

    public function setLocalisation($localisation)
    {
    	 $this->_localisation = $localisation ;
    }

    public function setCookiee($cookiee)
    {
    	 $this->_colkiee = $cookiee ;
    }

    public function setSiteweb($siteweb)
    {
    	 $this->_siteweb = $siteweb ; 
    }

    public function setInscrit($inscrit)
    {
    	 $this->_inscrit = $inscrit ;
    }

    public function setSignature($signature)
    {
    	 $this->_signature = $signture ;
    }

    public function setReset($reset)
    {
    	 $this->_reset = $reset ;
    }

    public function setReset_at($date)
    {
    	 $this->_reset_at = $date ;
    }

    public function moveAvatar($avatar)
    {
        $extension = strtolower(substr( strrchr($avatar['name'],'.') ,1));

        $name = time();

        $nomavatar = str_replace(' ','',$name).".".$extension_upload;

        $name = "./images/avatars/".str_replace('','',$name).".".$extension_upload;

        move_uploaded_file($avatar['tmp_name'],$name);

       return $nomavatar;
    }


  public function verif_auth($auth_necessaire)
  {
     $level = (isset($_SESSION['level']))?$_SESSION['level']:1;
     return ($auth_necessaire <= intval($level));
  }


  public function hydrate(array $membreA)
  {
  	foreach ($membreA AS $cle => $contenu)
  	{
  		$method = 'set'.ucfirst($cle);
  		if(method_exists($this, $method))
  		{
  			$method($contenu);
  		}
  	}
  }



	

}
