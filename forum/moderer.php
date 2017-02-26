<?php

  include "../includes/session.php";
 include("../includes/identifiants.php");


$topic = $_GET['t'];
$forum = $_GET['f'];


$query = $bdd->prepare('SELECT topic_titre, topic_post, forum_topic.forum_id , topic_last_post,forum_name, auth_view, auth_topic, auth_post,auth_modo
                        FROM forum_topic
                        LEFT JOIN forum 
                        ON forum_topic.forum_id = forum.forum_id 
                        WHERE topic_id = :topic');

$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->execute();

$data = $query->fetch();

$titre = $data['topic_titre'] ." | SiteduSavoir.com";

include("../includes/debut.php");

if(!verif_auth($data['auth_modo']))
{
	header('Location:./index.php');
}

include("../includes/menu.php");


echo '<div class="fildariane">

         <ul>
         <li>
              <a href="../index.php"> Accueil </a>
        </li> 
              <img class="fleche" src="../images/icones/fleche.png"/>
            <li>
                <a href="./index.php">Forum</a>
            </li>

            <img class="fleche" src="../images/icones/fleche.png"/>

           <li>
               <a href="./voirforum.php?f='.$forum.'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
           </li>  <img class="fleche" src="../images/icones/fleche.png"/>   
           <li>
            <a href="./voirtopic.php?t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
            </li>
         </ul>
     <div>';

echo '<div class="page">';


$action = (isset($_GET['action']))?$_GET['action']:"";

switch($action)
{
	case "autoreponse":

          echo' 
              <h2 class="titre"> Ferme automatiquement le sujet , apres la reponse </h2>

          <div class="formulaire">
	          <form method="post" action=postok.php?action=autorep&amp;t='.$topic.'>

	           <div class="select">
						<select name="rep">';

						$query=$bdd->query('SELECT automess_id, automess_titre FROM forum_automess');

						while($data = $query->fetch())
						{
						echo '<option value="'.$data['automess_id'].'">
						'.$data['automess_titre'].'</option>';

						}
						echo '</select>
				</div>

				<div class="submit">
				       <input type="submit" name="submit" value="Envoyer" />
				</div>
				</form>
		</div>';
			$query->CloseCursor();



	break;

	case "deplacer":

    $query=$bdd->prepare('SELECT forum_id, forum_name 
                        FROM forum
                        WHERE forum_id <> :forum');
  $query->bindValue(':forum',$forum,PDO::PARAM_INT);
  $query->execute();

  //$forum a été définie tout en haut de la page !

  echo'<h1 class="titre"> Déplacer vers :</h1>

  <div class="formulaire">

		  <form method="post" action=postok.php?action=deplacer&amp;t='.$topic.'>

          <div class="select">
			  <select name="dest">';
			  while($data=$query->fetch())
			  {
			  echo'<option value='.$data['forum_id'].' id='.$data['forum_id'].'>'.$data['forum_name'].'</option>';
			  }

			   $query->CloseCursor();

			  echo'
			  </select>
		 </div>

		  <input type="hidden" name="from" value='.$forum.'>

		  <div class="submit">
		      <input type="submit" name="submit" value="Envoyer" />
		  </div>
		  </form>
	</div>';

	break;
}