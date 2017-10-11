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

					echo '<ul class="fildariane">
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="./messagesprives.php">Messages prives</a></li>
            <li>Consulter message</li>
          </ul>';

					$idMessage = (int) $_GET['id'];

					//On récupère la valeur de l'id

					echo '<div class="page">
					              <h1 class="titre">Consulter un message</h1>';
					//La requête nous permet d'obtenir les infos sur ce message :
					  $managerMembre = new ManagerMembre($bdd);
					  $managerMp = new ManagerMp($bdd);

					  $infosMessage = $managerMp->infosMessage($idMessage);
					  $leMessage = new Mp($infosMessage);

					  $infosMembre = $managerMembre->infosMembre($leMessage->expediteur());
					  $expediteurMessage = new Membre($infosMembre);


					// Attention ! Seul le receveur du mp peut le lire !

					if ($id != $leMessage->receveur())
						erreur(ERR_WRONG_USER);

					//bouton de réponse
					echo'<p class="nouveau-sujet">
							<img src="../images/icones/mail.png"/><a href="./messagesprives.php?action=repondre&amp;dest='.$leMessage->expediteur().'">Repondre </a>
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
										<a href="../forum/voirprofil.php?m='.$expediteurMessage->id().'&amp;action=consulter">
										'.stripslashes(htmlspecialchars($expediteurMessage->pseudo())).'</a></strong></td>
										<td>Posté à '.$leMessage->mptime().'
									</td>';
									?>
							</tr>

							<tr>
									<td>
										<?php
										//Ici des infos sur le membre qui a envoyé le mp
										echo'<p>
												<img src="../images/avatars/'.$expediteurMessage->avatar().'" alt="" />
												<br />Membre inscrit le '.$expediteurMessage->inscrit().'
												<br />Messages : '.$expediteurMessage->posts().'
												<br />Localisation :
												'.stripslashes(htmlspecialchars($expediteurMessage->Localisation())).'
										    </p>
									</td>

									<td>';
										echo code(nl2br(stripslashes(htmlspecialchars($leMessage->texte())))).'
										<hr
										/>'.code(nl2br(stripslashes(htmlspecialchars($expediteurMessage->signature())))).'
									</td>
							</tr>
					</table>';
					?>

					<?php

					if ($leMessage->lu() == 0) //Si le message n'a jamais été lu
					{
							$managerMp->messageLu($leMessage->id());
					}

					break; //La fin !

				case "repondre":

				//On veut répondre

        echo '<ul class="fildariane">
          <li><a href="../index.php">Accueil</a></li>
          <li><a href="./messagesprives.php">Messages prives</a></li>
          <li><span>Repondre a un message</span></li>
        </ul>

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
									                <textarea name="texte" required></textarea>
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

					echo '<ul class="fildariane">
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="./messagesprives.php">Messages prives</a></li>
            <li>Consulter message</li>
          </ul>
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

												<textarea  name="texte"></textarea>
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

					echo '<ul class="fildariane">
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="./messagesprives.php">Messages prives</a></li>
          </ul>

          <div class="page">';

					echo '<h1 class="titre">Messagerie Privée</h1><br />';

					$managerMp = new ManagerMp($bdd);
					$managerMembre = new ManagerMembre($bdd);

					$tousLesMessages = $managerMp->tousLesMessages($id);

					echo'<p class="nouveau-sujet"><img src="../images/icones/mail.png"/><a href="./messagesprives.php?action=nouveau">Nouveau
					</a></p>';

					if (!empty($tousLesMessages))
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
							foreach ($tousLesMessages as $leMessage) {

                                     $mp = new Mp($leMessage);
                                     $infosExpediteur = $managerMembre->infosMembre($mp->receveur());

                                     $expediteur = new Membre($infosExpediteur);


								   echo'<tr>';

									       //Mp jamais lu, on affiche l'icone en question

											if($mp->lu() == 0)
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
													<a href="./messagesprives.php?action=consulter&amp;id='.$mp->id().'">'.stripslashes(htmlspecialchars($mp->titre())).'</a>
												</td>

												<td id="mp_expediteur">
													<a href="../forum/voirprofil.php?action=consulter&amp;m='.$expediteur->id().'">
													'.stripslashes(htmlspecialchars($expediteur->pseudo())).'</a>
												</td>

												<td id="mp_time">'
												      .$mp->mptime().
												'
												</td>

												<td>
													<a href="./messok.php?action=supprimer&amp;id='.$mp->id().'&amp;sur=0">supprimer</a>
											    </td>

									    </tr>';
							}

							//Fin de la boucle



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


