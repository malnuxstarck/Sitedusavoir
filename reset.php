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
            

        header('Location: ./index.php');
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

<h1> Réinitialisez votre mot de passe </h1>

<form action="" method="POST">

  <div class="form-group">
    <label for="pseudo">Mot de passe</label>
    <input type="password" name="password" class="form-control" />
  </div>

  <div class="form-group">
    <label for="confirmation">Confirmation de mot de passe </label>
    <input type="password" name="confirmation" class="form-control" />
  </div>  
	
  <button type="submit" class="btn btn-primary"> Envoyer </button>
        
</form>
