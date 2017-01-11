
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8"/>
  	<title>Site du Savoir | SiteduSavoir.com</title>
  	<meta name="author" content="MalnuxStrck"/>
  	<meta name="description" content="site de partages des connaissances,site d'apprentissage , On est tous la bienvenue chez le site du savoir"/>
  	<link rel="stylesheet" type="text/css" href="./vue/css/style.css"/>
  	<link rel="icon" href="logo.png"/>
  </head>
         
  <?php
        
    if ($id)
    {
          include "./modele/index.php";

      echo '<aside id="aconnexion">
              <h2 id="connexion">'.$data['membre_pseudo'].' </h2>

              <div id="avatar">
                <img src="images/avatars/'.$data['membre_avatar'].'" alt="Pas davatar"/>
              </div>

              <p><a href="mailto:'.$data['membre_email'].'">'.$data['membre_email'].'</a><p>

              <p> <a href="membre/voirmonprofil.php?id='.$id.'"> Voir son profil </a></p>

              <p> <a href="membre/editerprofil.php?id='.$id.'">Editer profil </a></p>
              <p> <a href="deconnexion.php"> Se deconnecter </a> </p>
            </aside>';
    }
    else{
  ?>

  <aside id="aconnexion">
    <h2 id="connexion"> Connexion </h2>
    <form id="seconnecter" method="POST" action="connexion.php">
      <p>
         <input type="text" name="pseudo" placeholder="Pseudo"/>
      </p>
      <p>
        <input type="password" name="password" placeholder="Mot de passe"/>
      </p>
      <p>
        <input type="checkbox" name="souvenir"/> Se souvenir de moi
      </p>
      <p>
        <input type="submit" value="Se connecter"/>
      </p>
    </form>

    <p id="oubli">
      <a href="oublie.php">Mot de passe perdu </a>
    </p>

    <p id="nouveau">
      <a href="register.php"> Nouveau ? Inscrivez Vous</a>
    </p>

  </aside>

  <?php
    }
  ?>

  <div class="arianepresentation">
  	<section class="fildariane">
  		<i> Vous etes ici --> <a href="index.php"> Accueil </a></i>
  	</section>

  	<section class="presentation">
  		<h1> SDS KEZAKO ? </h1>
  	  <p>
  			SDS ou plus communement Site Du Savoir est un site communautaire. Il a pour but de regrouper les informaticiens (debutants, intermediaires, experts, confirmés) afin de partager nos Experiences, nos savoirs faire et nos astuces.
  		</p>

  		<h1 class="but"> De quoi est composé SDS </h1>

  		<p>
  			<a href="#">Extras</a> : Section Pour l'ensemble des tutos hors informatique. </br>
  			<a href=""/>Tutoriels</a> Pour les tutos informatiques  Astuces(Programmation,Conception, partages experiences).</br>
        <a href="./Forum">Forum </a> Pour tous les problemes ou pour se presenter. </br> <a href="">Social</a> pour se retrouver et discuter avec des inconnus une sorte de superchat global.
  		</p>
  	</section>
  </div>

  <div class="reste">

 
  </div>

  <footer>
    <p> Site du savoir Copyright &copy; 2016 Tous droits reservés </p>
  </footer>


</body>
</html>
