<?php

/* La class qui va gerer un membre , tout ce que peut faire un membre */

class ManagerMembre
{
	// Les constantes de la classe 

    const ERR_IS_CO =  'Vous etes deja connecter';
    const ERR_IS_NOT_CO =  'Vous devez d\'abord vous connecter ';
    const VALIDE = 1 ;
    const NON_VALIDE = 0;
    const ERR_AUTH_ADMIN = "Acces restraint aux Administrateurs ";

	protected $_db;
	protected $_errors = array();
	public $nombresErreurs = 0;

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
		}

	}

	public function pseudoValide($pseudo)
	{
		if (strlen($pseudo) < 3 || strlen($pseudo) > 15  || !preg_match('#^[a-zA-Z0-9_]+$#',$pseudo))
        {
               $this->_errors["pseudo2"] = "Votre pseudo est soit trop grand, soit trop petit, soit il ne respecte les caracteres autorisées";
               $this->nombresErreurs++;
        }

	}

	public function emailValide($email)
	{
		$query = $this->_db->prepare('SELECT COUNT(*) AS nbr FROM membres WHERE email =:mail');
        $query->bindValue(':mail',$email, PDO::PARAM_STR);
        $query->execute();
        $nombresEmails = $query->fetchColumn();

        if($nombresEmails != 0 || empty($email) || !filter_var($email,FILTER_VALIDATE_EMAIL))
        {
        	$this->nombresErreurs++;
        	$this->_errors["mail1"] = " Votre email est soit deja prise , soit ne respecte pas le format ";
        }

        
	}

    public function verifyPassword(Membre $membreAInscrire)
    {
    	$password = $membreAInscrire->password();
    	$confirmPassword = $membreAInscrire->confirmPassword();

    	if($password != $confirmPassword || empty($confirmPassword) || empty($password))
        {
            $this->_errors["password"] = "Votre mot de passe et votre confirmation diffèrent, ou sont vides";
            $this->nombresErreurs++;
        }

        else{

	  	   $pass = PASSWORD_HASH($membreAInscrire->password(),PASSWORD_BCRYPT);
	       $membreAInscrire->setPassword($pass);
	       $membreAInscrire->setConfirmPassword(NULL);
        }
    }

    /*
    **@function retuourne true  si l'image est bien uploader , false si non;
    */


    public function verifAvatar($avatar)
    {
		  if (!empty($avatar['size']))
		  {
		    //On définit les variables :

		    $maxsize = 54000; //Poid de l'image
		    $maxwidth = 500; //Largeur de l'image
		    $maxheight = 500; //Longueur de l'image

		    $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp' ); 

		    //Liste des extensions valides

		    if ($avatar['error'] > 0)
		    {
			      $this->_errors["avatar1"] = "Erreur lors du transfert de l'avatar  ";
			      $this->nombresErreurs++;
		    }

		    if ($avatar['size'] > $maxsize)
		    {
		        $nombresErreurs++;
		        $this->_errors["avatar2"] = 'Le fichier est trop gros :(<strong>'.$avatar['size'].' Octets</strong> contre <strong>'.$maxsize.' Octets</strong>)';
		    }

		    $image_sizes = getimagesize($avatar['tmp_name']);

		    if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
		    {
			      $this->nombresErreurs++;
			      $this->_errors["avatar3"] = "Image trop large ou trop longue : (<strong>".$image_sizes[0]."x".$image_sizes[1]."</strong> contre
			      <strong>".$maxwidth."x".$maxheight."</strong>)";
		    }

		    $extension_upload = strtolower(substr(strrchr($avatar['name'], '.') ,1));

		    if(!in_array($extension_upload,$extensions_valides) )
		    {
			      $nombresErreurs++;
			      $this->_errors["avatar4"] = "Extension de l'avatar incorrecte";
		    }

		      return TRUE;

		  }
		  else
		  	 return FALSE;
    }

    public function inscription(Membre $membreA)
    {
    	$query = $this->_db->prepare("INSERT INTO membres(pseudo,password, email, avatar, inscrit,token) 
                                      VALUES(:pseudo, :pass, :email , :nomavatar, NULL, :token)");
    	$query->execute(array("pseudo" => $membreA->pseudo(),
    		                  "pass"   => $membreA->password(),
    		                  "email"  => $membreA->email(),
    		                  "nomavatar" => $membreA->avatar(),
    		                  "token"     => $membreA->token()));
    	$query->closeCursor();

    	$id = $this->_db->lastInsertId();
    	$token = $membreA->token();
    	
    	$membreA->setID($id);

    	$this->envoyerMail($membreA->email() , "Inscription sur le Site du Savoir","Cliquez ou copier le lien dans votre navigateur https://sitedusavoir.com/confirm.php?id=$id&token=$token");

    }

    public function envoyerMail($email,$titre,$message,$header="sitedusavoir.com")
    {
         mail($email,$titre,$message,$header);
    }

	public function errors()
	{
         return $this->_errors;
    }

    public function infosMembre($info,$dateInscription = 'IS NOT NULL')
    {
    	  $id = (int)$info;

    	  if($id == 0)
    	  {
    	  	    $query = $this->_db->prepare('
    	  	    	                          SELECT id,pseudo,password,siteweb,email,signature,avatar,localisation,token,cookiee,reset,reset_at,rang,posts,DATE_FORMAT(inscrit ,\'le %d-%m-%Y à %H h : %i min : %s secs\') AS inscrit , DATE_FORMAT(visite ,\'le %d-%m-%Y à %Hh:%imin:%s secs\') AS visite
    	  	    	                          FROM membres 
    	  	    	                          WHERE pseudo = :info AND inscrit '.$dateInscription);

    		    $query->bindValue(':info',$info,PDO::PARAM_STR);
    		    
          }
          else
          {
          	    $query = $this->_db->prepare('
          	    	                          SELECT id,pseudo,password,siteweb,email,avatar,signature,localisation,token,cookiee,reset,reset_at,rang,posts,DATE_FORMAT(inscrit ,\'le %d - %m - %Y à %H h : %i min : %s secs\') AS inscrit , DATE_FORMAT(visite ,\'le %d - %m - %Y à %H h : %i min : %s secs\') AS visite
    	  	    	                          FROM membres  
    	  	    	                          WHERE id = :info AND inscrit '.$dateInscription);

    		    $query->bindValue(':info',$info,PDO::PARAM_INT);

          }

            $query->execute();

            $donnees = $query->fetch();

            if(!empty($donnees))
                    return $donnees;
            else
                return array();    
    }

    /*
    **@function mise ajours infos
    **@param Membre
    **
    */


    public function miseAjoursMembre(Membre $membreAjour)
    {
    	$query = $this->_db->prepare('UPDATE membres SET pseudo = :pseudo ,email = :mail , password = :password , posts = :posts ,localisation = :localisation,signature = :signature , siteweb = :siteweb ,avatar = :avatar WHERE id = :id');

    	$query->execute(array(

    		    'id' => $membreAjour->id(),
    		    'pseudo' => $membreAjour->pseudo(),
    		    'password' => $membreAjour->password(),
    		    'mail' => $membreAjour->email(),
                'localisation' => $membreAjour->localisation(),
                'avatar' => $membreAjour->avatar(),
                'siteweb' => $membreAjour->siteweb(),
                'signature' => $membreAjour->signature(),
                'posts'     => $membreAjour->posts()
    		));

    	$query->closeCursor();
    }
    
    /*
    *@function confrirmerCompte
    **@param Membre
    ** Permet de confirmer un compte d'utilisateur
    */
  
    public function confirmerCompte(Membre $membre)
    {
        $query = $this->_db->prepare('UPDATE membres SET visite = NOW() ,inscrit = NOW() ,token = NULL 
        	                          WHERE id = :id');
        $query->bindValue(":id",$membre->id(),PDO::PARAM_INT);
        $query->execute();
    }

    /*
    **@function reconnected_from_cookie
    ** Reconnecte un utilisateur s'il a un cookie poster dans son navigateur
    */


    public function reconnected_from_cookie()
    {

		if(session_status() == PHP_SESSION_NONE)
		{
		    session_start();
		}


	    if(isset($_COOKIE['souvenir']) && !isset($_SESSION['id']))
	    {

	       $cookie = $_COOKIE['souvenir'];
	       $parts =  explode('==',$cookie);
	       $user_id = $parts[0];

	        
	       $donnees = $this->infosMembre($user_id);

	       $membre = new Membre($donnees);

	       if($membre)
	       {
	            $expected = $user_id.'=='.$membre->cookiee().sha1($membre->id().'MALNUX667');
	              
	            if($expected == $cookie)
	            {
	               
	                setcookie('souvenir',$cookie,time()+60*60*24*7);

	                $_SESSION['pseudo'] = $membre->pseudo();
	                $_SESSION['level'] = $membre->rang();
	                $_SESSION['id'] = $membre->id();

	        
	                $this->derniereVisite($membre->id());
	            }
	            else
	            {
	                  setcookie('souvenir',NULL,-1);
	            }


	        }

	        else
	        {
	          setcookie('souvenir',NULL,-1);
	        }



	     }
	}

	public function derniereVisite($id)
	{
		$query = $this->_db->prepare("UPDATE membres SET visite = NOW() WHERE id = :id");
		$query->bindValue(":id",$id, PDO::PARAM_INT);
		$query->execute();
	}

	public function souviensToiDeMoi($id ,$cookie)
	{
		$req = $this->_db->prepare("UPDATE membres
                                      SET cookiee = :cookie
                                      WHERE id = :id");

		$req->bindValue(':id',$id, PDO::PARAM_INT);
		$req->bindValue(':cookie',$cookie, PDO::PARAM_INT);
		$req->execute();

	}

	public function OublieMoi($id)
	{
		$query = $this->_db->prepare("UPDATE membres SET cookiee = NULL WHERE id = :id");
		$query->bindValue(':id' , $id ,PDO::PARAM_INT);
	}


	/*
	**@param array $donnees
	** retourne un membre si aucune erreur ne survienne , sinon renvoie NULL
	*/

	public function connexion(array $donnees)
	{


		if(empty($donnees['pseudo']) || empty($donnees['password']))
		{
			$this->_errors["champsVides"] = "Une erreure s'est produite vous devez remplir tous les champs";
			$this->nombresErreurs++;

		}
		else
		{
			$datas = $this->infosMembre($donnees["pseudo"]);
			$membreAEnvoyer = new Membre($datas);

			if(PASSWORD_VERIFY($donnees["password"] ,$membreAEnvoyer->password()))
			{
				if($membreAEnvoyer->rang() == 0 )
				{
					$this->nombresErreurs++;
				    $this->_errors["rang"] = "Vous avez été Banni du site , impossible de vous connecter sur ce site.";
				}
				else
				{
					if(!empty($donnees["souvenir"]))
					{
						$cookie = Membre::str_random(60);
						$this->souviensToiDeMoi($membreAEnvoyer->id() ,$cookie);
						setcookie('souvenir',$membreAEnvoyer->id().'=='.$cookie.sha1($membreAEnvoyer->id().'MALNUX667'),time() + 60 * 60 *24 *7 );

					}

					$_SESSION['pseudo'] = $membreAEnvoyer->pseudo();
    				$_SESSION['level'] = $membreAEnvoyer->rang();
    				$_SESSION['id'] = $membreAEnvoyer->id();
    				$_SESSION["flash"]["success"] = 'Bienvenue '.$membreAEnvoyer->pseudo().', vous êtes maintenant connecté ! ';

    				$this->derniereVisite($membreAEnvoyer->id());
				}		


			}
			else
			{
				$this->nombresErreurs++;
				$this->_errors["Motdepasse"] = "Mot de passe ou pseudo incorrecte veuillez reessayez";
			}
            
          
		}

        
		if($this->nombresErreurs != 0)
		{
			return NULL;
		}
		else
		{
			return $membreAEnvoyer;
		}
		
	}

	public function totalDesMembres()
	{
		$query = $this->_db->query('SELECT COUNT(*) AS nbr FROM membres');
		$donnees = $query->fetch();

		return $donnees["nbr"];
	}

	public function dernierInscrit()
	{
		$query = $this->_db->query('SELECT * FROM membres ORDER BY id DESC LIMIT 0, 1');
		$donnees = $query->fetch();

		return $donnees;
	}

	public function augmenterNombrePostMembre($idMembre)
	{
		$query = $this->_db->prepare('UPDATE membres SET posts = posts + 1 WHERE id = :id');

		$query->bindValue(':id', $idMembre, PDO::PARAM_INT);
		$query->execute();
		$query->CloseCursor();

	}


	public function diminuerNombrePostMembre($idMembre , $nbrePost = -1)
	{
		$query = $this->_db->prepare('UPDATE membres SET posts = posts + :nbr WHERE id = :id');

		$query->bindValue(':id', $idMembre, PDO::PARAM_INT);
		$query->bindValue(':nbr' , $nbrePost , PDO::PARAM_INT);
		$query->execute();
		$query->CloseCursor();

	}

	public function listeDesMembres($premier , $membreParPage , $champOrdre , $ordre)
	{
		$query = $this->_db->prepare('SELECT id, pseudo,inscrit, posts, visite, online_id 
                          FROM membres 
                          LEFT JOIN whoisonline 
                          ON online_id = id 
                          ORDER BY '.$champOrdre.' '.$ordre.' 
                          LIMIT :premier, :membreparpage');

        $query->bindValue(':premier',$premier,PDO::PARAM_INT);
        $query->bindValue(':membreparpage',$membreParPage, PDO::PARAM_INT);
        $query->execute();

        $datas = $query->fetchAll();

        return $datas;
	}

	public function infosMembreParEmail($email)
	{
		$req = $this->_db->prepare('SELECT * FROM membres WHERE email = :email AND inscrit IS NOT NULL');
        $req->execute(array('email' => $email));

        $user = $req->fetch();

        if(!empty($user))
               return $user;
        else
            return array();   
	}

	public function prepareInitialisationPassword($idM)
	{
		$token = Membre::str_random(60);
        $req = $this->_db->prepare('UPDATE membres SET reset = :token , reset_at = NOW() WHERE id = :id');

        $req->execute(array(
                            'token'=> $token,
                            'id'  => $idM 
      ));

        return $token;
	}

	public function initialisationPassword($membre)
	{
		$req = $this->_db->prepare('UPDATE membres SET password = :pass, reset = NULL, reset_at = NULL WHERE id = :id');
            $req->execute(array('pass'=> $membre->password(),'id'=> $membre->id()));
	}

	public function tousLesBannis()
	{
		$query = $this->_db->query('SELECT id, pseudo FROM membres WHERE rang = 0');
		$datas = $query->fetchAll();

		return $datas;
	}

	public function recupereLeMembre($pseudo)
	{
		$query = $this->_db->prepare('SELECT * FROM membres WHERE LOWER(pseudo) = :pseudo');
		$query->bindValue(':pseudo', strtolower($pseudo) , PDO::PARAM_STR);
		$query->execute();

		$data = $query->fetch();
		return $data;
	}

	public function promouvoirMembre(Membre $membre)
	{
		 $query = $this->_db->prepare('UPDATE membres 
                                  SET rang = :rang
                                  WHERE LOWER(pseudo) = :pseudo');

            $query->bindValue(':rang',(int)$membre->rang(), PDO::PARAM_INT);
            $query->bindValue(':pseudo',strtolower($membre->pseudo()), PDO::PARAM_STR);

            $query->execute();
            $query->CloseCursor();
	}

}
