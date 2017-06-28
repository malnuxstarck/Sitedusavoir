<?php
  include './includes/session.php';
  $titre="Connexion";
  include_once './includes/identifiants.php';
  include_once'./includes/debut.php';
  include_once './includes/menu.php';
  include_once "./includes/fonctions.php";

  spl_autoload_register("chargerClass");

  $ManagerMembre = new ManagerMembre($bdd);

  echo '<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><span style="color:black;">Connexion</span></li>
         </ul>
  </div>';

  $managerMembre->reconnected_from_cookie();

	if ($id != 0)
	{

	echo '<div class="alert-danger">'. erreur(ManagerMembre::ERR_IS_CO). ' </div>';


	}



  if (!isset($_POST['pseudo'])) //On est dans la page de formulaire
  {
  	echo '<div class="page">
              <h1 class="titre">Connexion</h1>

             <div class="formulaire">

                <form method="post" action="connexion.php">

        				         <div class="input">
                              <label for="pseudo"><img src="images/icones/person.png" alt="p"></label>
                              <input type="text" name="pseudo" placeholder="Votre pseudo (Sans Espace,3 a 15 caracteres)" required />
                         </div>
                         <div class="input">
                              <label for="password"><img src="images/icones/mdp.png" alt="M"></label>
                              <input type="password" name="password" placeholder="Votre mot de passe" required />
                          </div>    
                          <div class="checkbox">
                                <input type="checkbox" name="souvenir"/><label>Se souvenir de moi </label>
                         </div>    
                         
                         <div class="submit">
                                <input type="submit" value="Connexion"/>
                         </div>
      			   </form>
             </div>
          <p class="apresformulaire">
  			    <a href="./register.php">Pas encore inscrit ?</a>
            <a href="oublie.php">Mot de passe oublier</a>
          </p>
        </div>';

       include './includes/footer.php';
  			
  		echo '</body>
  	</html>';
  }

  else
  {
      $membre = new Membre ($_POST);

      //Oublie d'un champ

  	  if (empty($_POST['pseudo']) || empty($_POST['password']) )
      {

  	   $message = '<p>
  	                 une erreur s\'est produite pendant votre identification. Vous devez remplir tous les champs.
  	               </p>
  	               <p>
  	                 Cliquez <a href="./connexion.php">ici</a> pour revenir.
  	               </p>';
  	  }

  	  else //On check le mot de passe
  	  {

          $membre = new Membre($_POST);
    	    $donnees =  $managerMembre->infosMembre($membre->pseudo());


    	  if(PASSWORD_VERIFY($membre->password(),$donnees['password']))
        {
      		   if ($donees['rang'] == 0) //Le membre est banni
      	     {
                 $message="<p>Vous avez été banni, impossible de vous connecter sur ce site. </p>";
      	     }
      	     else
      	     {
        	        if(isset($_POST['souvenir']))
        	  	    {

        	  		   $cookie = Membre::str_random(60);

        	  		   $req = $bdd->prepare('UPDATE membres
                                      SET cookie = :cookie
                                      WHERE membre_id = :id');
        	  		   $req->execute(array('cookie'=> $cookie, 'id' => $data['membre_id']));

      	    		setcookie('souvenir',$data['membre_id'].'=='.$cookie.sha1($data['membre_id'].'MALNUX667'),time() + 60 * 60 *24 *7 );
      	    	}

    				$_SESSION['pseudo'] = $data['membre_pseudo'];
    				$_SESSION['level'] = $data['membre_rang'];
    				$_SESSION['id'] = $data['membre_id'];

    				$requete = $bdd->prepare('UPDATE membres
                                      SET membre_derniere_visite = NOW()
                                      WHERE membre_id = :id');

    				$requete->bindValue(':id',$data['membre_id'],PDO::PARAM_INT);
    				$requete->execute();

    		  $_SESSION['flash']['success'] ='Bienvenue '.$data['membre_pseudo'].', vous êtes maintenant connecté ! ';
          header('Location:index.php');
                       
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
