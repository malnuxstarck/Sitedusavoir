<?php

  include './includes/session.php';
  include './includes/identifiants.php';
  include_once './includes/debut.php';
  include_once('./includes/menu.php');
  
  session_destroy();

  unset($_SESSION['flash']);
  unset($_COOKIE['souvenir']);

  setcookie('souvenir',NULL,time()-1);
 
  

  $ip = ip2long($_SERVER['REMOTE_ADDR']);
  $memberDatas = array("online_id" => $id , "online_ip" => $ip);

  $memberOnline = new WhoIsOnline($memberDatas);
  $managerWhoIsOnline = new ManagerWhoIsOnline($bdd);

  $managerWhoIsOnline->deleteMembreOnline($id);
  $managerMembre = new ManagerMembre($bdd);

  $managerMembre->oublieMoi($id);

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


