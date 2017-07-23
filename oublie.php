
<?php 
      require_once './includes/identifiants.php';
      include_once './includes/debut.php';
      include_once './includes/menu.php';
      include_once './includes/fonctions.php';

  if(!empty($_POST)  && !empty($_POST['email']))
  {    
    $managerMembre = new ManagerMembre($bdd);
    $donneesUtilisateur = $managerMembre->infosMembreParEmail($_POST['email']);
    $membre = new Membre($donneesUtilisateur);

    if(!empty($donneesUtilisateur))
    {
        $token = $managerMembre->prepareInitialisationPassword($membre->id());
        $titreMessage = 'Réinitialisation de votre mot de passe';
        $message = 'Cliquez sur le lien ou copier coller dans votre navigateur :\n\n http://www.sitedusavoir.com/reset.php?id='.$membre->id().'&token='.$token ;

        $managerMembre->envoyerMail($membre->email() , $titreMessage , $message);
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