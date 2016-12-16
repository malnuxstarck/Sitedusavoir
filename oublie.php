
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
            'id'  => $user['id'] 
          ));

         mail($_POST['email'],"Réinitialisation de votre mot de passe","Cliquez sur le lien :\n\n http://www.sietdusavoir.com/reset.php?id={$user['id']}&token=$token");

    	
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



<h1> Mot de passe oublier </h1>

<form action="" method="POST">

    <div class="form-group">
          <label for="pseudo">Email</label>

         <input type="email" name="email" class="form-control" />
   </div>

   
   <input type="submit" class="btn btn-primary" value="Renouveler"/>
  

</form>
