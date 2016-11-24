<?php

session_start();

$titre = "Editer profil | Sitedusavoir.com";


require_once("../includes/identifiants.php");

require_once("../includes/debut.php");

require_once("../includes/menu.php");



echo '<p><i>Vous êtes ici</i> : <a href="../index.php"> Accueil </a> --> Editer profil de '.$pseudo ;

$identifiant = (isset($_GET['id']))?$_GET['id'] :0;

if ($id == 0)
{ 
	
   header('Location:../index.php');
}



if ($id != $identifiant)
{ 
	
   header('Location:./editerprofil.php?id='.$id);
}




if (empty($_POST)) // Si on la variable est vide, on peutconsidérer qu'on est sur la page de formulaire
{
  
        echo '<h1 class="titre">Modification Infos : '.$pseudo .'</h1>';


       echo'<form method="post" action="" enctype="multipart/form-data" id="formulaire">

             
              <p>
                <label for="pseudo">* Pseudo </label> 
                <input name="pseudo" type="text" id="pseudo" required />(doit contenir entre 3 et 15 caractères, sans espace) 
              </p>

              <p>  
                   <label for="password">* Mot de Passe </label>
                   <input type="password" name="password" id="password" required/> 
              </p>

              <p>
                  <label for="confirm">* Confirmer le mot de passe </label>
                  <input type="password" name="confirm" id="confirm" required/>
              </p>


              <p>
                  <label for="email">* Votre adresse Mail </label>
                  <input type="text" name="email" id="email" required/>
              </p>

              <p>
                  <label for="signature">Votre signature </label>
                  <textarea name="signature" placeholder="Votre signature"></textarea>
              </p>

               <p>
                  <label for="avatar">Choisissez votre avatar </label>
                  <input type="file" name="avatar" id="avatar"/>(Taille max : 1mo)
              </p>

     

      <p>Les champs précédés d un * sont obligatoires</p>
      <p><input type="submit" value="Modifier"/></p>

    </form>';
 

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
	$email =  $_POST['email'];
    $pass =   $_POST['password'];
    $confirm = $_POST['confirm'];

	
  $query = $bdd->prepare('SELECT COUNT(*) AS nbr FROM membres WHERE membre_pseudo =:pseudo');

	$query->bindValue(':pseudo',$pseudo, PDO::PARAM_STR);

	$query->execute();

	$pseudo_free=($query->fetchColumn() == 0)?1:0;

	$query->CloseCursor();

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

//Vérification du mdp
		if ($pass != $confirm || empty($confirm) || empty($pass))
		{
		  $mdp_erreur = "Votre mot de passe et votre confirmation diffèrent, ou sont vides";
		  $i++;
		}
		else
			$pass = PASSWORD_HASH($pass,PASSWORD_BCRYPT);

  
//Vérification de l'adresse email
//Il faut que l'adresse email n'ait jamais été utilisée

		$query = $bdd->prepare('SELECT COUNT(*) AS nbr FROM membres WHERE membre_email =:mail');
		$query->bindValue(':mail',$email, PDO::PARAM_STR);
		$query->execute();
		$mail_free=($query->fetchColumn() == 0)?1:0;

		$query->closeCursor();

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

		
	
			
		?>

   <?php
		//Vérification de l'avatar :
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
      			$avatar_erreur = "Erreur lors du transfert de
      			l'avatar : ";
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
         
              



			echo'<h1>Modification terminée</h1>

			<p>Cliquez <a href="./index.php">ici</a> pour revenir à la page d accueil</p>';

			//La ligne suivante sera commentée plus bas
			
			$nomavatar = (!empty($_FILES['avatar']['size']))?move_avatar($_FILES['avatar']):'';

			$query = $bdd->prepare('UPDATE membres (membre_pseudo,membre_mdp, membre_email, membre_avatar) VALUES (:pseudo, :pass, :email , :nomavatar)');

			$query->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
			$query->bindValue(':pass', $pass, PDO::PARAM_INT);
			$query->bindValue(':email', $email, PDO::PARAM_STR);
			
			
			$query->bindValue(':nomavatar', $nomavatar, PDO::PARAM_STR);

     
			
			$query->execute();
			//Et on définit les variables de sessions

          $_SESSION['flash']['success'] = "Votre Compte a bien eté Modifier " ;

			$query->CloseCursor();

	      }


	else
	{

		echo'<h1>Modification interrompue</h1>';
		echo'<p>Une ou plusieurs erreurs se sont produites pendant l
		incription</p>';
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

		echo'<p>Cliquez <a href="./editerprofil.php?id='.$id.'">ici</a> pour recommencer</p>';
	}


			




	
}



?>
</div>
</body>
</html>



