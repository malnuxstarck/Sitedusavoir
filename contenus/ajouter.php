
<?php

//Elle sert a ajouter un auteur a un article ou des parties

$titre="Ajout (Partie Ou Membre) | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

    $action = (isset($_GET['action']))?$_GET['action']:"";
	$art = (isset($_GET['article']))?$_GET['article']:"";

	if(empty($action) || empty($art))
	{
		$_SESSION['flash']['success'] = "Aucune action et/ou article selectionner";
		header('Location:index.php');
	}


?>

<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Blog</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="editiontuto.php?tuto=<?php echo $art;?>"> Edition tuto</a></li><img class="fleche" src="../images/icones/fleche.png"/><li> <span style="color:black;">Auteur/Partie</span> </li>
         </ul>
  </div>
  
 <div class="page">

<?php



	

	switch($action)
	{
		case "auteur":
	    ?>
	      <h2 class="titre"> Ajouter un auteur </h2>
        <div class="formulaire formulaire-tuto">
		    <form action="ajouterok.php?action=auteur&article=<?php echo $art;?>" method="POST">

		          <div class="input input-tuto">
		               <label for="auteur"></label>
		               <input type="text" name="auteur" required/>
		          </div>

		          <div class="submit submit-tuto">
		               <input type="submit" value="Ajout Auteur" required/>
		          </div>

		    </form>
		 </div> 
		 </div>  
        
	   <?php
	   include "../includes/footer.php";

	   break;

	   case "partie":
	 
	   	?>
	   	<h2 class="titre"> Ajouter une partie </h2>
	   	<div class="formulaire formulaire-tuto">

		   	 <form action="ajouterok.php?action=partie&article=<?php echo $art;?>" method="POST">
		  
			   	   <div class="input input-tuto">
			   	        <label for="partie_titre"></label>
			   	        <input type="text" name="partie_titre"/>
			   	   </div>

			   	   <div class="textarea textarea-tuto">
			   	        <textarea  name="contenu">Le contenu de la partie</textarea>
			   	   </div>

			   	   <div class="submit submit-tuto">
		               <input type="submit" value="Ajout Partie" required>
		          </div>
		   	   </form>
        </div>
        </div>

	   <?php
	   include "../includes/footer.php";

	   break;
	  
	}

