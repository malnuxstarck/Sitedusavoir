<?php

/* La class qui va gerer un membre , tout ce que peut faire un membre */

class ManagerMembre
{
	const PSEUDO_LIBRE = 1 ;
	const PSEUDO_OCCUPE = 0 ;
	const MAIL_LIBRE =  1;
	const MAIL_OCCUPE = 0 ;
	const CONNECTE = 1;
	const NON_CONNECTE = 0 ;
	const COMPTE_VALIDE = 1 ;
	const COMPTE_NON_VALIDE = 0;
	protected $db;

	public function __construct(PDO $db)
	{
		$this->setDb($db);
	}

	public function setDb($db)
	{
		$this->db = $db ;
	}

	public function inscription(Membre $membre)
	{
	    $erreurs = array();
        




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


	public function pseudoLibre(Membre $membre)
	{
        $pseudo = $membre->pseudo();
        $req = $this->_db->prepare('SELECT COUNT(*) AS nbr FROM membres WHERE membre_pseudo = :pseudo');
        $req->bindValue(':pseudo' ,$pseudo , PDO::PARAM_STR);
        $req->execute();
        $nombrePseudos = $req->fetch();
        $nombrePseudos = $nombrePseudos['nbr'];

        if($nombrePseudos == 1)
            return ManagerMembre::PSEUDO_OCCUPE;
        else
            return ManagerMembre::PSEUDO_LIBRE;

	}

	public function pseudoValide(Membre $membre)
    {
        $pseudo = $membre->pseudo();
        $longeurP = strlen($pseudo);

        if($longeurP < 3 || $longeurP > 15 || !preg_match("#^[a-zA-Z0-9_]+$"))
            return 0;
        else
            return 1;
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