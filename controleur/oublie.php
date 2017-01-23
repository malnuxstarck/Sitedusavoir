<?php require_once '../modele/includes/identifiants.php';
      include_once './includes/debut.php';
      include("./includes/constantes.php");
      include ("../modele/includes/debut.php");
      $balises=(isset($balises))?$balises:0;
      if($balises)
      {
        include('../../vue/includes/debut.php');
      }
      include_once '../vue/includes/menu.php';
      include_once './includes/menu.php';
      include_once './includes/fonctions.php';
      include_once '../modele/includes/fonctions.php';
 





  if(!empty($_POST)  && !empty($_POST['email']))
  {    
     require '../modele/oublie.php';

    if($user)
    {
    		
      $token = str_random(60);
      $id = $user['membre_id'];
      resetTakeToken($id, $token, $bdd);

      mail($_POST['email'],"Réinitialisation de votre mot de passe",
                           "Cliquez sur le lien ou copier coller dans votre navigateur :\n\n http://www.sitedusavoir.com/controleur/reset.php?id={$user['membre_id']}&token=$token","SiteduSavoir.com");

      $_SESSION['flash']['success'] = "Les instructions de rappel de mot de passe sont envoyées. Ils sont valables 30 minutes.";

      header('Location: connexion.php');
    }
    else
    {
      $_SESSION['flash']['danger'] = "Aucune email ne correspond a cette adresse ou vous n'avez pas confirmer votre compte";
      header('Location:oublie.php');
    }
  }


    include '../vue/oublie.php';
?>    	



