			<?php
			include "../includes/session.php";

			include("../includes/identifiants.php");
			include("../includes/debut.php");

			if($id == 0 || !isset($id))
			{
				header('Location: ../index.php');
			}

			$titre="Messages Privés | SiteduSavoir.com";
			$balises = true;

			include("../includes/bbcode.php");
			include("../includes/menu.php");

			$action =(isset($_GET['action']))?htmlspecialchars($_GET['action']):'';
		    switch($action) //On switch sur $action
			{


				case "consulter": 

				//Si on veut lire un message

					echo '<div class="fildariane">
									         <ul>
									              <li>
									                <a href="../index.php">Accueil</a>
									              </li>
									                 <img class="fleche" src="../images/icones/fleche.png"/>
									              <li>
									                  <a href="./messagesprives.php">Messages prives</a>
									              </li>
									                 <img class="fleche" src="../images/icones/fleche.png"/>
									              <li> Consulter message </li>  
									         </ul>
									  </div>';

					$id_mess = (int) $_GET['id']; 

					//On récupère la valeur de l'id

					echo '<div class="page">
					              <h1 class="titre">Consulter un message</h1>';
					//La requête nous permet d'obtenir les infos sur ce message :
					$query = $bdd->prepare('SELECT mp_expediteur, mp_receveur,mp_titre,mp_time, mp_text, mp_lu, membre_id, membre_pseudo, membre_avatar,
					    membre_localisation, membre_inscrit, membre_post, membre_signature FROM mp LEFT JOIN membres ON membre_id = mp_expediteur WHERE mp_id = :id');

					$query->bindValue(':id',$id_mess,PDO::PARAM_INT);
					$query->execute();

					$data = $query->fetch();

					// Attention ! Seul le receveur du mp peut le lire !

					if ($id != $data['mp_receveur']) 
						erreur(ERR_WRONG_USER);

					//bouton de réponse
					echo'<p class="nouveau-sujet">
							<img src="../images/icones/mail.png"/><a href="./messagesprives.php?action=repondre&amp;dest='.$data['mp_expediteur'].'">Repondre </a>
					     </p>';
					?>
					<table>
							<tr>
									<th class="vt_auteur">
									       <strong>Auteur</strong>
									</th>

									<th class="vt_mess"><strong>Message</strong></th>
							</tr>

							<tr>
									<td>
										<?php echo'<strong>
										<a href="../forum/voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">
										'.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a></strong></td>
										<td>Posté à '.$data['mp_time'].'
									</td>';
									?>
							</tr>

							<tr>
									<td>
										<?php
										//Ici des infos sur le membre qui a envoyé le mp
										echo'<p>
												<img src="../images/avatars/'.$data['membre_avatar'].'" alt="" />
												<br />Membre inscrit le '.$data['membre_inscrit'].'
												<br />Messages : '.$data['membre_post'].'
												<br />Localisation :
												'.stripslashes(htmlspecialchars($data['membre_localisation'])).'
										    </p>
									</td>

									<td>';
										echo code(nl2br(stripslashes(htmlspecialchars($data['mp_text'])))).'
										<hr
										/>'.code(nl2br(stripslashes(htmlspecialchars($data['membre_signature'])))).'
									</td>
							</tr>
					</table>';
					?>

					<?php

					if ($data['mp_lu'] == 0) //Si le message n'a jamais été lu
					{
							$query->CloseCursor();
							$query=$bdd->prepare('UPDATE mp SET mp_lu = :lu WHERE mp_id= :id');
							$query->bindValue(':id',$id_mess, PDO::PARAM_INT);

							$query->bindValue(':lu','1', PDO::PARAM_STR);
							$query->execute();
							$query->CloseCursor();
					}

					break; //La fin !

				case "repondre": 

				//On veut répondre

					echo '<div class="fildariane">
									         <ul>
									              <li>
									                <a href="../index.php">Accueil</a>
									              </li>
									                 <img class="fleche" src="../images/icones/fleche.png"/>
									              <li>
									                  <a href="./messagesprives.php">Messages prives</a>
									              </li>
									                 <img class="fleche" src="../images/icones/fleche.png"/>
									              <li><span style="color:black;"> Repondre a un message </span></li>  
									         </ul>
									  </div>

						<div class="page">';

					echo '<h1 class="titre">Répondre à un message privé</h1>';

					$dest = (int) $_GET['dest'];

					?>

					 <div class="formulaire">

							<form method="post" action="./messok.php?action=repondremp&amp;dest=<?php echo $dest ?>">
									<div class="input">
										    <label for="titre"><span>Titre</span></label>
										    <input type="text" name="titre" />
								    </div>		    
											<?php include "../includes/miseenforme.php"; ?>

									<fieldset>
									          <legend>Message</legend>

									          <div class="textarea">
									                <textarea  name="message" required>
												
											       </textarea>
											 </div>

											 <div class="submit submit-tuto">
											      <input type="submit" name="submit" value="Envoyer" />
											</div>

											<div class="submit submit-tuto">
											     <input type="reset" name="Effacer" value="Effacer"/>
											</div>
										
									</fieldset>

							</form>
					</div>

					<?php

					 break;


				case "nouveau": 
				//Nouveau mp

					echo '<div class="fildariane">
									         <ul>
									              <li>
									                <a href="../index.php">Accueil</a>
									              </li>
									                 <img class="fleche" src="../images/icones/fleche.png"/>
									              <li>
									                  <a href="./messagesprives.php">Messages prives</a>
									              </li>
									                 <img class="fleche" src="../images/icones/fleche.png"/>
									              <li> Consulter message </li>  
									         </ul>
									  </div>
							<div class="page">';

					echo '<h1 class="titre">Nouveau message privé</h1><br /><br />';

					?>

					<div class="formulaire">

							<form method="post" action="./messok.php?action=nouveaump" name="formulaire">

									<div class="input">

											<label for="to"><span> A :</span></label>
											<input type="text" name="to" />
									</div>
									
									<div class="input">

											<label for="titre"><span>Titre</span></label>
											<input type="text" size="80" id="titre" name="titre" />
								    </div>

                                     	<?php include "../includes/miseenforme.php"; ?>

                                    <fieldset>
                                     
                                     	<legend>Message</legend>
	                                    <div class="textarea">

												<textarea  name="message"></textarea>
									    </div>			
										
										<div class="submit submit-tuto">
												<input type="submit" name="submit" value="Envoyer" />
										</div>	

										<div class="submit submit-tuto">	

												<input type="reset" name="Effacer" value="Effacer" />
										</div>
							   </fieldset>		
							</form>
					   </div>		

					<?php
					 break;
					//Si rien n'est demandé ou s'il y a une erreur dans l'url
					//On affiche la boite de mp.
				default: 

					echo '<div class="fildariane">
					         <ul>
					            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./messagesprives.php">Messages prives </a></li>
					         </ul>
                          </div>

                            <div class="page">';

					echo '<h1 class="titre">Messagerie Privée</h1><br />';

					$query = $bdd->prepare('SELECT mp_lu, mp_id, mp_expediteur, mp_titre, mp_time,
					membre_id, membre_pseudo
					FROM mp
					LEFT JOIN membres ON mp.mp_expediteur = membres.membre_id
					WHERE mp_receveur = :id ORDER BY mp_id DESC');
					$query->bindValue(':id',$id,PDO::PARAM_INT);
					$query->execute();

					echo'<p class="nouveau-sujet"><img src="../images/icones/mail.png"/><a href="./messagesprives.php?action=nouveau">Nouveau
					</a></p>';

					if ($query->rowCount()>0)
					{
						?>
						<table>

							<tr>
									<th>
										
									</th>

									<th class="mp_titre">
									     <strong>Titre</strong>
									</th>

									<th class="mp_expediteur">
									       <strong>Expéditeur</strong>
									</th>

									<th class="mp_time">

									        <strong>Date</strong>

									</th>

									<th>
									     <strong>Action</strong>
									</th>
							</tr>

							<?php
							//On boucle et on remplit le tableau
							while ($data = $query->fetch())
							{
								   echo'<tr>';

									       //Mp jamais lu, on affiche l'icone en question

											if($data['mp_lu'] == 0)
											{

											echo'<td>
											         <img src="../images/mp_non_lu.png" alt="Non lu"/>
											   </td>';
											}

											else //sinon une autre icone
											{

											echo'<td>
											         <img src="../images/mp_lu.png" alt="Déja lu" />
											    </td>';

											}

											echo'<td id="mp_titre">
													<a href="./messagesprives.php?action=consulter&amp;id='.$data['mp_id'].'">'.stripslashes(htmlspecialchars($data['mp_titre'])).'</a>
												</td>

												<td id="mp_expediteur">
													<a href="../forum/voirprofil.php?action=consulter&amp;m='.$data['membre_id'].'">
													'.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a>
												</td>

												<td id="mp_time">'
												      .$data['mp_time'].
												'
												</td>

												<td>
													<a href="./messok.php?action=supprimer&amp;id='.$data['mp_id'].'&amp;sur=0">supprimer</a>
											    </td>

									    </tr>';
							} 

							//Fin de la boucle

							$query->CloseCursor();

					  echo '</table>';

					}

					 //Fin du if

					else
					{
						echo'<p>
						         Vous n avez aucun message privé pour l instant, cliquez
						         <a href="../index.php">ici</a> pour revenir à la page d index
						    </p>';
					}

			}
			 //Fin du switch
			?>

	    </div>
	    <?php include "../includes/footer.php"; ?>
	</body>
</html>


