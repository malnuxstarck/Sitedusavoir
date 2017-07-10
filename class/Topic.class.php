<?php

// Classe permetant de travailler sur les sujets du forum

class Topic
{
	protected $_id;
	protected $_forum;
	protected $_titre;
	protected $_createur;
	protected $_posteur;
	protected $_vus;
	protected $_topictime;
	protected $_genre; // represente le type soit une annonce soit un simple message
	protected $_last_post;
	protected $_first_post;
	protected $_posts;
	protected $_locked;

	/* le constructeur */

	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
	}

	/* les getters */

	public function id()
	{
		return $this->_id;
	}
	public function forum()
	{
		return $this->_forum;
	}
	public function titre()
	{
		return $this->_titre;
	}
	public function createur()
	{
		return $this->_createur;
	}
	public function posteur()
	{
		return $this->_posteur;
	}
	public function vus()
	{
		return $this->_vus;
	}
	public function topictime()
	{
		return $this->_topictime;
	}
	public function genre()
	{
		return $this->_genre;
	} 
	public function last_post()
	{
		return $this->_last_post;
	}
	public function first_post()
	{
		return $this->_first_post;
	}
	public function posts()
	{
		return $this->_posts;
	}
	public function locked()
	{
		return $this->_locked;
	}

    /* les setters */

	public function setId($d)
	{
		$this->_id = $id ;
	}
	public function setForum($forum)
	{
		$this->_forum = $forum ;
	}
	public function setTitre($titre)
	{
		$this->_titre = $titre ;
	}
	public function setCreateur($createur)
	{
		$this->_createur = $createur;
	}
	public function setPposteur($posteur)
	{
		$this->_posteur = $posteur ;
	}
	public function setVus($vus)
	{
		$this->_vus = $vus;
	}
	public function setTopictime($time)
	{
		$this->_topictime = $time ;
	}
	public function setGenre($genre)
	{
		$this->_genre = $genre ;
	} 
	public function setLast_post($postId)
	{
		$this->_last_post = $postId .
	}
	public function setFirst_post($idPost)
	{
		$this->_first_post = $idPost ;
	}
	public function setPosts($nombresPosts)
	{
		$this->_posts = $nombresPosts ;
	}
	public function setLocked($lock)
	{
		$this->_locked = $lock ;
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