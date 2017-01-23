<?php

  include "./includes/session.php";
  require '../modele/includes/identifiants.php';
  require '../modele/includes/fonctions.php';

  $id = $_GET['id'];
  $token = $_GET['token'];

  $user = getInfosById($id , $bdd);

  if($user AND $user['token'] == $token)
  {
    

    $_SESSION['flash']['success'] = "Votre compte a été validé, vous pouvez vous connecter maintenant.";
     
    require "../modele/confirm.php";
    setDerniereVisite($id, $bdd);
    header('Location:connexion.php');
  }
  else
  {
    header('Location:connexion.php');
    setToken($id , $bdd);
    $_SESSION['flash']['danger'] = "Mauvaises identifiants et/ou token invalides ";

  }
