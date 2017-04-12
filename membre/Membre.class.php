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

	public function setDerniere_visite()
	{
		return $this->membre_derniere_visite;
	}

	public function setAvatar()
	{
		return $this->membre_avatar;
	}

	public function setInscrit()
	{
		return $this->membre_inscrit;
	}

	public function setSiteweb()
	{
		return $this->membre_siteweb;
	}

	public function setLocalisation()
	{
		return $this->membre_localisation;
	}

	public function setPost()
	{
		return $this->membre_post;
	}

	public function setToken()
	{
		return $this->membre_token;
	}

	public function setReset()
	{
		return $this->membre_reset;
	}

	public function setReset_at()
	{
		return $this->membre_reset_at;
	}

	public function setCookie()
	{
		return $this->membre_cookie;
	}

	public function setSignature()
	{
		return $this->membre_signature;
	}


}