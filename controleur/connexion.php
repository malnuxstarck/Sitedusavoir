<?php


  include "./includes/session.php";
  $titre = "Connexion | SiteduSavoir.com";
  include("../modele/includes/identifiants.php");
  include"./includes/fonctions.php";        // Des fonctions qui font des verifications , donc controleur
  include "../modele/includes/fonctions.php";    // des fonctions qui font du SQL
  require("./includes/debut.php");
  include("./includes/constantes.php");
  include ("../modele/includes/debut.php");
  $balises=(isset($balises))?$balises:0;
  if($balises)
  {
    include('../../vue/includes/debut.php');
  }
  require("../vue/includes/menu.php");
  include("./includes/menu.php");
  
  echo '<p id="fildariane"><i>Vous êtes ici</i> : <a href="../index.php">Accueil </a> --> Connexion';

 
   if ($id != 0)
   { 
      erreur(ERR_IS_CO);
   }
    
     

   if(!isset($_POST['pseudo']))
   {
  	 include "../vue/connexion.php";
   }

   else
   {
	     $message='';

	  	if (empty($_POST['pseudo']) || empty($_POST['password']) )
	    {
	    		//Oublie d'un champ

	  	   $message = '<p>
	  	                  une erreur s\'est produite pendant votre identification. Vous devez remplir tous les champs.
	  	               </p>
	  	               <p>
	  	                  Cliquez <a href="./connexion.php">ici</a> pour revenir.
	  	               </p>';
	  	}

	  	else //On check le mot de passe
	  	{
	  		$data = getInfosUtilisateurs($_POST['pseudo'], $bdd);

	    	if(PASSWORD_VERIFY($_POST['password'],$data['membre_mdp']))
	        {
	    		if ($data['membre_rang'] == 0) //Le membre est banni
	    	    {
	               $message="<p>Vous avez été banni, impossible de vous connecter sur ce site. </p>";
	    	    }
	    	    else
	    	    {
	    	        if(isset($_POST['souvenir']))
	    	  	    {
	    	  	  	  $cookie = str_random(250);

	    	  		  cookieToken($cookie,$data['membre_id'],$bdd);
                      setcookie('souvenir',$data['membre_id'].'=='.$cookie.sha1($data['membre_id'].'MALNUX667'),time() + 60 * 60 *24 *7 );
	  	    	  }

	  				$_SESSION['pseudo'] = $data['membre_pseudo'];
	  				$_SESSION['level'] = $data['membre_rang'];
	  				$_SESSION['id'] = $data['membre_id'];

                    setDerniereVisite($data['membre_id'],$bdd);

	  				$message = '<p>Bienvenue '.$data['membre_pseudo'].', vous êtes maintenant connecté !</p>
	                            <p>Cliquez <a href="../index.php">ici</a> pour revenir à la page d accueil</p>';
	  	       }
	    	}

	    	else // Acces pas OK !
	    	{
	    		$message ='<p>
	    					  Une erreur s\'est produite pendant votre identification ou vous n\'avez pas confirmer votre compte. <p> Le mot de passe ou le pseudo entré n\'est pas correct.
	    				    </p>
	    				    <p>
	    				       Cliquez <a href="./connexion.php">ici</a> pour revenir à la page précédente, cliquez <a href="./index.php">ici</a> pour revenir à la page d\'accueil
	    		            </p>';
	    	}
	         $query->CloseCursor();
	  	}
	  	  echo $message.'</div></div></body></html>';
	}

	?>

	<input type="hidden" name="page" value="<?php
	  if(isset($_SERVER['HTTP_REFERER'])) 
	     echo $_SERVER['HTTP_REFERER']; ?>" />

	<?php

	  if(isset($_POST['page']))
	  {
	     $page = htmlspecialchars($_POST['page']);
	     echo 'Cliquez <a href="../'.$page.'">ici</a> pour revenir à la page précedente'; 
	   }
	  
