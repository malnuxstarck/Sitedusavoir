<?php

class ManagerPartie
{
	protected $_db;   // PDO objet pour les oprations vers la bd
	protected $_errors = array();  // contient les messages d'erreurs
	public $_iErrors = 0 ;   // nombres d'erreurs
	
	public function __construct(PDO $db)
	{
		$this->setDb($db) ;
	}
	
	public function setDb($db)
	{
		$this->_db = $db ;
	}

	public function errors()
	{
		return $this->_errors;
	}

	public function donnerLaPartie($idPartie)
	{
		$query = $this->_db->prepare('SELECT * FROM parties WHERE id = :idpartie');
		$query->bindValue(':idpartie' , $idPartie, PDO::PARAM_INT);
		$query->execute();
		$donnees = $query->fetch();

		if(!empty($donnees))
		{
			return $donnees;
		}
		else
			return array();
	}

	public function toutesLesPartiesdeCeContenu($idContenu)
	{
		$query = $this->_db->prepare('SELECT * FROM parties
			                          WHERE idcontenu = :idC ORDER BY id');
		$query->bindValue(':idC' , $idContenu, PDO::PARAM_INT);
		$query->execute();
		$donnees = $query->fetchAll();

		if(!empty($donnees))
		{
			return $donnees;
		}
		else
			return array();

	}

	public function verifierChamps(Partie $partie)
	{
		if(empty($partie->texte()) || empty($partie->titre()))
		{
			$this->_errors["champs"] = "Le titre et/Ou Le contenu de la partie est vide .";
			$this->_iErrors++;
		}
	}

	public function ajouterPartie(Partie $partie)
	{
		$query = $this->_db->prepare('INSERT INTO parties(titre , texte , idcontenu) 
         	                          VALUES(:titre , :texte , :contenu)');

         $query->execute(array('titre' => $partie->titre() , 'contenu' => $partie->idcontenu(), 'texte' => $partie->texte()));
	}

	public function miseAjourPartie(Partie $partie)
	{
		$query = $this->_db->prepare('UPDATE parties SET titre = :titre,texte = :txt 
		   		                         WHERE id = :idPartie');

		$query->bindValue(':idPartie',$partie->id(),PDO::PARAM_INT);
		$query->bindValue(':titre',$partie->titre(),PDO::PARAM_STR);
		$query->bindValue(':txt',$partie->texte(),PDO::PARAM_STR);

		$query->execute();
	}

	public function deletePartie(Partie $partie)
	{
		$query = $this->_db->prepare('DELETE FROM parties WHERE id = :idP');
		$query->bindValue(':idP',$partie->id() , PDO::PARAM_INT);
		$query->execute();
	}
}	