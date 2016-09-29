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
   ?>
    
    <aside id="connexion">

        <h2> Connexion </h2>
        <form id="seconnecter" method="POST" action="connexion.php">
        	<p>
        	   <input type="text" name="pseudo"/>
        	</p>

        	<p>
                <input type="password" name="password"/>
        	</p>

        	<p>
        	   <input type="checkbox" name="souvenir"/> Se souvenir de moi </p> 
        	</p>

        	<p>
               <input type="submit" value="Se connecter"/>
        	</p>
        </form>
          <p>
              <a href="register.php"> Nouveau , Inscivez Vous</a>
          </p>
        
    </aside>

    <div id="fildariane">
         <i> Vous etes ici >> <a href="index.php">  Accueil </a> </i>
    </div>



    <section id="presentation">
                <h1> SDS KEZAKO ? </h1>
                <p>
                    SDS ou plus communement site du savoir est un site cumunautaire. Il a pour but de regrouper les informaticiens ( debutants , intermediaire , expert, confirmer) afin de partager nos Experiences , nos savoirs faire et nos astuces .
                </p>

                <h1 id="but"> De quoi est composé SDS </h1>

                <p>
                   EXTRAS : pour les cours hors informatiques) , Tutoriels(cours informatiques, et tutos) , Forum (en cas de problemes ) et Generales pour se retrouver et discuter avec des inconnus une sorte de superchat globale).

                </p>

    </section>

    <section id="reste" >
    </section>

    <footer>


    </footer>

  
     
    
       </div>
   </div>

</body>
</html>