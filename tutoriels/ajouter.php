
<?php

$titre="Ajout (Partie Ou Membre) | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

$action = (isset($_GET['action']))?$_GET['action']:"";
$tuto = (isset($_GET['tuto']))?$_GET['tuto']:"";

if(empty($action) || empty($tuto))
{
	$_SESSION['flash']['success'] = "Aucune action et/ou tuto selectionner";
	header('Location:index.php');
}

switch($action)
{
	case "auteur":
    ?>
      <h2 class="titre"> Ajouter un auteur </h2>

    <form action="" method="POST">
          <div class="input">
               <input type="text" name="auteur" required>
          </div>

          <div class="input">
               <input type="submit" value="Ajout Auteur" required>
          </div>

    </form>

   <?php

   break;

   case "partie":
 
   	?>
   	<h2 class="titre"> Ajouter une partie </h2>
   	 <form action="" method="POST">
  
	   	   <div class="">
	   	        <input type="text" name="partie"/>
	   	   </div>

	   	   <div class="">
	   	        <textarea class="textarea">Le contenu de la partie</textarea>
	   	   </div>

	   	   <div class="input">
               <input type="submit" value="Ajout Partie" required>
          </div>
   	   </form>

   <?php

   break;
  
}