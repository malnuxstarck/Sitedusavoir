<?php 
  
  include "./includes/session.php";
  require_once './includes/identifiants.php';
  include_once './includes/debut.php';

  include_once './includes/menu.php';
  include_once './includes/fonctions.php';
 ?>

<?php

  if(isset($_GET['id'])  && isset($_GET['token']))
  {
		
    $req = $bdd->prepare('SELECT * FROM membres WHERE membre_id = :id AND reset = :reset AND reset_at IS NOT NULL AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');

    $req->execute(array('id' => $_GET['id'], 'reset' => $_GET['token']));

    $user = $req->fetch();

    if($user)
    {

      if(!empty($_POST) && $_POST['password'] == $_POST['confirmation'])
      {
        $password = PASSWORD_HASH($_POST['password'],PASSWORD_BCRYPT);
        $req = $bdd->prepare('UPDATE membres SET membre_mdp = :pass, reset = NULL, reset_at = NULL WHERE membre_id = :id');
        $req->execute(array('pass'=> $password,'id'=> $user['membre_id']));

       

       $_SESSION['flash']['success'] = "Mot de passe mis a jour, vous pouvez vous connecter a present";
            

        header('Location: ../index.php');
        exit();
      }
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
          <label for="confirmation"><span><img src="images/icones/mdp.png"/></span> </label>
          <input type="password" name="confirmation" required placeholder="Confirmer le mot de passe" />
        </div>  
      	<div class="submit">
           <input type="submit" value="Envoyer" /> 
        </div>
      </form>
   </div>  
</div>

<?php include "./includes/footer.php"; ?>