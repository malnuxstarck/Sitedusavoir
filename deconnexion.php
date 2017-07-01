<?php

  include './includes/session.php';
  include './includes/fonctions.php';
  spl_autoload_register("chargerlass");
  
  session_destroy();

  unset($_SESSION['flash']);
  unset($_COOKIE['souvenir']);

  setcookie('souvenir',NULL,time()-1);
 
  include './includes/identifiants.php';
  include_once './includes/debut.php';
  include_once('./includes/menu.php');

  $ip

  $query=$bdd->prepare('DELETE FROM whoisonline WHERE online_id = :id');
  $query->bindValue(':id',$id,PDO::PARAM_INT);
  $query->execute();
  $query->CloseCursor();

  $req = $bdd->prepare('UPDATE membres SET cookie = NULL WHERE membre_id=:id');
  $req->execute(array('id'=>$id));
  $req->CloseCursor();

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
