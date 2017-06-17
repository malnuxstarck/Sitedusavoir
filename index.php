<?php
  include "includes/identifiants.php";
	include 'includes/fonctions.php';
	include ('includes/session.php');
?>
<!DOCTYPE html>
<html>
<head>
  <title>Site du Savoir | SiteduSavoir.com </title>
  <meta charset="UTF-8/">
  <meta name="description" content="Site du Savoir , Une nouvelle communauté de programmeur sympatique, partages de connaissances et astuces"/>
  <meta name="author" content="MalnuxStarck"/>
  <link rel="stylesheet" href="css/style.css" type="text/css"/>
  <link rel="icon" href="sitedusavoir.png" type="image/png"/> 
</head>
  <?php
      if(isset($_SESSION['level'],$_SESSION['id'],$_SESSION['pseudo']))
      {
        $lvl = (int)$_SESSION['level'];
        $id = (int)$_SESSION['id'];
        $pseudo = $_SESSION['pseudo'];
      }
      else
      {
        $lvl = 1;
        $id = 0;
        $pseudo = '';
      }

      include ('includes/constantes.php');
      include_once('includes/menu.php');
      include_once('includes/identifiants.php');
      reconnected_from_cookie();

      /* Qui est en ligne */
      
      $ip = ip2long($_SERVER['REMOTE_ADDR']);
//Requête
      $query = $bdd->prepare('INSERT INTO whosonline VALUES(:id,NOW(),:ip) ON DUPLICATE KEY UPDATE online_time = NOW() , online_id = :id');
      $query->bindValue(':id',$id,PDO::PARAM_INT);
      $query->bindValue(':ip', $ip, PDO::PARAM_INT);

      $query->execute();
      $query->CloseCursor();




$query=$bdd->prepare('DELETE FROM whosonline WHERE online_time < SUBDATE(NOW(),INTERVAL 5 MINUTE)');

$query->execute();
$query->CloseCursor();

  ?>

  <div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li>
         </ul>
  </div>
        <div class="page">
              <h1 class="titre"> Bienvenue sur le Site du Savoir </h1>
              <div class="presentation">
                    <span class="top-btn"><a href="./forum/"> Forum </a> </span>
                    <p class="presentation-text">
                      Le Forum est la partie la plus communataire . 
                      elle permet au membre du site d'y discuter , chercher de l'aide ,
                      proposer des modifications , signaler des bugs.Plusieurs forum y sont present:
                      On en trouve un forum dedies pour les jeux videos , un forum general , et 
                      un forum dedie a l'informatique.
                       
                    <p>
              </div>
              <div class="presentation">
                    <span class="top-btn"><a href="./tutoriels/"> Tutos </a></span>
                    <p class="presentation-text">
                      Venez nous apprendre des nouvelles choses , que vous soyez informaticien ou non,
                      vous possedez peut etre un domaine que vous maitrisez , venez nous en faire profiter.
                      Partager votre savoir et aider les autres a s'ameliorer.
                       
                    <p>
              </div>
              <div class="presentation">
                    <span class="top-btn"><a href="./blog/"> Blog </a> </span>
                    <p class="presentation-text">
                      Le blog est une autre partie du site tres importante.
                      Elle permet de vous tenir au courant de l'activites du site.
                      Entre autres les nouvelles fonctionnalites apporte , les propositions d'amelioration,
                      et notament les sortis de s versions du sites
                       
                    <p>
              </div>
              <div class="presentation">
                    <span class="top-btn"><a href="./social/"> Social </a> </span>
                    <p class="presentation-text">
                     Social,Reseau social tres basique , sans aucune pretention , vous pouvez faires des 
                     statuts , des publications , partages votre humeurs vos photos .Vous pouvez creer des groupes ,
                     les administrer , ajouter d'autres administrateurs , publier du contenu
                       
                    <p>
              </div>

        </div>
     <?php
         include  "./includes/footer.php";
      ?>

</body>
</html>