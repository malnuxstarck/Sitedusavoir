<?php

/*
 Qui est en ligne ? 
 */

 class WhoIsOnline
 {
 	protected $online_id;  // L'id de l'utilisateur en ligne , 0 pour un invité
 	protected $online_ip;  // Adresse Ip de l'utilisateur en ligne
 	protected $online_time ; // la date de sa connexion  


 	/* Constrcuteur de la classe */

 	public function __construct(array $donnees)
 	{
 		$this->hydrate($donnees);
 	}


    /* Les differents getters pour chaque Attributs */

 	public function online_id()
 	{
 		return $this->online_id;
 	}

 	public function online_ip()
 	{
 		return $this->online_ip;
 	}

 	public function online_time()
 	{
 		return $this->online_time;
 	}

 	/* les differents setters pour chaques attributs */

 	public function setOnline_id($id)
 	{
 		$this->online_id = $id ;

 	}

 	public function setOnline_ip($ip)
 	{
 		$this->online_ip = $ip ;

 	}

 	public function setOnline_time($time)
 	{
 		$this->online_time = $time ;

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