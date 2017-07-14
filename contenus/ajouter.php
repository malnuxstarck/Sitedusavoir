
<?php

//Elle sert a ajouter un auteur a un article ou des parties

$titre="Ajout (Partie Ou Auteur) | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

    $action = (isset($_GET['action']))?$_GET['action']:"";
	$idContenu = (isset($_GET['contenu']))?$_GET['contenu']:"";

	if(empty($action) || empty($idContenu))
	{
		$_SESSION['flash']['success'] = "Aucune action et/ou article selectionner";
		header('Location:./index.php');
	}


?>

<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Contenus</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="editioncontenu.php?contenu=<?php echo $idContenu;?>"> Edition Contenu</a></li><img class="fleche" src="../images/icones/fleche.png"/><li> <span style="color:black;">Auteur/Partie</span> </li>
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
		    <form action="ajouterok.php?action=auteur&contenu=<?php echo $idContenu;?>" method="POST">

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

		   	 <form action="ajouterok.php?action=partie&contenu=<?php echo $idContenu;?>" method="POST">
		  
			   	   <div class="input input-tuto">
			   	        <label for="titre"></label>
			   	        <input type="text" placeholder="Le titre de la partie" name="titre"/>
			   	   </div>

			   	   <div class="textarea textarea-tuto">
			   	        <textarea placeholder="Le Contenu de la partie" name="texte">Le contenu de la partie</textarea>
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

