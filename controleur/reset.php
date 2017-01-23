<?php 
  
  include "./includes/session.php";
  require_once '../modele/includes/identifiants.php';
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
 

  if(isset($_GET['id'])  && isset($_GET['token']))
  {
		
    require "../modele/reset.php";

    if($user)
    {

      if(!empty($_POST) && $_POST['password'] == $_POST['confirmation'])
      {

        $password = PASSWORD_HASH($_POST['password'],PASSWORD_BCRYPT);
        $id = $user['membre_id'];

        miseAjourMdp($id,$password, $bdd);

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

  include "../vue/reset.php";
?>

