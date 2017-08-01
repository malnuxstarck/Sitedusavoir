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
		 	foreach ($resultats as $key => $value) {

		 		var_dump($value);
		 		/*
		 		switch ($key) {

		 			case 'topic':
		 				foreach ($value as $topicDonnee) {

		 					$topic = new Topic($topicDonnee);

		 					echo '<div class = "sujet">';

                            echo '<h1>'.$topic->titre().'</h1>
                                       <p>'.$topic->topictime().'<a href="forum/voirtopic.php?t='.$topic->id().'">Voir le sujet</a><p>';

		 					echo '</div';         

		 					
		 				}

		 				break;

		 				case 'post':
		 				break;

		 				case 'contenus':
		 				break;

		 			
		 			default:
		 				# code...
		 				break;

		 		}
		 		*/
		 	}
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
