<?php

  require './includes/identifiants.php';
  require './includes/session.php';
  require './includes/fonctions.php';
  
  spl_autoload_register('chargerClass');

  $id = $_GET['id'];
  $token = $_GET['token'];
  

  $managerMembre = new ManagerMembre($bdd);
  $donnees = $managerMembre->infosMembre($id);

  $membre = new Membre($donnees);

  
  if($membre AND $membre->token() == $token)
  {

    $_SESSION['flash']['success'] = "Votre compte a été validé, vous pouvez vous connecter maintenant.";

    $managerMembre->confirmerCompte($membre);

    header('Location:connexion.php');
  }
  else
  {
    header('Location:connexion.php');

    $_SESSION['flash']['danger'] = "Ce token n'est plus valide !!";

  }
  
