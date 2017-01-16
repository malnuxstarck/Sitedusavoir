<?php
  include './includes/session.php';
  
  session_destroy();

  unset($_SESSION['flash']);
  unset($_COOKIE['souvenir']);

  setcookie('souvenir',NULL,time()-1);

  include("../modele/includes/identifiants.php");
  include "../modele/deconnexion.php";
  
  include "./index.php";
  include "./includes/menu.php";

  if ($id == 0)
  {

    $_SESSION['flash']['danger'] = 'Vous devez etre connecté pour vous déconnecter.';
    header('Location:connexion.php');
  }

  else
  {
    $_SESSION['flash']['success'] = 'Vous êtes à présent déconnecté.';
    header('Location:connexion.php');

  }
 ?>