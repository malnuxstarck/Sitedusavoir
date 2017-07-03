<?php

//Permet de travailler sur un message de forum dans un sujet

class Post
{
	protected $_id;     // id du message
	protected $_texte;  // le message lui meme
	protected $_posttime; // la date du postage
	protected $_forum;    // le forum ou il a été poster
	protected $_topic;    // le sujet ou il a été poster
	protected $_createur;  // l'id du createur du message

	/* le constructeur */

	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
	}


	/* les differents getters pour les attributs */

	public function id()
	{
		return $this->_id;
	}

	public function texte()
	{
		return $this->_texte;
	}

	public function posttime()
	{
		return $this->_posttime;
	}

	public function forum()
	{
		return $this->_forum;
	}

	public function topic()
	{
		return $this->_topic;
	}

	public function createur()
	{
		return $this->_createur;
	}

	/* les setters des attributs */


	public function setId($id)
	{
		$this->_id = $id ;
	}

	public function setTexte($texte)
	{
		$this->_texte = $texte ;
	}

	public function setPosttime($timeP)
	{
		$this->_posttime = $timeP ;
	}

	public function setForum($forum)
	{
		$this->_forum = $forum ;
	}

	public function setTopic($topic)
	{
		$this->_topic = $topic ;
	}

	public function setCreateur($createur)
	{
		$this->_createur = $createur ;
	}


	public function hydrate(array $donnees)
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