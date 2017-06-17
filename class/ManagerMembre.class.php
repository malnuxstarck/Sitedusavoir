<?php

/* La class qui va gerer un membre , tout ce que peut faire un membre */

class ManagerMembre
{
	// Les constantes de la classe 

    const ERR_IS_CO =  'Vous etes deja connecter';
    const ERR_IS_NOT_CO =  'Vous devez d\'abord vous connecter ';
    const VALIDE = 1 ;
    const NON_VALIDE = 0;

	protected $_db;
	protected $_errors = array();
	protected $nombresErreurs = 0;

	public function __construct(PDO $db)
	{
		$this->setDb($db) ;
	}
	
	public function setDb($db)
	{
		$this->_db = $db ;
	}
 
	public function pseudoLibre($pseudo)
	{
		$req = $this->_db->prepare("SELECT COUNT(*) 
			                 AS nbr 
			                 FROM membres 
			                 WHERE pseudo = :pseudo");
		$req->bindValue(":pseudo",$pseudo,PDO::PARAM_STR);
		$req->execute();
		$nombresMembres = $req->fetchColumn();

		if($nombresMembres != 0)
		{
			$this->nombresErreurs++;
			$this->_errors["pseudo1"] = "Votre pseudo est dejas pris , nous sommes desolé ";
			return NON_VALIDE ;
		}

		return VALIDE;

	}

	public function pseudoValide($pseudo)
	{
		if (strlen($pseudo) < 3 || strlen($pseudo) > 15  || !preg_match('#^[a-zA-Z0-9_]+$#',$pseudo))
        {
               $this->_errors["pseudo2"] = "Votre pseudo est soit trop grand, soit trop petit, soit il ne respecte les caracteres autorisées";
               $this->nombresErreurs++;
               return NON_VALIDE;
        }

        return VALIDE ;
	}

	public function emailValide($email)
	{
		$query = $this->_db->prepare('SELECT COUNT(*) AS nbr FROM membres WHERE membre_email =:mail');
        $query->bindValue(':mail',$email, PDO::PARAM_STR);
        $query->execute();
        $nombresEmails = $query->fetchColumn();

        if($nombresEmails != 0 || empty($email) || !filter_var($email,FILTER_VALIDATE_EMAIL))
        {
        	$this->nombresErreurs++;
        	$this->_errors["mail1"] = " Votre email est soit deja prise , soit ne respecte pas le format ";
        	return NON_VALIDE ;
        }

        return VALIDE;
	}

    public function verifyPassword(Membre $membreAInscrire)
    {
    	$password = $membreAInscrire->password();
    	$confirmPassword = $this->confirmPassword();

    	if($password != $confirmPassword || empty($confirmPassword) || empty($password))
        {
            $this->_errors["password"] = "Votre mot de passe et votre confirmation diffèrent, ou sont vides";
           $this->nombresErreurs++;
        }

        else{

	  	   $pass = PASSWORD_HASH($pass,PASSWORD_BCRYPT);
	       $membreAInscrire->setPassword($pass);
	       $membreAInscrire->setConfirmPassword(NULL);
        }
    }


    public function verifAvatar($avatar)
    {
		if (!empty($avatar['size']))
		  {
		    //On définit les variables :

		    $maxsize = 54000; //Poid de l'image
		    $maxwidth = 180; //Largeur de l'image
		    $maxheight = 180; //Longueur de l'image

		    $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp' ); 
		    //Liste des extensions valides

		    if ($avatar['error'] > 0)
		    {
			      $this->_errors["avatar1"] = "Erreur lors du transfert de l'avatar : ";
			      $this->nombresErreurs++;
		    }

		    if ($avatar['size'] > $maxsize)
		    {
		        $nombresErreurs++;
		        $this->_errors["avatar2"] = "Le fichier est trop gros :(<strong>"$avatar['size']." Octets</strong> contre <strong>".$maxsize." Octets</strong>)";
		    }

		    $image_sizes = getimagesize($avatar['tmp_name']);

		    if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
		    {
			      $nombresErreurs++;
			      $this->_errors["avatar3"] = "Image trop large ou trop longue : (<strong>".$image_sizes[0]."x".$image_sizes[1]."</strong> contre
			      <strong>".$maxwidth."x".$maxheight."</strong>)";
		    }

		    $extension_upload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.') ,1));

		    if(!in_array($extension_upload,$extensions_valides) )
		    {
			      $nombresErreurs++;
			      $this->_errors["avatar4"] = "Extension de l'avatar incorrecte";
		    }

		  }
    }

    public function envoyerMail($email,$titre,$message)
    {
         mail($email,$titre,$message);
    }

	public function inscription(Membre $membreAInscrire)
	{


	}


	

}
