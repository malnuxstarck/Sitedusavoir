		<?php
        include "../includes/session.php";
		$titre="Messagerie | Sitedusavoir";

		include("../includes/identifiants.php");
		include("../includes/debut.php");
		include("../includes/menu.php");
		include('../includes/bbcode.php');

		if ($id==0) 
			erreur(Membre::ERR_IS_CO);


		$action =(isset($_GET['action']))?$_GET['action']:'';

		$message =(isset($_POST['texte']))?htmlspecialchars($_POST['texte']):'';

		$titre = (isset($_POST['titre']))?htmlspecialchars($_POST['titre']):'';




	    switch($action)
		{

            case "repondremp": //Si on veut répondre
				//On récupère le titre et le message

				//On récupère la valeur de l'id du destinataire
            
				$dest = (int)$_GET['dest'];
				$donnees = array('titre' => $titre , 'texte' => $message , 'expediteur' => $id , 'receveur' => $dest);

				$infoMessage = new Mp($donnees);
				$managerMp = new ManagerMp($bdd);

			    $managerMp->envoyerMp($infoMessage);

				break;

			case "nouveaump": //On envoie un nouveau mp
				//On récupère le titre et le message

				$message = $_POST['texte'];
				$titre = $_POST['titre'];
                $dest = $_POST['to'];

                $infoMessage = new Mp($_POST);
                $managerMp = new ManagerMp($bdd);
                $infosMembre = $managerMp->recupereLeMembre($infoMessage->to());
                $infoMessage->setReceveur((int)$infosMembre['id']);
                $infoMessage->setExpediteur($id);

				//On récupère la valeur de l'id du destinataire
				//Il faut déja vérifier le nom

				if(!empty($infosMembre))
				{
						$managerMp->envoyerMp($infoMessage);
				}

				//Sinon l'utilisateur n'existe pas !
				else
				{
						echo'<p>
								Désolé ce membre n existe pas, veuillez vérifier et
								réessayez à nouveau.
						    </p>';
				}

				break;

			case "supprimer":

				//On récupère la valeur de l'id
				$idMessage = (int) $_GET['id'];
				$managerMp = new ManagerMp($bdd);

				$donnees = $managerMp->infosMessage($idMessage);
				$infosMessage = new Mp($donnees);

				
				//Il faut vérifier que le membre est bien celui qui a reçu le messag
				//Sinon la sanction est terrible :p

				if ($id != $infoMessage->receveur()) 
					erreur(ERR_WRONG_USER);

				$sur = (int) $_GET['sur'];

				//Pas encore certain

				if ($sur == 0)
				{
					echo'<p>
							Etes-vous certain de vouloir supprimer ce message ?<br/>
							<a href="./messok.php?action=supprimer&amp;id='.$idMessage.'&amp;sur=1"> Oui</a> - <a href="./messagesprives.php">Non</a>
						</p>';
				}
				//Certain
				else
				{
					$managerMp->deleteMp($idMessage);

					echo'<p>
							Le message a bien été supprimé.<br />
							Cliquez <a href="./messagesprives.php">ici</a> pour revenir à la boite de messagerie.
					    </p>';
				}
				break;

			default:
				 echo '<p> Action impossible</p>';
		}

		?>
		</div>
    </body>
</html>

