<?php 
  
  include "./includes/session.php";
  require_once './includes/identifiants.php';
  include_once './includes/debut.php';

  include_once './includes/menu.php';
  include_once './includes/fonctions.php';

  if(isset($_GET['id'])  && isset($_GET['token']))
  {
      $managerMembre = new ManagerMembre($bdd);
      $token = $_GET['token'];
      $idUser = (int)$_GET['id'];

		  $infosMembre = $managerMembre->infosMembre($idUser);
      $membre = new Membre($infosMembre);

      $membreAjours = new Membre($_POST);
      $membreAjours->setID($membre->id());

      if(!empty($infosMembre) AND $membre->reset() == $token)
      {
          $managerMembre->verifyPassword($membreAjours);

          if(empty($managerMembre->errors())){

               $managerMembre->initialisationPassword($membreAjours);
               $_SESSION['flash']['success'] = "Mot de passe mis a jour, vous pouvez vous connecter a present";
               header('Location: ../index.php');
          }
          else
          {
              $message = $managerMembre->errors()['password'];
              $_SESSION['flash']['danger'] = $message;
              header('Location: ./oubli.php');
          }

            
            exit();
    }
    else
    {
      $_SESSION['flash']["danger"] = "Ce token n'est pas valide";
      header('Location: connexion.php');

      exit();
    }
  }
  else
  {
    $_SESSION['flash']['danger'] = "Maivaise URL , veiullez reessayer .";
    header('Location: connexion.php');
    exit();

  }


?>
<div class="page">

    <h1 class="titre"> Réinitialisez votre mot de passe </h1>
    <div class="formulaire">
      <form action="" method="POST">

        <div class="input">
          <label for="password"><span><img src="images/icones/mdp.png"/></span></label>
          <input type="password" name="password" required placeholder="Votre nouveau Mot de passe"/>
        </div>

        <div class="input">
          <label for="confirmPassword"><span><img src="images/icones/mdp.png"/></span> </label>
          <input type="password" name="confirmPassword" required placeholder="Confirmer le mot de passe" />
        </div>  
      	<div class="submit">
           <input type="submit" value="Envoyer" /> 
        </div>
      </form>
   </div>  
</div>

<?php include "./includes/footer.php"; ?>