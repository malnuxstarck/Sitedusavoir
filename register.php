<?php

  include "./includes/session.php";
  $titre = "Inscription | SiteduSavoir.com";
  require_once("./includes/identifiants.php");
  require_once("./includes/debut.php");
  require_once("./includes/menu.php");
  require_once("./includes/fonctions.php");


  spl_autoload_register("chargerClass");


  echo '<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li>Inscription</li>
         </ul>
  </div>';


  if ($id != 0)
  { 
      erreur(managerMembre::ERR_IS_CO);
  }

  if (empty($_POST['pseudo'])) // Si on la variable est vide, on peut considérer qu'on est sur la page de formulaire
  {

    echo '<div class="page">
              <h1 class="titre">Inscription</h1>
              <div class="formulaire">
              
                 <form method="post" action="" enctype="multipart/form-data" id="formulaire">
          
                        <div class="input">
                              <label for="pseudo"><img src="images/icones/person.png"></label>
                              <input type="text" name="pseudo" placeholder="Votre pseudo(Sans Espace,3 a 15 caracteres)*" required />
                         </div>
                         <div class="input">
                              <label for="password"><img src="images/icones/mdp.png"></label>
                              <input type="password" name="password" placeholder="Votre mot de passe*" required />
                         </div>
                         <div class="input">
                              <label for="confirm"><img src="images/icones/mdp.png"></label>
                              <input type="password" name="confirmPassword" placeholder="confirmation de mot de passe*" required />
                         </div>
                         <div class="input">
                              <label for="email"><img src="images/icones/mail.png"></label>
                              <input type="email" name="email" placeholder="Votre email (utilisé pour confirmation de compte)*" required />
                         </div>
                         <div class="input">
                              <label for="avatar"></label>
                              <input type="file" value="Avatar" name="avatar" placeholder="votre Avatar"/>
                         </div>
                         <div class="submit">
                             
                              <input type="submit" value="Inscrire"/>
                         </div>
                         <p class="apresformulaire" style="font-family:Gadugi;">
                             Les champs contenant (*) sont obligatoires , mais vous n\'etes pas obligé de fournir un avatar , il vous serra donnés un unique".
                             Un avatar de moins d\'un mega pixel dans le cas contraire .
                         </p>
                </form>
            </div>
        </div>';

        include "./includes/footer.php";
  }
  else
  {
  
      $membreAInscrire = new Membre($_POST);
      $token = $membreAInscrire->str_random(60);
      $membreAInscrire->setToken($token);

      $membreAInscrire->setAvatar($_FILES["avatar"]);
      $managerMembre = new ManagerMembre($bdd);

      $managerMembre->pseudoLibre($membreAInscrire->pseudo());
      $managerMembre->pseudoValide($membreAInscrire->pseudo());

      $managerMembre->verifyPassword($membreAInscrire);
      $managerMembre->emailValide($membreAInscrire->email());


      if ($managerMembre->verifAvatar($membreAInscrire->avatar()))
      {   
          $nomavatar = $membreAInscrire->moveAvatar($membreAInscrire->avatar());
          $membreAInscrire->setAvatar($nomavatar);
      }
      else
      {
          $pseudo = $membreAInscrire->pseudo();
          $nomavatar = $membreAInscrire->createAvatar($pseudo);
          $membreAInscrire->setAvatar($nomavatar);
      }

  
      if ($managerMembre->nombresErreurs == 0)
      {
             
        $managerMembre->inscription($membreAInscrire);

        echo'<h1>Inscription terminée</h1>';
        echo'<p> Bienvenue '.stripslashes(htmlspecialchars($membreAInscrire->pseudo())).', vous êtes maintenant inscrit sur le Site du savoir</p>
             <p>Cliquez <a href="./index.php">ici</a> pour revenir à la page d\'accueil</p>';

        $_SESSION['flash']['success'] = "Un mail de confirmation vous a été envoyé" ;

      }
      else
      {

        echo'<h1>Inscription interrompue</h1>';
        echo'<p>Une ou plusieurs erreurs se sont produites pendant l inscription</p>';
        
        echo'<p>'.$managerMembre->nombresErreurs.' erreur(s)</p>';

        $errors = $managerMembre->errors();
        
        foreach($errors as $erreur)
        {
            echo '<p>'.$erreur.'</p>';
        }
         echo'<p> Cliquez <a href="./register.php">Ici</a> pour recommencer</p>';
      }

      
  }

  

?>
</div>
</body>
</html>
