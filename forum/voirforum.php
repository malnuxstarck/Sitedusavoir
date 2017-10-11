<?php
	include '../includes/session.php';
	include("../includes/identifiants.php");
	require "../class/ManagerForum.class.php";
	require "../class/Forum.class.php";

    //On récupère la valeur de f
    $forumId = (isset($_GET['f']))?(int) $_GET['f']:1;

	$managerForum = new ManagerForum($bdd);
	$donnees = $managerForum->infosForum($forumId);

	if(empty($donnees))
	{
		$forumId = 1;
		$donnees = $managerForum->infosForum($forumId);
		$_SESSION['flash']['success'] = "Le forum n'existe pas , vous avez été redirigé ";
    }

    $forum = new Forum($donnees);
    $titre = $forum->name();

	include("../includes/debut.php");
	include("../includes/menu.php");
   /*On verifie si l'utilisateur a le droit le forum */

	if (!Membre::verif_auth($forum->auth_view()))
	{
		erreur(ERR_AUTH_VIEW);
	}

	$totalDesMessages = $forum->topics() + 1;
	$nombreDeMessagesParPage = 25;
	$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);

  echo '<ul class="fildariane">
    <li><a href="../index.php">Accueil</a></li>
    <li><a href="./index.php">Forum</a></li>
    <li><a href="./voirforum.php?f='.$forum->id().'">'.stripslashes(htmlspecialchars($forum->name())).'</a></li>
  </ul>

  <div class="page">';


	      echo '<h1 class="titre">'.stripslashes(htmlspecialchars($forum->name())).'</h1><br/>';

	$page = (isset($_GET['page']))?intval($_GET['page']):1;

	//On affiche les pages 1-2-3, etc.

	echo '<p class="pagination">';
           paginationListe($page ,$nombreDePages, 'voirforum.php?f='.$forum->id());
	echo '</p>';

	$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

	//Le titre du forum


	//Et le bouton pour poster


	if (Membre::verif_auth($forum->auth_topic()))
	{
		//Et le bouton pour poster
		echo'<p class="nouveau-sujet">
		         <a href="./poster.php?action=nouveautopic&amp;f='.$forum->id().'"><img src="../images/icones/new.png"/>Nouveau sujet </a>
		      </p>';
	}

	$jointures = $managerForum->ajoutJointuresViews($id);

    // cette grosse fonction prends plusieurs fonctions , les jointures pour la table de vus , l'id du forum , l'id du user , le type ('Annonce' ou 'message') , puis le premier message et le nombres :D

	$toutInfosSurCeForum = $managerForum->obtenirToutSurCeForum($jointures , $forum->id() ,$id, 'Annonce' ,$premierMessageAafficher , $nombreDeMessagesParPage);


	if (!empty($toutInfosSurCeForum))
	{

		foreach ($toutInfosSurCeForum as $ligneForum) {

		    $topic_view = new TopicView($ligneForum);
		    $topic = new Topic($ligneForum);
		    $createurTopic = new Membre($ligneForum);
		    $createurTopic->setId($ligneForum['createur']);

		    $posteur = new Membre($ligneForum);
		    $posteur->setId($ligneForum['createurPost']);

		    $posteur->setPseudo($ligneForum['pseudoPosteur']);

		    $post = new Post($ligneForum);
		    $post->setId($ligneForum['idPost']);


		    //Pour chaque topic :
		    //Si le topic est une annonce on l'affiche en haut
		    //mega echo de bourrain pour tout remplir

			if (!empty($id)) // Si le membre est connecté
			{
				if ($topic_view->tv_id() == $id) //S'il a lu le topic
				{
					if ($topic_view->tv_poste() == '0') // S'il n'a pas posté
					{
						if ($topic_view->tv_post_id() == $topic->last_post())
						//S'il n'y a pas de nouveau message
						{
							$ico_mess = 'message.png';
						}
						else
						{
							$ico_mess = 'messagec_non_lus.png'; //S'il y a un nouveau message
						}
					}
					else // S'il a posté
					{
						if ($topic_view->tv_post_id() == $topic->last_post())
						//S'il n'y a pas de nouveau message
						{
							 $ico_mess = 'messagep_lu.png';
						}
						else //S'il y a un nouveau message
						{
							$ico_mess = 'messagep_non_lu.png';
						}
					}
				}
				else //S'il n'a pas lu le topic
				{
					$ico_mess = 'message_non_lu.png';
				}
			}
			//S'il n'est pas connecté
			else
			{
				$ico_mess = 'message.png';
			}

			echo'<div class="sujet">
				     <p class="avatar_createur">
				        <img src="../images/avatars/'.$createurTopic->avatar().'" alt="Createur"/>
				     </p>

				     <p class="infossujet">
	                     <span> <a href="./voirtopic.php?t='.$topic->id().'" title="Topic commencé à '.$topic->topictime().'">'.stripslashes(htmlspecialchars($topic->titre())).'</a>
	                     </span>

	                     <span><a class="at" href="./voirprofil.php?m='.$topic->createur().'&amp;action=consulter">'.stripslashes(htmlspecialchars($createurTopic->pseudo())).'</a> <i> '.$topic->topictime().'</i>
	                     </span>

	                 </p>

				     <p class="vues">
				        <span><img src="../images/icones/vue.png"/></span>
				        <span>'.$topic->vus().'</span>
				     </p>

				     <p class="messages">
	                     <span><img src="../images/icones/mess.png"/></span>
	                     <span> '.$topic->posts().'</span>
				     </p>';

					//Selection dernier message

					$nombreDeMessagesParPage = 15;
					$nbr_post = $topic->posts() + 1 ;
					$page = ceil($nbr_post / $nombreDeMessagesParPage);


			echo '<p class="derniermessage">
			       <span>
			           Dernier message
			        </span>
			           <span> Par <a class="at" href="./voirprofil.php?m='.$post->createur().'&amp;action=consulter">'.stripslashes(htmlspecialchars($posteur->pseudo())).'</a>
			         A <a  href="./voirtopic.php?t='.$topic->id().'&amp;page='.$page.'#p_'.$post->id().'">'.$post->posttime().'</a>
			    </span>
			    </p>
			    <span class="typesujet"> A </span>
			 </div>';
		}
	}





   $toutInfosSurCeForum = $managerForum->obtenirToutSurCeForum($jointures , $forum->id() ,$id, 'Message' ,$premierMessageAafficher , $nombreDeMessagesParPage);

	if (!empty($toutInfosSurCeForum))
	{

		foreach ($toutInfosSurCeForum as $ligneForum) {

		    $topic_view = new TopicView($ligneForum);
		    $topic = new Topic($ligneForum);
		    $createurTopic = new Membre($ligneForum);
		    $createurTopic->setId($ligneForum['createur']);

		    $posteur = new Membre($ligneForum);
		    $posteur->setId($ligneForum['createurPost']);

		    $posteur->setPseudo($ligneForum['pseudoPosteur']);

		    $post = new Post($ligneForum);
		    $post->setId($ligneForum['idPost']);


		    //Pour chaque topic :
		    //Si le topic est une annonce on l'affiche en haut
		    //mega echo de bourrain pour tout remplir

			if (!empty($id)) // Si le membre est connecté
			{
				if ($topic_view->tv_id() == $id) //S'il a lu le topic
				{
					if ($topic_view->tv_poste() == '0') // S'il n'a pas posté
					{
						if ($topic_view->tv_post_id() == $topic->last_post())
						//S'il n'y a pas de nouveau message
						{
							$ico_mess = 'message.png';
						}
						else
						{
							$ico_mess = 'messagec_non_lus.png'; //S'il y a un nouveau message
						}
					}
					else // S'il a posté
					{
						if ($topic_view->tv_post_id() == $topic->last_post())
						//S'il n'y a pas de nouveau message
						{
							 $ico_mess = 'messagep_lu.png';
						}
						else //S'il y a un nouveau message
						{
							$ico_mess = 'messagep_non_lu.png';
						}
					}
				}
				else //S'il n'a pas lu le topic
				{
					$ico_mess = 'message_non_lu.png';
				}
			}
			//S'il n'est pas connecté
			else
			{
				$ico_mess = 'message.png';
			}

			echo'<div class="sujet">
				     <p class="avatar_createur">
				        <img src="../images/avatars/'.$createurTopic->avatar().'" alt="Createur"/>
				     </p>

				     <p class="infossujet">
	                     <span> <a href="./voirtopic.php?t='.$topic->id().'" title="Topic commencé à '.$topic->topictime().'">'.stripslashes(htmlspecialchars($topic->titre())).'</a>
	                     </span>

	                     <span><a class="at" href="./voirprofil.php?m='.$topic->createur().'&amp;action=consulter">'.stripslashes(htmlspecialchars($createurTopic->pseudo())).'</a> <i> '.$topic->topictime().'</i>
	                     </span>

	                 </p>

				     <p class="vues">
				        <span><img src="../images/icones/vue.png"/></span>
				        <span>'.$topic->vus().'</span>
				     </p>

				     <p class="messages">
	                     <span><img src="../images/icones/mess.png"/></span>
	                     <span> '.$topic->posts().'</span>
				     </p>';

					//Selection dernier message

					$nombreDeMessagesParPage = 15;
					$nbr_post = $topic->posts() + 1 ;
					$page = ceil($nbr_post / $nombreDeMessagesParPage);


			echo '<p class="derniermessage">
			       <span>
			           Dernier message
			        </span>
			           <span> Par <a class="at" href="./voirprofil.php?m='.$post->createur().'&amp;action=consulter">'.stripslashes(htmlspecialchars($posteur->pseudo())).'</a>
			         A <a  href="./voirtopic.php?t='.$topic->id().'&amp;page='.$page.'#p_'.$post->id().'">'.$post->posttime().'</a>
			    </span>
			    </p>
			 </div>';
		}
	}
    else
    {
        echo'<p>Ce forum ne contient aucun sujet actuellement</p>';
    }


  $page = (isset($_GET['page']))?intval($_GET['page']):1;

	//On affiche les pages 1-2-3, etc.

	echo '<p class="pagination">';
          paginationListe($page ,$nombreDePages, 'voirforum.php');
	echo '</p>';

echo '</div>';
include "../includes/footer.php";

?>

</body>
</html>
