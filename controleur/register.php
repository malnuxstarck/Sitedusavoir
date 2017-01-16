<?php

// les includes sont positionné par rapport a l'emplacment de ce fichier 

  include "./includes/session.php";
  $titre = "Inscription | SiteduSavoir.com";
  include("../modele/includes/identifiants.php");
  include_once "./includes/fonctions.php";

  include "../modele/includes/fonctions.php";
  require("./includes/debut.php");
  require_once("../vue/includes/menu.php");
  require_once("./includes/menu.php");

  if ($id != 0)
  { 
     erreur(ERR_IS_CO);
  }

  if(empty($_POST['pseudo']))
  {
  	 include ('../vue/register.php');
  }
  else
  {
		$pseudo_erreur1 = NULL;
		$pseudo_erreur2 = NULL;
		$mdp_erreur = NULL;

		$email_erreur1 = NULL;
		$email_erreur2 = NULL;
		$avatar_erreur = NULL;

		$avatar_erreur1 = NULL;
		$avatar_erreur2 = NULL;
		$avatar_erreur3 = NULL;

		$i = 0;

	    $pseudo = $_POST['pseudo'];
	    $email = $_POST['email'];
	    $pass = $_POST['password'];
	    $confirm = $_POST['confirm'];


	    

	    $pseudo_free = pseudoLibre($pseudo,$bdd);

	    if(!$pseudo_free)
	    {

	      $pseudo_erreur1 = "Votre pseudo est déjà utilisé par un membre";
	      $i++;
	    }

	    if (strlen($pseudo) < 3 || strlen($pseudo) > 15  || !preg_match('#^[a-zA-Z0-9_]+$#',$pseudo))
	    {

	      $pseudo_erreur2 = "Votre pseudo est soit trop grand, soit trop petit, soit il ne respecte les caracteres autorisées";
	      $i++;
	    }
	  
	  //Vérification du mot de passe
	    if ($pass != $confirm || empty($confirm) || empty($pass))
	    {
	      $mdp_erreur = "Votre mot de passe et votre confirmation diffèrent, ou sont vides";
	      $i++;
	    }

	    else
	       $pass = PASSWORD_HASH($pass,PASSWORD_BCRYPT);
	  
	       //Vérification de l'adresse email
	       //Il faut que l'adresse email n'ait jamais été utilisée

	    $mail_free = mailLibre($email,$bdd);

	    if(!$mail_free)
	    {
	    
	     $email_erreur1 = "Votre adresse email est déjà utilisée par un membre";
	     $i++;
	    }

	    //On vérifie la forme maintenant
	    if(empty($_POST['email']) || !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
	    {

	       $email_erreur2 = "Votre adresse E-Mail n'a pas un format valide";
	       $i++;
	    }

	    // Vérification de l'avatar :

	    if (!empty($_FILES['avatar']['size']))
	    {
	       //On définit les variables :
	       $maxsize = 100024; //Poid de l'image
	       $maxwidth = 180; //Largeur de l'image
	       $maxheight = 180; //Longueur de l'image

	       $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp' ); 
	        //Liste des extensions valides

	      if ($_FILES['avatar']['error'] > 0)
	      {
	         $avatar_erreur = "Erreur lors du transfert de l'avatar : ";
	      }

	      if ($_FILES['avatar']['size'] > $maxsize)
	      {
	         $i++;
	         $avatar_erreur1 = "Le fichier est trop gros :(<strong>".$_FILES['avatar']['size']." Octets</strong> contre <strong>".$maxsize." Octets</strong>)";
	      }

	      $image_sizes = getimagesize($_FILES['avatar']['tmp_name']);

	       if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
	       {
	         $i++;
	         $avatar_erreur2 = "Image trop large ou trop longue : (<strong>".$image_sizes[0]."x".$image_sizes[1]."</strong> contre
	         <strong>".$maxwidth."x".$maxheight."</strong>)";
	        }

	       $extension_upload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.') ,1));

	       if(!in_array($extension_upload,$extensions_valides) )
	       {
	         $i++;
	         $avatar_erreur3 = "Extension de l'avatar incorrecte";
	        }
	    }

	    if ($i==0)
	    {
	         
	      $token = str_random(60);

	      echo'<h1>Inscription terminée</h1>';
	      echo'<p>Bienvenue '.stripslashes(htmlspecialchars($_POST['pseudo'])).', vous êtes maintenant inscrit sur le Site du savoir</p>
	         <p>Cliquez <a href="../index.php">ici</a> pour revenir à la page d\'accueil</p>';
	    
	      //La ligne suivante sera commentée plus bas

	      $nomavatar = (!empty($_FILES['avatar']['size'])) ? move_avatar($_FILES['avatar']):'default.png';

	      include "../modele/register.php";

	      mail($email,"Confirmation de Votre compte","Cliquez sur le lien ou copiez le : \n\n http://www.sitedusavoir.com/controleur/confirm.php?id=$id&token=$token","SiteduSavoir.com");

	      $_SESSION['flash']['success'] = "Un mail de confirmation vous a été envoyé" ;

	      $query->CloseCursor();

	    }
	    else
	    {

		    echo'<h1>Inscription interrompue</h1>';
		    echo'<p>Une ou plusieurs erreurs se sont produites pendant l inscription</p>';
		    echo'<p>'.$i.' erreur(s)</p>';
		    echo'<p>'.$pseudo_erreur1.'</p>';

		    echo'<p>'.$pseudo_erreur2.'</p>';
		    echo'<p>'.$mdp_erreur.'</p>';
		    echo'<p>'.$email_erreur1.'</p>';

		    echo'<p>'.$email_erreur2.'</p>';
		    echo'<p>'.$avatar_erreur.'</p>';
		    echo'<p>'.$avatar_erreur1.'</p>';
				
		    echo'<p>'.$avatar_erreur2.'</p>';
		    echo'<p>'.$avatar_erreur3.'</p>';

		    echo'<p>Cliquez <a href="./register.php">ici</a> pour recommencer</p>';
	   }
  	}

?>