<!DOCTYPE html>
<html>
<head>
     <meta charset="utf-8"/>
	<title>Site du savoir | Site de l'amitié</title>
	<meta name="author" content="MalnuxStrck"/>
	<meta name="description" content="site de partages de connaissances"/>
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="icon" href="logo.png"/>

</head>
<?php include_once('includes/menu.php');
    
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
              <a href="register.php"> Nouveau , Inscivez Vous</a>
          </p>
        
    </aside>

    <div id="arianepresentation">

			    <section id="fildariane">
			         <i> Vous etes ici --> <a href="index.php">  Accueil </a></i>			    
			    </section>



			    <section id="presentation">
          
			                <h1> SDS KEZAKO ? </h1>
			                <p>
			                    SDS ou plus communement Site Du Savoir est un site cumunautaire. Il a pour but de regrouper les informaticiens ( debutants , intermediaires , experts, confirmés) afin de partager nos Experiences , nos savoirs faire et nos astuces.
			                </p>

			                <h1 id="but"> De quoi est composé SDS </h1>

			                <p> 

			                   <a href="#">Extras</a> : Section Pour  l'ensemble des tutos hors informatiques. </br>
			                   <a href=""/>Tutoriels</a> Pour les tutos informatiques  Astuces(Programmation,Conception, partages experiences).</br>
                         <a href="./Forum">Forum </a> Pour tous les problemes ou pour se presenter. </br> <a href="">Social</a> pour se retrouver et discuter avec des inconnus une sorte de superchat globale.

			                </p>

			    </section>
     </div>

     <div class="reste">

     </div>
    
    <footer>
          <p> Voir mon site web perso <a href="http://malnuxstarck.alwaysdata.net">Malnux Starck </a></p>
          <p> Site du savoir Copyright &copy; 2016 Tous droits reservés </p>
    

   </footer>

  
     
    
         </div>
</body>
</html>