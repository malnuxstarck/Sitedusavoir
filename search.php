<?php
  include "./includes/session.php";
  $titre = "Recherche | SiteduSavoir.com";
  require_once("./includes/identifiants.php");
  require_once("./includes/debut.php");
  require_once("./includes/menu.php");


	$managerSearch = new ManagerSearch($bdd);

	if (isset($_GET['q']) AND !empty($_GET['q']))
	{
		$motChercher  = $_GET['q'];
		$resultats = $managerSearch->search($motChercher);

	    if(empty($resultats))
	    {
	    	echo '<p> Aucun resultats trouvees </p>';
	    	echo '<div class="formulaire"/>
			<form method="GET" action="search.php">
			                   <div class="form-content">
			                        <div class="input">
			                            <label for="q"><img src="images/icones/Search.png"></label>
			                            <input type="text" name="q" placeholder="Mot a rechercher">
			                        </div>
			                        <div class="submit">
			                           <input type = "submit" value="Rechercher"/>
			                      </div>
			                   </div>
			             </form>
		     </div>';
		 }else
		 { 
		 	print_r($resultats);
		 }

	}
	else
	{
		echo '<p> Le champs est vides , reesayer</p>

        <div class="formulaire"/>
			<form method="GET" action="search.php">
			                   <div class="form-content">
			                        <div class="input">
			                            <label for="q"><img src="images/icones/Search.png"></label>
			                            <input type="text" name="q" placeholder="Mot a rechercher">
			                        </div>
			                        <div class="submit">
			                           <input type = "submit" value="Rechercher"/>
			                      </div>
			                   </div>
			             </form>
		</div>';
	}

include "./includes/footer.php";
