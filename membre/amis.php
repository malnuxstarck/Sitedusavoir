<?php
session_start();

$titre="Gestion des amis";
include("../includes/identifiants.php");

include("../includes/debut.php");

		include("../includes/menu.php");

		$action = isset($_GET['action'])?htmlspecialchars($_GET['action']):'';

		

		if ($id==0) 
			erreur(ERR_IS_CO);
		//Le titre

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
						$pseudo_d = $_POST['pseudo'];
						//On vérifie que le pseudo renvoit bien quelque chose :o
						$query=$bdd->prepare('SELECT membre_id, COUNT(*) AS nbr FROM membres WHERE LOWER(membre_pseudo) = :pseudo GROUP BY membre_pseudo');

						$query->bindValue(':pseudo',strtolower($pseudo_d),PDO::PARAM_STR);
						$query->execute();

						$data = $query->fetch();
						$pseudo_exist = $data['nbr'];

						$i = 0;
						$id_to=$data['membre_id'];

						if(!$pseudo_exist)
						{
							echo '<p>
									Ce membre ne semble pas exister<br />
									Cliquez <a href="./amis.php?action=add">ici</a> pour réessayer
							    </p>';
							$i++;
						}

						$query->CloseCursor();

						$query = $bdd->prepare('SELECT COUNT(*) AS nbr FROM amis WHERE ( ami_from = :id AND ami_to = :id_to ) OR ( ami_from = :id_to AND ami_to = :id)');
						$query->bindValue(':id',$id,PDO::PARAM_INT);
						$query->bindValue(':id_to', $id_to, PDO::PARAM_INT);

						$query->execute();
						$deja_ami = $query->fetchColumn();

						$query->CloseCursor();


						if ($deja_ami != 0)
						{
						   echo '<p>
						                Ce membre fait déjà parti de vos amis ou a déjà
										proposé son amitié :p<br />
										Cliquez <a href="./amis.php?action=add">ici</a> pour réessayer
								</p>';
								
								$i++;
						}

						if ($id_to == $id)
						{
								echo '<p>
								            Vous ne pouvez pas vous ajouter vous même<br />
								            Cliquez <a href="./amis.php?action=add">ici</a> pour réessayer
								     </p>';
								$i++;
						}

						if ($i == 0)
						{
								$query=$bdd->prepare('INSERT INTO amis (ami_from, ami_to,ami_confirm, ami_date)
								VALUES(:id, :id_to, :conf,NOW())');

								$query->bindValue(':id',$id,PDO::PARAM_INT);
								$query->bindValue(':id_to', $id_to, PDO::PARAM_INT);
								$query->bindValue(':conf','0',PDO::PARAM_STR);

								$query->execute();
								$query->CloseCursor();

							   echo '<p>
										   <a href="/voirprofil.php?m='.$data['membre_id'].'">'.stripslashes(htmlspecialchars($pseudo_d)).'</a>
											a bien été ajouté à vos amis, il faut toutefois qu il donne son accord.<br
											/>
											Cliquez <a href="../index.php">ici</a> pour retourner à la page d\'accueil<br
											/>
											Cliquez <a href="./amis.php">ici</a> pour retourner à la page de gestion
											des amis
								    </p>';
						}
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
					$query = $bdd->prepare('SELECT ami_from, ami_date, membre_pseudo FROM amis
					LEFT JOIN membres ON membre_id = ami_from
					WHERE ami_to = :id AND ami_confirm = :conf
					ORDER BY ami_date DESC');

					$query->bindValue(':id',$id,PDO::PARAM_INT);
					$query->bindValue(':conf','0',PDO::PARAM_STR);
					$query->execute();

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

					if ($query->rowCount() == 0)
					{
					echo '<td colspan="3" align="center">Vous n avez aucune proposition</td>';
					}

					while ($data = $query->fetch())
					{
					echo '<tr><td><a href="./voirprofil.php?m='.$data['ami_from'].'&amp;action=consulter">'.stripslashes(htmlspecialchars($data['membre_pseudo'])).'
					<td>'.$data['ami_date'].'</td><td><a href="./amis.php?action=check&amp;add=ok&amp;m='.$data['ami_from'].'">Accepter</a><a href="./amis.php?action=delete&amp;m='.$data['ami_from'].'">Refuser</a></td></tr>';
					}

					$query->CloseCursor();

					echo '</table>';

				}

				else
				{
						$membre = (int) $_GET['m'];

						$query = $bdd->prepare('UPDATE amis SET ami_confirm = :conf WHERE ami_from = :membre AND ami_to = :id');
						$query->bindValue(':conf','1',PDO::PARAM_STR);

						$query->bindValue(':membre',$membre,PDO::PARAM_INT);

						$query->bindValue(':id',$id,PDO::PARAM_INT);
						$query->execute();
						$query->closeCursor();

						echo '<p>
								Le membre a bien été ajouté à votre liste d ami <br/>
								Cliquez <a href="./amis.php">ici</a> pour retourner à la liste des
								amis
						    </p>';
				}

				break;




			case "delete":

				$membre = (int) $_GET['m'];

				if (!isset($_GET['ok']))
				{
						echo '<p>
								Etes vous certain de vouloir supprimer ce membre ?
								<br />
								<a href="./amis.php?
								action=delete&amp;ok=ok&amp;m='.$membre.'">oui</a> - <a
								href="./amis.php">non</a>
						    </p>';
						}
				else
				{
				$query = $bdd->prepare('DELETE FROM amis WHERE ami_from
				= :membre AND ami_to = :id');
				$query->bindValue(':membre',$membre,PDO::PARAM_INT);
				$query->bindValue(':id',$id,PDO::PARAM_INT);

				$query->execute();
				$query->closeCursor();
				$query = $bdd->prepare('DELETE FROM amis WHERE ami_to =
				:membre AND ami_from = :id');

				$query->bindValue(':membre',$membre,PDO::PARAM_INT);
				$query->bindValue(':id',$id,PDO::PARAM_INT);

				$query->execute();
				$query->closeCursor();

				echo '<p>Membre correctement supprimé :D <br />
				Cliquez <a href="./amis.php">ici</a> pour retourner à la liste des
				amis</p>';

				}

				break;

			default:

				$query = $bdd->prepare('SELECT (ami_from + ami_to - :id) AS ami_id, ami_date,membre_pseudo, membre_id FROM amis
				LEFT JOIN membres ON membre_id = (ami_from + ami_to - :id)
				LEFT JOIN forum_whosonline ON online_id = membre_id WHERE (ami_from = :id OR ami_to = :id) AND ami_confirm = :conf ORDER BY membre_pseudo');

				$query->bindValue(':id',$id,PDO::PARAM_INT);
				$query->bindValue(':conf','1',PDO::PARAM_STR);
				$query->execute();


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

				if ($query->rowCount() == 0)
				{
				    echo '<td colspan="4" align="center"> Vous n avez aucun ami pour l instant</td>';
				}

				while ($data = $query->fetch())
				{

					echo '<tr>
							<td>
							<a href="./voirprofil.php?m='.$data['ami_id'].'&amp;action=consulter">'.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a>
							</td>

							<td>'.$data['ami_date'].'</td>

							<td>

							      <a href="./messagesprives.php?action=repondre&amp;dest='.$data['ami_id'].'">Envoyer</a> <a href="./amis.php?action=delete&m='.$data['ami_id'].'">Supprimer</a>
							</td>';

							if (!empty($data['online_id'])) 
								    echo '<td>Oui</td>';

						    else
						       echo '<td>Non</td>';
				}

				echo   '</tr>';

				echo '</table>';

				$query->CloseCursor();

				//On compte le nombre de demande en cours et on met quelques liens

				$query=$bdd->prepare('SELECT COUNT(*) FROM amis
				WHERE ami_to = :id AND ami_confirm = :conf');
				$query->bindValue(':id',$id,PDO::PARAM_INT);
				$query->bindValue(':conf','0', PDO::PARAM_STR);
				$query->execute();
				$demande_ami=$query->fetchColumn();
				//Cette ligne va permettre d'afficher 0 plutôt qu'un vide
				if (empty($demande_ami)) 
				$demande_ami=0;

				echo '<br />

				<ul>
						<li class="nouveau-sujet ajoutami">
						    <a href="./amis.php?action=add">Ajouter un ami</a>
						</li>

						<li class="nouveau-sujet ajoutami">
						    <a href="./amis.php?action=check"> Voir les demandes d\'ajout ('.$demande_ami.')</a>
						</li>
				</ul>		
						';

		}

		echo '</div>';

		include "../includes/footer.php";
		?>

  </body>
</html>
