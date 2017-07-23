<?php
include '../includes/session.php';
$titre="Administration| sitedusavoir.com";
$balises = true;

include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

// On indique ou l'on se trouve
$cat = (isset($_GET['cat']))?htmlspecialchars($_GET['cat']):'';
$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';

echo'<p id="fildariane"><i>Vous êtes ici</i> : <a href="../index.php">Acceuil</a> --> <a href="./index.php">Administration du forum</a>';


if (!Membre::verif_auth(ADMIN)) 
	   erreur(ManagerMembre::ERR_AUTH_ADMIN);


switch($cat) //1er switch
{ 
	case "config":
		//ici configuration

		//ici configuration
		echo'<h1>
		         Configuration du forum
		    </h1>';

		echo '<form method="post" action="adminok.php?cat=config">';

		//Le tableau associatif
		$config_name = array(
							"avatar_maxsize" => "Taille maximale de l avatar",
							"avatar_maxh" => "Hauteur maximale de l avatar",
							"avatar_maxl" => "Largeur maximale de l avatar",
							"sign_maxl" => "Taille maximale de la signature",
							"auth_bbcode_sign" => "Autoriser le bbcode dans la signature",
							"pseudo_maxsize" => "Taille maximale du pseudo",
							"pseudo_minsize" => "Taille minimale du pseudo",
							"topic_par_page" => "Nombre de topics par page",
							"post_par_page" => "Nombre de posts par page",
							"forum_titre" => "Titre du forum"
		                    );

        $managerConfiguration = new ManagerConfiguration($bdd);
        $donneesConfigs = $managerConfiguration->toutesLesConfigurations();
        
        foreach ($donneesConfigs as $donneesConfig) {

        	$config = new Configuration($donneesConfig);
      
				echo '<p>
						<label for='.$config->nom().'>
							'.$config_name[$config->nom()].'
						</label> :
						<input type="text" id="'.$config->nom().'"value="'.$config->valeur().'"
						name="'.$config->nom().'">
				    </p>';

		}

				echo '<p>
				       <input type="submit" value="Envoyer" />
				     </p>
				</form>';

		break;

	case "forum":
		//Ici forum

		if( isset($_GET['action']) )
		      $action = htmlspecialchars($_GET['action']); 

		      //On récupère la valeur de action

		if(isset($_GET['c']))
		{
		    $_GET['c'] = htmlspecialchars($_GET['c']);
		}

		    

		switch($action) //2eme switch
		{

				case "creer":



					//Création d'un forum
					//1er cas : pas de variable c

					if(empty($_GET['c']))
					{
							echo'<br />
							<br />
							<br />
							Que voulez-vous faire?
							<br />

							<a href="./index.php?cat=forum&action=creer&c=f">Créer un forum</a><br />

							<a href="./index.php?cat=forum&action=creer&c=c">Créer une catégorie</a></br>';
					}

					//2ème cas : on cherche à créer un forum (c=f)

					elseif($_GET['c'] == "f")
					{
							$managerCategorie = new ManagerCategorie($bdd);
							$donneesCats = $managerCategorie->tousLesCategories();

							echo'<h1>Création d un forum</h1>';

							echo'<form method="post" action="./adminok.php?cat=forum&action=creer&c=f">';

									echo'<label>Nom :</label>
									<input type="text" id="name" name="name" />
									<br />
									<br />
									<label>Description :</label><textarea cols=40 rows=4 name="description" id="desc"></textarea>
									<br /><br />

									<label>Catégorie : </label><select name="cat">';

                                    foreach ($donneesCats as $donneesCat ) {
                                    	$cat = new Categorie($donneesCat);

                                    	echo'<option value="'.$cat->id().'">
										           '.$cat->nom().'
										    </option>';
									}

									echo'</select>
									       <br />
									      <br />
									<input type="submit" value="Envoyer">
							    </form>';

				    }
							//3ème cas : on cherche à créer une catégorie (c=c)
					elseif($_GET['c'] == "c")
					{
							echo'<h1>Création d une catégorie</h1>';

							echo'<form method="post" action="./adminok.php?cat=forum&action=creer&c=c">';
							      echo'<label> Indiquez le nom de la catégorie
							:</label>
							     <input type="text" id="nom" name="nom" /><br /><br />
							   <input type="submit" value="Envoyer"></form>';
					}

					break;


			    case "edit":
					//Edition d'un forum
					echo'<h1>Edition d un forum</h1>';

					$managerCategorie = new ManagerCategorie($bdd);
					$donneesCats = $managerCategorie->tousLesCategories();

					$managerForum = new ManagerForum($bdd);
					$jointures = $managerForum->ajoutJointuresViews($id);
					$donneesForums = $managerForum->tousLesForums($jointures ,$id , $lvl);


					if(!isset($_GET['e']))
					{

						echo'<p>
									Que voulez vous faire ?<br />

									<p>
									    <a href="./index.php?cat=forum&action=edit&amp;e=editf">Editer un forum</a><br />
									</p>

									<p>
									    <a href="./index.php?cat=forum&action=edit&amp;e=editc">Editer une catégorie</a><br />
									</p>

									<p>
                                        <a href="./index.php?cat=forum&action=edit&amp;e=ordref">Changer l ordre des forums</a><br />
									</p>

									<p>
									     <a href="./index.php?cat=forum&action=edit&amp;e=ordrec">Changer l ordre des catégories</a>
									</p>

									<br />
						    </p>';

					}

					elseif($_GET['e'] == "editf")
					{
						
					//On affiche dans un premier temps la liste des forums
							if(!isset($_POST['forum']))
							{
									echo'<form method="post" action="index.php?cat=forum&amp;action=edit&amp;e=editf">';
											echo'<p>
													Choisir un forum :</br /></h2>

													<select name="forum">';
													foreach ($donneesForums as $donneesForum) {

														$forum = new Forum($donneesForum);
											
														echo'<option value="'.$forum->id().'">
														             '.stripslashes(htmlspecialchars($forum->name())).'
														   </option>';
													}

													echo'<input type="submit" value="Envoyer"/>
											    </p>
									    </form>';

									

							}

							//Ensuite, on affiche les renseignements sur le forum choisi

							else
							{
									$donneesForumR = $managerForum->infosForum($_POST['forum']);
									$forumR = new Forum($donneesForumR);

									echo'<p>
											Edition du forum
											<strong>'.stripslashes(htmlspecialchars($forumR->name())).'</strong>
									    </p>';

									echo'<form method="post" action="adminok.php?cat=forum&amp;action=edit&amp;e=editf">
									         <label>Nom du forum : </label>
									          <input type="text" id="nom" name="name" value="'.$forumR->name().'" />
									          <br />

										<label>Description :</label>
										<textarea cols=40 rows=4 name="description">'.$forumR->description().'</textarea>
										<br />
										<br />';

										//A partir d'ici, on boucle toutes les catégories,
										//On affichera en premier celle du forum

										echo'<label>Déplacer le forum vers : </label>
										<select name="cat">';

											foreach ($donneesCats as $donneesCat) {

												$cat = new Categorie($donneesCat);

												if($cat->id() == $forumR->id())
												{
													echo'<option value="'.$cat->id().'" selected="selected">'.stripslashes(htmlspecialchars($dcat->nom())).'
													    </option>';
												}

												else
												{
													echo'<option value="'.$cat->id().'">'.$cat->nom().'</option>';
												}
											}
										  echo'</select>

										  		<input type="hidden" name="id" value="'.$forumR->id().'"/>';

										  echo'<p>
										       <input type="submit" value="Envoyer"/>

										   </p>

									</form>';
							
							}

					}

					elseif($_GET['e'] == "editc")
					{
						//On commence par afficher la liste des catégories
						if(!isset($_POST['cat']))
						{
							echo'<form method="post" action="index.php?cat=forum&amp;action=edit&amp;e=editc">';
								    echo'<p>
									      Choisir une catégorie :</br />
									       <select name="cat">';

										foreach ($donneesCats as $donneesCat) {

												$cat = new Categorie($donneesCat);
                                                echo'<option value="'.$cat->id().'">'.$cat->nom().'</option>';
											}
										echo'<input type="submit" value="Envoyer">
									</p>
							    </form>';
							
						}

						//Puis le formulaire
						else
						{
							$donneesCat = $managerCategorie->infosCategorie((int)$_POST['cat']);
							$cat = new Categorie($donneesCat);

							echo'<form method="post" action="./adminok.php?cat=forum&amp;action=edit&amp;e=editc">';

							echo'<label> Indiquez le nom de la catégorie :</label>
							<input type="text" id="nom" name="nom" value="'.stripslashes(htmlspecialchars($cat->nom())).'" />
							<br /><br />
							<input type="hidden" name="id" value="'.$cat->id().'" />
							<input type="submit" value="Envoyer" /></p>
							</form>';
						}
					}


					elseif($_GET['e'] == "ordref")
					{

							$categorie="";
							echo'<form method="post" action="adminok.php?cat=forum&amp;action=edit&amp;e=ordref">';

									echo '<table>';

									foreach ($donneesForums as $donneesForum) {

										$cat = new Categorie($donneesForum);
										$cat->setId($donneesForum['idCat']);
										$cat->setOrdre($donneesForum['ordreCat']);
										$forum = new Forum($donneesForum);

										if( $categorie !== $cat->id())
										{
											$categorie = $cat->id();
											echo'
											<tr>
												<th>
												    <strong>'.stripslashes(htmlspecialchars($cat->nom())).'</strong>
												</th>

												<th>
												    <strong>Ordre</strong>
												</th>
											</tr>';
										}
										echo'<tr>
												<td>
												    <a href="./voirforum.php?f='.$forum->id().'">'.$forum->name().'</a>
												</td>

												<td>
												   <input type="text" value="'.$forum->ordre().'" name="'.$forum->id().'" />
												</td>
										    </tr>';
									}
									echo'</table>

									<p>
									     <input type="submit" value="Envoyer" />
									</p>
								</form>';
					}


					elseif($_GET['e'] == "ordrec")
					{

						echo'<form method="post" action="adminok.php?cat=forum&amp;action=edit&amp;e=ordrec">';

								foreach ($donneesCats as $donneesCat) {

									$cat = new Categorie($donneesCat);
								
									echo'<label>'.stripslashes(htmlspecialchars($cat->nom())).':</label>

									<input type="text" value="'.$cat->ordre().'"name="'.$cat->id().'" /><br /><br
									/>';
								}

								echo '<input type="submit" value="Envoyer" />

						    </form>';
					}

					break;

				   //Gestion des droits

			    case "droits":
					//Gestion des droits

			        $managerForum = new ManagerForum($bdd);
			        $jointures = $managerForum->ajoutJointuresViews($id);

			        $donneesForums = $managerForum->tousLesForums($jointures , $id , $lvl);
					echo'<h1>Edition des droits</h1>';

					if(!isset($_POST['forum']))
					{

						echo'<form method="post" action="index.php?cat=forum&action=droits">';
							echo'<p>
									Choisir un forum :</br />
									<select name="forum">';

									foreach ($donneesForums as $donneesForum) {

									    $forum = new Forum($donneesForum);
										echo'<option value="'.$forum->id().'">'.$forum->name().'</option>';
									}

									echo'<input type="submit" value="Envoyer">
							    </p>
						</form>';
					}

					else
					{
							$donneesForum = $managerForum->infosForum((int)$_POST['forum']);
							$forum = new Forum($donneesForum);

							echo '<form method="post" action="adminok.php?cat=forum&action=droits">
									<p>
										<table>
										     <tr>
												<th>Lire</th>
												<th>Répondre</th>
												<th>Poster</th>
												<th>Annonce</th>
												<th>Modérer</th>
										    </tr>';

										   //Ces deux tableaux vont permettre d'afficher les résultats
										   $rang = array(
														VISITEUR=>"Visiteur",
														INSCRIT=>"Membre",
														MODO=>"Modérateur",
														ADMIN=>"Administrateur"
													);

										    $list_champ = array(
											                 "auth_view",
											                 "auth_post",
										                     "auth_topic",
										                     "auth_annonce", 
										                     "auth_modo"
										                    );
										//On boucle

										foreach($list_champ as $champ)
										{
											echo'<td><select name="'.$champ.'">';

											for($i=1; $i<5; $i++)
											{
												if ($i == $forum->$champ())
												{
													echo'<option value="'.$i.'" selected="selected">'.$rang[$i].'</option>';
												}
												else
												{
													echo'<option value="'.$i.'">'.$rang[$i].'</option>';
												}
											}
											echo'</td></select>';
										}
										echo'<br /><input type="hidden" name="id" value="'.$forum->id().'" />
										<input type="submit" value="Envoyer">
									</p>
								</form>';
							
					}
					echo '</table>';
					break;


					default; //action n'est pas remplie, on affiche le menu
					echo'<h1>Administration des forums</h1>';
					echo'<p>
					Bonjour, cher Administrateur :p, que veux tu faire ?
					<br />
					<a href="./index.php?cat=forum&amp;action=creer">Créer un forum</a>
					<br />
					<a href="./index.php?cat=forum&amp;action=edit">Modifier un
					forum</a>
					<br />
					<a href="./index.php?cat=forum&amp;action=droits">
					Modifier les droits d un forum</a><br /></p>';
					break;
		}

		break;


	case "membres":
		//Ici membres
		$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):''; //On récupère la valeur de action

		switch($action) //2eme switch
		{
				case "edit":
					//Edition d'un membre

					echo'<h1>Edition du profil d un membre</h1>';
					if(!isset($_POST['membre'])) //Si la variable $_POST['membre'] n'existe pas
					{
						echo'De quel membre voulez-vous éditer le profil ?
						<br />';
						echo'<br />

						<form method="post" action="./index.php?cat=membres&amp;action=edit">
							<p>
								<label for="membre">Inscrivez le pseudo : </label>
								<input type="text" id="membre" name="membre">
								<input type="submit" name="Chercher" value="Chercher">
							</p>
						</form>';
					}

					else //sinon
					{
						$pseudo_d = $_POST['membre'];
						//Requête qui ramène des info sur le membre à partir de son pseudo
						
						$managerMembre = new ManagerMembre($bdd);
						$donneesMembre = $managerMembre->recupereLeMembre($pseudo_d);
						$membre = new Membre($donneesMembre);

						//Si la requête retourne un truc, le membre existe
						if (!empty($donneesMembre))
						{
							?>
							<form method="post" action="adminok.php?cat=membres&amp;action=edit" enctype="multipart/form-data">

									<fieldset>
											<legend>Identifiants</legend>
											<label for="pseudo">Pseudo :</label>
											<input type="text" name="pseudo" id="pseudo" value="<?php echo stripslashes(htmlspecialchars($membre->pseudo())) ?>" /><br />
									</fieldset>

									<fieldset>

											<legend>Contacts</legend>
											<label for="email">Adresse E_Mail :</label>
											<input type = "text" name="email" id="email" value="<?php echo stripslashes(htmlspecialchars($membre->email())) ?>" /><br />

											<label for="website">Site web :</label>
											<input type = "text" name="siteweb" id="website"
											value="<?php echo stripslashes(htmlspecialchars($membre->siteweb())) ?>"/><br />

									</fieldset>

									<fieldset>
											<legend>
											        Informations supplémentaire
											</legend>

											<label for="localisation">
											       Localisation :
											</label>

											<input type = "text" name="localisation" id="localisation" value="<?php echo stripslashes(htmlspecialchars($membre->localisation())) ?>" />
											<br />
									</fieldset>

									<fieldset>

												<legend>
												        Profil sur le forum
												</legend>

												<label for="avatar">
												             Changer l avatar :
												</label>

												<input type="file" name="avatar" id="avatar" />
												<br />
												<br />

												<label>
												      <input type="checkbox" name="delete" value="Delete" /> Supprimer l avatar
												</label>

												    Avatar actuel : <?php echo'<img src="../images/avatars/'.$membre->avatar().'" alt="pas d avatar" />' ?>
												<br /><br />

												<label for="signature">Signature :</label>

												<textarea cols=40 rows=4 name="signature" id="signature">
												          <?php echo $membre->signature() ?>
												</textarea>

												<br />
												</h2>
									</fieldset>
									<?php
									echo'<input type="hidden" value="'.stripslashes($pseudo_d).'" name="pseudo_d">
									     <input type="submit" value="Modifier le profil" />
							</form>';
						}
						else echo' <p>
										Erreur : Ce membre n existe pas, <br />
										cliquez <a href="./index.php?cat=membres&amp;action=edit">ici</a>
										pour réessayez
						          </p>';
					}

					break;

                    break;

				case "droits":

					//Droits d'un membre (rang)

					echo'<h1>Edition des droits d un membre</h1>';

					if(!isset($_POST['membre']))
					{
							echo'<p>De quel membre voulez-vous modifier les droits
							?</p>';

							echo'<br />

							<form method="post" action="./index.php?cat=membres&action=droits">
									<p>
											<label for="membre">Inscrivez le pseudo : </label>

											<input type="text" id="membre" name="membre"/>

											<input type="submit" value="Chercher"/>
									</p>
							</form>';
					}

					else
					{

							$pseudo_d = $_POST['membre'];
                            $managerMembre = new ManagerMembre($bdd);
                            $donneesMembre = $managerMembre->recupereLeMembre($pseudo_d);

							if (!empty($donneesMembre))
							{
								$membre = new Membre($donneesMembre);
								echo'<form action="./adminok.php?cat=membres&amp;action=droits" method="post">';

											$rang = array(
															0 => "Bannis",
															1 => "Visiteur",
															2 => "Membre",
															3 => "Modérateur",
															4 => "Administrateur"
															); 

															//Ce tableau associe numéro de droit et nom

											echo'<label>'.$membre->pseudo().'</label>';

											echo'<select name="rang">';

														for($i = 0 ; $i < 5; $i++)
														{
																if ($i == $membre->rang())
																{

																	echo'<option value="'.$i.'" selected="selected">'.$rang[$i].'</option>';
																}

																else
																{

																	echo'<option value="'.$i.'">'.$rang[$i].'</option>';

																}
														}

											echo'</select>

											<input type="hidden" value="'.stripslashes($pseudo_d).'" name="pseudo">
											<input type="submit" value="Envoyer">
									</form>';
							}

							else echo' <p>
											Erreur : Ce membre n existe pas, <br />
										cliquez <a href="./index.php?cat=membres&amp;action=droits">ici</a>
										pour réessayer
							        </p>';
					}

					break;




					break;

					//Bannissement

				case "ban":
					//Bannissement
					echo'<h1>Gestion du bannissement</h1>';
					//Zone de texte pour bannir le membre
					echo'<p>Quel membre voulez-vous bannir ?</p>';

					echo'<br />

					<form method="post" action="./adminok.php?cat=membres&amp;action=ban">

							<label for="membre">Inscrivez le pseudo : </label>

							<input type="text" id="membre" name="membre">

							<input type="submit" value="Envoyer">
							<br />';

							//Ici, on boucle : pour chaque membre banni, on affiche une checkbox
							//Qui propose de le débannir

							$managerMembre = new ManagerMembre($bdd);
                            $donneesMembresBannis = $managerMembre->tousLesBannis();
							//Bien sur, on ne lance la suite que s'il y a des membres bannis !

							if (!empty($donneesMembresBannis))
							{
								foreach ($donneesMembresBannis as $donneesMembre) {
									$membre = new Membre($donneesMembre);

									echo'<br />
									<label>
											<a href="./voirprofil.php?action=consulter&amp;m='.$membre->id().'">

											'.stripslashes(htmlspecialchars($membre->pseudo())).'</a>
									</label>

									<input type="checkbox" name="'.$membre->id().'" /> Débannir<br />';

									}

									echo'<p>
									      <input type="submit" value="Go !" />
									</p>

							    </form>';

					        }

					else 
						echo' <p>
					           Aucun membre banni pour le moment :p
					       </p>';

					
					break;



				    break;





				default; //action n'est pas remplie, on affiche le menu
					echo'<h1>
					         Administration des membres
					    </h1>';
					echo'<p>
							Salut mon ptit, alors tu veux faire quoi ?<br />

                            <a href="./index.php?cat=membres&amp;action=edit">Editer le profil d un membre</a>
                            <br />
							<a href="./index.php?cat=membres&amp;action=droits">Modifier les droits d un membre</a>
							<br />
							<a href="./index.php?cat=membres&amp;action=ban"> Bannir / Debannir un membre</a>
							<br />
						</p>';
					break;
		}

		break;

	default; //cat n'est pas remplie, on affiche le menu général

		echo'<h1>
		         Index de l administration
		    </h1>';

		echo'<p>

		        Bienvenue sur la page d administration.<br />

				<a href="./index.php?cat=config">Configuration du forum</a>
				<br />
				<a href="./index.php?cat=forum">Administration des forums</a>
				<br />
				<a href="./index.php?cat=membres">Administration des membres</a>
				<br/>
		</p>';
		break;
}
?>

</div>
</body>
</html>