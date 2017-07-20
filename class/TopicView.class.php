<?php

class TopicView
{
	protected $_tv_id ;
	protected $_tv_post_id;
	protected $_tv_topic_id;
    protected $_tv_poste;
	protected $_tv_forum_id;
	protected $_nbr_vu ;


	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
	}

	/* les getters */

	public function tv_id()
	{
		return $this->_tv_id;
	}

	public function tv_forum_id()
	{
		return $this->_tv_forum_id;
	}

	public function tv_topic_id()
	{
		return $this->_tv_topic_id;
	}

	public function tv_post_id()
	{
		return $this->_tv_post_id;
	}

	public function tv_poste()
	{
		return $this->_tv_poste;
	}

	public function nbr_vu()
	{
		return $this->_nbr_vu;
	}

	/* les setters */

	public function setNbr_vu($vu)
	{
		$this->_nbr_vu = $vu ;
	}

	public function setTv_id($id)
	{
	    $this->_tv_id = $id;
	}

	public function setTv_forum_id($forum)
	{
		$this->_tv_forum_id = $forum;
	}

	public function setTv_topic_id($topic)
	{
		$this->_tv_topic_id = $topic;
	}

	public function setTv_post_id($postid)
	{
		$this->_tv_post_id = $postid;
	}

	public function setTv_poste($poste)
	{
		$this->_tv_poste = $poste;
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