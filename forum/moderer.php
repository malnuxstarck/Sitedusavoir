<?php

include "../includes/session.php";
include("../includes/identifiants.php");

$titre = 'Moderation - Topic ';
include("../includes/debut.php");

$idTopic = $_GET['t'];
$idForum = $_GET['f'];
$managerTopic = new ManagerTopic($bdd);

$managerForum = new ManagerForum($bdd);
$donneesTopic = $managerTopic->infosTopic($idTopic);
$topic = new Topic($donneesTopic);

$donneesForum = $managerForum->infosForum($idForum);
$forum = new Forum($donneesForum);


if(!Membre::verif_auth($forum->auth_modo()))
{
	header('Location:./index.php');
}

include("../includes/menu.php");

echo '<ul class="fildariane">
  <li><a href="../index.php">Accueil</a></li>
  <li><a href="./index.php">Forum</a></li>
  <li><a href="./voirforum.php?f='.$forum->id().'">'.stripslashes(htmlspecialchars($forum->name())).'</a></li>
  <li><a href="./voirtopic.php?t='.$topic->id().'">'.stripslashes(htmlspecialchars($topic->titre())).'</a></li>
</ul>';

echo '<div class="page">';
$action = (isset($_GET['action']))?$_GET['action']:"";

switch($action)
{
	case "autoreponse":

          echo' 
              <h2 class="titre"> Ferme automatiquement le sujet , apres la reponse </h2>

          <div class="formulaire">
	          <form method="post" action=postok.php?action=autorep&amp;t='.$topic->id().'>

	           <div class="select">
						<select name="rep">';

						$managerAutoMessage = new ManagerAutoMessage($bdd);
                        $donneesAutoMesages = $managerAutoMessage->tousLesAutoMessages();

						foreach ($donneesAutoMesages as $automessageDonnnee) {
							$automess = new AutoMessage($automessageDonnnee);

						echo '<option value="'.$automess->id().'">
						'.$automess->titre().'</option>';

						}
						echo '</select>
				</div>

				<div class="submit">
				       <input type="submit" name="submit" value="Envoyer" />
				</div>
				</form>
		</div>';
	break;

	case "deplacer":

  //$forum a été définie tout en haut de la page !

  echo'<h1 class="titre"> Déplacer vers :</h1>

  <div class="formulaire">

		  <form method="post" action=postok.php?action=deplacer&amp;t='.$topic->id().'>

          <div class="select">
			  <select name="dest">';

			  $donneesForums = $managerForum->forumsDifferentDeCeluiLa($forum->id());

			  foreach ($donneesForums as $donneesForum) {

                     $forumd = new Forum($donneesForum);
                     echo'<option value='.$forumd->id().' id='.$forumd->id().'>'.$forumd->name().'</option>';
			  }

			  echo'
			  </select>
		 </div>

		  <input type="hidden" name="from" value='.$forum->id().'>

		  <div class="submit">
		      <input type="submit" name="submit" value="Envoyer" />
		  </div>
		  </form>
	</div>';

	break;
}