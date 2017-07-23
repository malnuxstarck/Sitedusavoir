<?php
<<<<<<< HEAD

include "../includes/session.php";
=======
include '../includes/session.php';
>>>>>>> POO

$titre="Gestion des amis";

include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

		$action = isset($_GET['action'])?htmlspecialchars($_GET['action']):'';

		

		if ($id==0) 
			erreur(Membre::ERR_IS_CO);
		

		switch($action)

		{
			case "add":

		             echo '<div class="fildariane">
		               <ul>
		                        <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./amis.php">Gestion des amis</a>
		                        </li><img class="fleche" src="../images/icones/fleche.png"/><li><span style="color:black;">Ajouter un ami </span></li>
		             </ul>
               </div>

               <div class="page">';
                
                echo '<h1 class="titre">Gestion des amis</h1><br /><br />';


			 //On veut ajouter un ami

				if (!isset($_POST['pseudo']))
				{
					echo '<div class="formulaire">
					            <form action="amis.php?action=add" method="post">

									<div class="input">
										<label for="pseudo"></label>
										<input type="text" name="pseudo" />
									</div>

									<div class="submit">
										<input type="submit" value="Envoyer" />
									</div>

					          </form>
				
					 </div>';
				}

				else
				{
						$pseudoAmi = $_POST['pseudo'];
						$managerAmi = new managerAmi($bdd);
						$managerAmi->addAmi($pseudoAmi,$id,$bdd);
				}


				break;


			case "check":

                     echo '<div class="fildariane">
		               <ul>
		                        <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./amis.php">Gestion des amis</a>
		                        </li><img class="fleche" src="../images/icones/fleche.png"/><li><span style="color:black;">Confirmer demande</span></li>
		             </ul>
               </div>

               <div class="page">';
                
                echo '<h1 class="titre">Gestion des amis</h1><br /><br />';


				$add = (isset($_GET['add']))?htmlspecialchars($_GET['add']):0;

				if (empty($add))
				{
					$managerAmi = new ManagerAmi($bdd);
					$donnees = $managerAmi->listesDesDemandes($id);

					echo '<table align="center">
					<tr>
							<th class="pseudo">
							           <strong>Pseudo</strong>
							</th>

							<th class="inscrit">
							         <strong>Date d ajout</strong>
							</th>

							<th>
							    <strong>Action</strong>
							</th>
					</tr>';

					if (empty($donnees))
					{
					echo '<td colspan="3" align="center">Vous n avez aucune proposition</td>';
					}

					foreach ($donnees as $donnee) {

						$ami = new Ami($donnee);
					 	
						echo '<tr><td><a href="./voirprofil.php?m='.$ami->fromt().'&amp;action=consulter">'.stripslashes(htmlspecialchars($ami->idami())).'
						<td>'.$ami->dateamitie().'</td><td><a href="./amis.php?action=check&amp;add=ok&amp;m='.$ami->fromt().'">Accepter</a><a href="./amis.php?action=delete&amp;m='.$ami->fromt().'">Refuser</a></td></tr>';
					}

					echo '</table>';

				}

				else
				{
						$idAmi = (int) $_GET['m'];
						$managerAmi = new ManagerAmi($bdd);
						$managerAmi->confirmerAmitie($id , $idAmi);

						echo '<p>
								Le membre a bien été ajouté à votre liste d ami <br/>
								Cliquez <a href="./amis.php">ici</a> pour retourner à la liste des
								amis
						    </p>';
				}

				break;




			case "delete":

				$idAmi = (int) $_GET['m'];

				if (!isset($_GET['ok']))
				{
						echo '<p>
								Etes vous certain de vouloir supprimer ce membre ?
								<br />
								<a href="./amis.php?
								action=delete&amp;ok=ok&amp;m='.$idAmi.'">oui</a> - <a
								href="./amis.php">non</a>
						    </p>';
						}
				else
				{
				   $managerAmi = new ManagerAmi($bdd);
				   $managerAmi->deleteAmi($id , $idAmi);
				   $managerAmi->deleteAmi($idAmi , $id);

				echo '<p>Membre correctement supprimé :D <br />
				Cliquez <a href="./amis.php">ici</a> pour retourner à la liste des
				amis</p>';

				}

				break;

			default:
                   
                   $managerAmi = new ManagerAmi($bdd);
                   $donnees = $managerAmi->recupereTousMesAmis($id);
                   $lesPersonnesEnLignes = $managerWhoIsOnline->quiSontEnLigne();
                   $managerMembre = new ManagerMembre($bdd);

                echo '<div class="fildariane">
		               <ul>
		                        <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./amis.php">Gestion des amis</a>
		                        </li><img class="fleche" src="../images/icones/fleche.png"/><li><span style="color:black;">Listes des amis </span></li>
		             </ul>
               </div>

               <div class="page">';
                
                echo '<h1 class="titre">Gestion des amis</h1><br /><br />';



				echo '<table align="center">
				<tr>

						<th class="pseudo">
						         <strong>Pseudo</strong>
						</th>

						<th class="inscrit">
						       <strong>Date d ajout</strong>
						</th>

						<th>
						        <strong>Action</strong>
						</th>

						<th>
						      <strong>Connecté</strong>
						</th>
				</tr>';

				if (empty($donnees))
				{
				    echo '<td colspan="4" align="center"> Vous n avez aucun ami pour l\' instant</td>';
				}

				foreach ($donnees as $donnee) {
                       
                       $datasMembre = $ManagerMembre->infosMembre($donne['idami']);
                       $membre = new Membre($datasMembre);

					echo '<tr>
							<td>
							<a href="./voirprofil.php?m='.$membre->id().'&amp;action=consulter">'.stripslashes(htmlspecialchars($membre->pseudo())).'</a>
							</td>

							<td>'.$donnee['dateamitie'].'</td>

							<td>

							      <a href="./messagesprives.php?action=repondre&amp;dest='.$membre->id().'">Envoyer</a> <a href="./amis.php?action=delete&m='.$membre->id().'">Supprimer</a>
							</td>';

							foreach ($lesPersonnesEnLignes as $personne) {

									if ($membre->id() == $personne['online_id']) 
										    echo '<td>Oui</td>';

								    else
								       echo '<td>Non</td>';
								}
				}

				echo   '</tr>';

				echo '</table>';

				

				//On compte le nombre de demande en cours et on met quelques liens

                 $demandes = $managerAmi->nombresDeDemandes($id);
				//Cette ligne va permettre d'afficher 0 plutôt qu'un vide
				if (empty($demandes)) 
				    $demandes=0;

				echo '<br />

				<ul>
						<li class="nouveau-sujet ajoutami">
						    <a href="./amis.php?action=add">Ajouter un ami</a>
						</li>

						<li class="nouveau-sujet ajoutami">
						    <a href="./amis.php?action=check"> Voir les demandes d\'ajout ('.$demandes.')</a>
						</li>
				</ul>		
						';

		}

		echo '</div>';

		include "../includes/footer.php";

		?>

  </body>
</html>
