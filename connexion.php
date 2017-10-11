<?php
  include './includes/session.php';
  $titre="Connexion";
  include_once './includes/identifiants.php';
  include_once'./includes/debut.php';

  $managerMembre = new ManagerMembre($bdd);

  include_once './includes/menu.php';

  echo '<ul class="fildariane">
    <li><a href="../index.php">Accueil</a></li>
    <li><span>Connexion</span></li>
  </ul>';

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
       $membre = $managerMembre->connexion($_POST);

       if($membre)
       {
            header("Location:index.php");
       }
       else
       {
           $errors = $managerMembre->errors();

           foreach ($errors as $erreur) {

             echo '<p>'.$erreur.'</p>';
           }

           echo '<p>
                     Cliquez <a href="./connexion.php">ici</a> pour revenir à la page précédente, cliquez <a href="./index.php">ici</a> pour revenir à la page d\'accueil
                   </p>';
       }
  }

