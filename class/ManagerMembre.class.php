<?php

/* La class qui va gerer un membre , tout ce que peut faire un membre */

class ManagerMembre
{
	const PSEUDO_LIBRE = 1 ;
	const PSEUDO_OCCUPE = 2 ;
	const MAIL_IBRE = 3 ;
	const MAIL_OCCUPE = 4 ;
	const
	protected $db;

	public function __construct(PDO $db)
	{
		$this->setDb($db);
	}

	public function setDb(PDO $db)
	{
		$this->db = $db ;
	}

	public function inscription()
	{


	}

	public function connexion()
	{

	}

	public function infos($info)
	{
		if (is_int($info))
		{
			$req = $this->$db->prepare('SELECT * FROM membres WHERE membre_id = :info');
			$req->bindValue(':info' , $info , PDO::PARAM_INT);
		}
		else
		{
		   $req = $this->$db->prepare('SELECT * FROM membres WHERE membre_pseudo = :info');	
		   $req->bindValue(':info' , $info , PDO::PARAM_STR);
		}

			
            $req->execute();
            $donnees = $req->fetch();

            return $donnees ;

    }

	public function verifPseudo()
	{

	}

	public function pseudoLibre()
	{

	}

	public function confirmerCompte()
	{

	}

	public function changeMdp()
	{

	}

	public function verifMail()
	{

	}

	public function resetMDP()
	{
		
	}

}