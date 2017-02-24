<?php require_once './includes/identifiants.php'; ?>
<?php include_once './includes/debut.php';
      include_once './includes/menu.php';
      include_once './includes/fonctions.php';
 ?>



<?php

  if(!empty($_POST)  && !empty($_POST['email']))
  {    
    $req = $bdd->prepare('SELECT * FROM membres WHERE membre_email = :email AND membre_inscrit IS NOT NULL');

    $req->execute(array('email' => $_POST['email']));

    $user = $req->fetch();

    if($user)
    {
    		
      $token = str_random(60);

      $req = $bdd->prepare('UPDATE membres SET reset = :token , reset_at = NOW() WHERE membre_id = :id');

      $req->execute(array(
        'token'=> $token,
        'id'  => $user['membre_id'] 
      ));

      mail($_POST['email'],"Réinitialisation de votre mot de passe",
                           "Cliquez sur le lien ou copier coller dans votre navigateur :\n\n http://www.sitedusavoir.com/reset.php?id={$user['membre_id']}&token=$token","SiteduSavoir.com");

      $_SESSION['flash']['success'] = "Les instructions de rappel de mot de passe sont envoyées.";

      header('Location: connexion.php');
    }
    else
    {
      $_SESSION['flash']['danger'] = "Aucune email ne correspond a cette adresse";
      header('Location:oublie.php');
    }
  }
?>    	


<div class="page">

<h1 class="titre"> Mot de passe oublier </h1>

<div class="formulaire">
    <form action="" method="POST">

      <div class="input">
         <label for="email"><span><img src="images/icones/mail.png" /></label>
         <input type="email" name="email" placeholder="Votre email (Pour verification)" />
     </div>

     <div class="submit">
           <input type="submit" class="btn btn-primary" value="Renouveler"/>
     </div> 
      </form>
  </div>  
</div>

<?php include"./includes/footer.php"; ?>