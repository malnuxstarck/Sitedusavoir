<?php


// Permet de travailler sur un forum

class Forum
{
	protected $_id;
	protected $_cat;
	protected $_name;
	protected $_description;
	protected $_ordre;
	protected $_topics;
	protected $_posts;
	protected $_last_post_id;
	protected $_auth_view;
	protected $_auth_post;
	protected $_auth_modo;
	protected $_auth_annonce;
	protected $_auth_topic;

	/* Le constrcteur */

	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
	}

    /* Les getters */

    public function id()
    {
    	return $this->_id;
    }

    public function cat()
    {
    	return $this->_cat;
    }

    public function name()
    {
    	return $this->_name;
    }

    public function description()
    {
    	return $this->_description ;
    }
    public function ordre()
    {
    	return $this->_ordre ;
    }
    public function topics()
    {
    	return $this->_topics;
    }
    public function posts()
    {
    	return $this->_posts ;
    }
    public function last_post_id()
    {
    	return $this->_last_post_id ;
    }
    public function auth_view()
    {
    	return $this->_auth_view ;
    }
    public function auth_post()
    {
    	return $this->_auth_post ;
    }
    public function auth_modo()
    {
    	return $this->_auth_modo ;
    }
    public function auth_annonce()
    {
    	return $this->_auth_annonce ;
    }
    public function auth_topic()
    {
    	return $this->_auth_topic ;
    }

    /* les setters */

    public function setId($id)
    {
        $id = (int)$id;
    	$this->_id = $id;
    }

    public function setCat($categorie)
    {
    	$this->_cat = $categorie;
    }

    public function setname($nomC)
    {
    	$this->_name = $nomC;
    }

    public function setDescription($description)
    {
    	$this->_description = $description ;
    }
    public function setOrdre($ordre)
    {
    	$this->_ordre = $ordre ;
    }
    public function setTopics($topics)
    {
        $topics = (int)$topics;
    	$this->_topics = $topics;
    }
    public function setPosts($posts)
    {
        $posts = (int)$posts;
    	$this->_posts = $posts ;
    }
    public function setLast_post_id($postId)
    {
    	$this->_last_post_id = $postId ;
    }
    public function setAuth_view($droitView)
    {
        $droitView = (int)$droitView;
    	$this->_auth_view = $droitView;
    }
    public function setAuth_post($droitPost)
    {
        $droitPost = (int)$droitPost;
    	$this->_auth_post = $droitPost ;
    }
    public function setAuth_modo($droitModerer)
    {
    	$this->_auth_modo = $droitModerer ;
    }
    public function setAuth_annonce($droitAnnonce)
    {
    	$this->_auth_annonce = $droitAnnonce ;
    }
    public function setAuth_topic($droitSujet)
    {
    	$this->_auth_topic = $droitSujet ;
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