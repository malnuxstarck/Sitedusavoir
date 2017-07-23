<?php

class ManagerAuteur
{
	protected $_db;
	protected $_errors = array();
	public $nombreErreurs = 0;

	public function __construct(PDO $db)
	{
		$this->setDb($db);
	}

	public function setDb($db)
	{
		$this->_db = $db;
	}

	public function errors()
	{
		return $this->_errors ;
	}


	public function auteurInfos($pseudo)
	{
		$query = $this->_db->prepare('SELECT pseudo,membres.id as membre 
			                          FROM membres 
			                          WHERE LOWER(pseudo) = :pseudo LIMIT 1');

		$query->bindValue(':pseudo',strtolower($pseudo),PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetch();

		if(!empty($data))	
			return $data;
		else
			return array();    		
	}

    public function ajouterAuteur($auteur , $contenu)
    {
   	   $contenu = (int)$contenu;

   	   $query = $this->_db->prepare('INSERT INTO auteurs(membre , idcontenu)
      		                         VALUES(:membre , :contenu)');
      	$query->bindValue(':membre',$auteur,PDO::PARAM_INT);
      	$query->bindValue(':contenu',$contenu, PDO::PARAM_INT);
        $query->execute();

   }

   public function tousLesAuteurs($contenu)
   {
   	   $query = $this->_db->prepare('SELECT pseudo , idcontenu , membre FROM auteurs INNER JOIN membres ON membres.id = auteurs.membre WHERE idcontenu = :cont');
   	   $query->bindValue(':cont', $contenu , PDO::PARAM_INT);
   	   $query->execute();

   	   $donnees = $query->fetchAll();

   	   if(!empty($donnees))
   	   	    return $donnees;
   	   	else
   	   		return array();
   	   	
   }

}