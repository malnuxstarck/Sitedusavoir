
<?php

$titre="Ajout (Partie Ou Membre) | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

?>

<p id="fildariane"><i><a href="../index.php">Accueil </a>--><a href="./index.php"> Tutoriels </a>-->Ajout Auteur/Partie </i></p>

<?php



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

	    <form action="ajouterok.php?action=auteur&tuto=<?php echo $tuto;?>" method="POST">

	          <div class="input">
	               <input type="text" name="auteur" required/>
	          </div>

	          <div class="input">
	               <input type="submit" value="Ajout Auteur" required/>
	          </div>

	    </form>

	   <?php

	   break;

	   case "partie":
	 
	   	?>
	   	<h2 class="titre"> Ajouter une partie </h2>
	   	 <form action="ajouterok.php?action=partie&tuto=<?php echo $tuto;?>" method="POST">
	  
		   	   <div class="">
		   	        <input type="text" name="partie_titre"/>
		   	   </div>

		   	   <div class="">
		   	        <textarea class="textarea" name="contenu">Le contenu de la partie</textarea>
		   	   </div>

		   	   <div class="input">
	               <input type="submit" value="Ajout Partie" required>
	          </div>
	   	   </form>

	   <?php

	   break;
	  
	}

