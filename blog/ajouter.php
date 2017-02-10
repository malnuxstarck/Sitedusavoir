
<?php

//Elle sert a ajouter un auteur a un tuto ou des parties

$titre="Ajout (Partie Ou Membre) | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

?>

<p id="fildariane"><i><a href="../index.php">Accueil </a>--><a href="./index.php"> Blog </a>-->Ajout Auteur/Partie </i></p>

<?php



	$action = (isset($_GET['action']))?$_GET['action']:"";
	$art = (isset($_GET['article']))?$_GET['article']:"";

	if(empty($action) || empty($art))
	{
		$_SESSION['flash']['success'] = "Aucune action et/ou article selectionner";
		header('Location:index.php');
	}

	switch($action)
	{
		case "auteur":
	    ?>
	      <h2 class="titre"> Ajouter un auteur </h2>

	    <form action="ajouterok.php?action=auteur&article=<?php echo $art;?>" method="POST">

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
	   	 <form action="ajouterok.php?action=partie&article=<?php echo $art;?>" method="POST">
	  
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

