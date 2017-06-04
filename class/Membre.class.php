<?php

/* Definition de class Membre 
Objet Membre 
*/

class Membre
{
	protected $membre_id;
	protected $membre_pseudo;
	protected $membre_mdp;
	protected $membre_email;
	protected $membre_derniere_visite;
	protected $membre_avatar;
	protected $membre_inscrit;
	protected $membre_siteweb;
	protected $membre_signature;
	protected $membre_localisation;
	protected $membre_post;
	protected $membre_token;
	protected $membre_reset;
	protected $membre_reset_at;
	protected $membre_cookie;
	

	public function __construct(array $donnees)
	{
		$this->hydrate(array $donnees) ;
	}

	public function id()
	{
		return $this->membre_id;
	}

	public function pseudo()
	{
		return $this->membre_pseudo;
	}

	public function mdp()
	{
		return $this->membre_mdp;
	}

	public function email()
	{
		return $this->membre_email;
	}

	public function derniere_visite()
	{
		return $this->membre_derniere_visite;
	}

	public function avatar()
	{
		return $this->membre_avatar;
	}

	public function inscrit()
	{
		return $this->membre_inscrit;
	}

	public function siteweb()
	{
		return $this->membre_siteweb;
	}

	public function localisation()
	{
		return $this->membre_localisation;
	}

	public function post()
	{
		return $this->membre_post;
	}

	public function token()
	{
		return $this->membre_token;
	}

	public function reset()
	{
		return $this->membre_reset;
	}

	public function reset_at()
	{
		return $this->membre_reset_at;
	}

	public function cookie()
	{
		return $this->membre_cookie;
	}

	public function signature()
	{
		return $this->membre_signature;
	}

     /***********************************************************/
	/******              Les setteurs pour la classe     *******/
	/**********************************************************/

	public function setId($id)
	{
		if(is_int($id) AND $id > 0)
          $this->membre_id = $id ;
	}

	public function setPseudo($pseudo)
	{
		if(is_string($pseudo))
		   $this->membre_pseudo = $pseudo ;
	}

	public function setMdp($mdp)
	{
		if(is_string($mdp))
		   $this->membre_mdp = $mdp ;
	}

	public function setEmail($email)
	{
		if(filter_var($email,FILTER_VALIDATE_EMAIL))
		       $this->membre_email = $email ;
	}

	public function setDerniere_visite($visite)
	{
		 $this->membre_derniere_visite;
	}

	public function setAvatar($visite)
	{
		 $this->membre_avatar = $visite;
	}

	public function setInscrit($inscrit)
	{
		 $this->membre_inscrit = $inscrit ;
	}

	public function setSiteweb($site)
	{
		 $this->membre_siteweb = $site ;
	}

	public function setLocalisation($localisation)
	{
		 $this->membre_localisation = $localisation ;
	}

	public function setPost($post)
	{
		if($post >= 0)
		   $this->membre_post = $post ;
	}

	public function setToken($token)
	{
		/* Une cle de 60 caracteres genere pour laverification d'un compte  */
		 $this->membre_token = $token ;
	}

	public function setReset($reset)
	{
		/* Une autre cle de 60 caracteres mais cette fois lorsqu'on reinitialise son mdp */
		 $this->membre_reset = $reset ;
	}

	public function setReset_at($reset_at)
	{
		 $this->membre_reset_at = $reset_at ;
	}

	public function setCookie($cookie)
	{
		 $this->membre_cookie = $cookie ;
	}

	public function setSignature($signature)
	{
		 $this->membre_signature = $signature ;
	}
    
    public function hydrate(array $donnees)
    {
    	foreach ($donnees as $key => $value) {
    		 cherche si une mehode existe
    		$keys = explode('_',$key);
    		$method = 'set'.ucfirst($key[1]);

    		if(method_exists($this, $method))
    		{
    			$this->$method($value);
    		}
    	}
    }

}