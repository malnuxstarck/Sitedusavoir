<?php
	include '../includes/session.php';
	$titre="Voir un forum";
	include("../includes/identifiants.php");
	include("../includes/debut.php");
	include("../includes/menu.php");

	//On récupère la valeur de f
	$forum = (int) $_GET['f'];

	//A partir d'ici, on va compter le nombre de messages
	//pour n'afficher que les 25 premiers

	$query = $bdd->prepare('SELECT forum_name, forum_topic, auth_view,auth_topic 
		                    FROM forum 
		                    WHERE forum_id = :forum');

	$query->bindValue(':forum',$forum,PDO::PARAM_INT);
	$query->execute();
	$data = $query->fetch();


	if (!verif_auth($data['auth_view']))
	{
		erreur(ERR_AUTH_VIEW);
	}

	$totalDesMessages = $data['forum_topic'] + 1;
	$nombreDeMessagesParPage = 25;
	$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);

		echo '<div class="fildariane">
		          <ul>
				       <li> 
				           <a href="../index.php">Accueil</a>
				       </li>
			             <img class="fleche" src="../images/icones/fleche.png"/>

				          <li>
				             <a href="./index.php">Forum</a>
				          <li>

				          <img class="fleche" src="../images/icones/fleche.png"/>

				          <li>
				               <a href="./voirforum.php?f='.$forum.'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
				          <li>
		        </ul>

		     </div>

	      <div class="page">';


	      echo '<h1 class="titre">'.stripslashes(htmlspecialchars($data['forum_name'])).'</h1><br/>';

	$page = (isset($_GET['page']))?intval($_GET['page']):1;

	//On affiche les pages 1-2-3, etc.

	echo '<p class="pagination">';

	for ($i = 1 ; $i <= $nombreDePages ; $i++)
	{
		if ($i == $page) //On ne met pas de lien sur la page actuelle
		{
			echo '<strong>'.$i.'</strong>';
		} 
		else 
		{
			echo '<a href="voirforum.php?f='.$forum.'&amp;page='.$i.'">'.$i.'</a>';
		}
	}
	echo '</p>';

	$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

	//Le titre du forum

	
	//Et le bouton pour poster


	if (verif_auth($data['auth_topic']))
	{
		//Et le bouton pour poster
		echo'<p class="nouveau-sujet">
		         <a href="./poster.php?action=nouveautopic&amp;f='.$forum.'"><img src="../images/icones/new.png"/>Nouveau sujet </a>
		      </p>';
	}

	$add1='';
	$add2 ='';

	if ($id!=0) //on est connecté
	{
		//Premièrement, sélection des champs
		$add1 = ',tv_id, tv_post_id, tv_poste';

		//Deuxièmement, jointure
		$add2 = 'LEFT JOIN forum_topic_view
		         ON forum_topic.topic_id = forum_topic_view.tv_topic_id 
		         AND forum_topic_view.tv_id = :id';
	}

	$query = $bdd->prepare('SELECT forum_topic.topic_id, topic_titre,topic_createur, topic_vu, topic_post, topic_time, topic_last_post, Mb.membre_pseudo 
		                   AS membre_pseudo_createur, Mb.membre_avatar AS avatar_createur, post_createur,post_time, Ma.membre_pseudo 
		                   AS membre_pseudo_last_posteur,post_id '.$add1.' FROM forum_topic
	                       LEFT JOIN membres Mb ON Mb.membre_id = forum_topic.topic_createur
	                       LEFT JOIN forum_post ON forum_topic.topic_last_post = forum_post.post_id
	                       LEFT JOIN membres Ma ON Ma.membre_id = forum_post.post_createur
	                      '.$add2.' 
	                      WHERE topic_genre = "Annonce" AND forum_topic.forum_id =:forum ORDER BY topic_last_post DESC');

	$query->bindParam(':forum',$forum,PDO::PARAM_INT);

	if($id!=0)
	$query->bindParam(':id',$id,PDO::PARAM_INT);
	$query->execute();

	//On lance notre tableau seulement s'il y a des requêtes !

	if ($query->rowCount()>0)
	{

		while ($data=$query->fetch())
		{
			
		//Pour chaque topic :
		//Si le topic est une annonce on l'affiche en haut
		//mega echo de bourrain pour tout remplir

			if (!empty($id)) // Si le membre est connecté
			{
				if ($data['tv_id'] == $id) //S'il a lu le topic
				{
					if ($data['tv_poste'] == '0') // S'il n'a pas posté
					{
						if ($data['tv_post_id'] == $data['topic_last_post'])
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
						if ($data['tv_post_id'] == $data['topic_last_post'])
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
				        <img src="../images/avatars/'.$data['avatar_createur'].'" alt="Createur"/>
				     </p>

				     <p class="infossujet">
	                     <span> <a href="./voirtopic.php?t='.$data['topic_id'].'" title="Topic commencé à '.$data['topic_time'].'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
	                     </span>

	                     <span> Par  <a class="at" href="./voirprofil.php?m='.$data['topic_createur'].'&amp;action=consulter">'.stripslashes(htmlspecialchars($data['membre_pseudo_createur'])).'</a> <i> '.$data['topic_time'].'</i>
	                     </span>

	                 </p>

				     <p class="vues">
				        <span><img src="../images/icones/vue.png"/></span>
				        <span>'.$data['topic_vu'].'</span>
				     </p>

				     <p class="messages">
	                     <span><img src="../images/icones/mess.png"/></span>
	                     <span> '.$data['topic_post'].'</span>
				     </p>';

					//Selection dernier message
					$nombreDeMessagesParPage = 15;
					$nbr_post = $data['topic_post'] + 1 ;
					$page = ceil($nbr_post / $nombreDeMessagesParPage);


			echo '<p class="derniermessage">
			       <span> 
			           Dernier message 
			        </span>   
			           <span> Par <a class="at" href="./voirprofil.php?m='.$data['post_createur'].'&amp;action=consulter">'.stripslashes(htmlspecialchars($data['membre_pseudo_last_posteur'])).'</a>
			         A <a  href="./voirtopic.php?t='.$data['topic_id'].'&amp;page='.$page.'#p_'.$data['post_id'].'">'.$data['post_time'].'</a>
			    </span>
			    </p>
			    <span class="typesujet">Annonce</span>
			 </div>';
		}
	}

	$query->CloseCursor();
?>

<?php

	$add1='';
	$add2 ='';

	if ($id!=0) //on est connecté
	{
		//Premièrement, sélection des champs

		$add1 = ',tv_id, tv_post_id, tv_poste';
		//Deuxièmement, jointure
		$add2 = 'LEFT JOIN forum_topic_view
		ON forum_topic.topic_id = forum_topic_view.tv_topic_id AND
		forum_topic_view.tv_id = :id';
	}


	//On prend tout ce qu'on a sur les topics normaux du forum
	$query = $bdd->prepare('SELECT forum_topic.topic_id, topic_titre, topic_createur,topic_vu, topic_post,DATE_FORMAT(topic_time,\'%d/%m/%Y %h:%i:%s\') AS topic_time , topic_last_post,
	Mb.membre_pseudo AS membre_pseudo_createur, post_id, post_createur, post_time, Mb.membre_avatar AS avatar_createur,
	Ma.membre_pseudo AS membre_pseudo_last_posteur '.$add1.' FROM forum_topic
	LEFT JOIN membres Mb ON Mb.membre_id = forum_topic.topic_createur
	LEFT JOIN forum_post ON forum_topic.topic_last_post = forum_post.post_id
	LEFT JOIN membres Ma ON Ma.membre_id = forum_post.post_createur
	'.$add2.'
	WHERE topic_genre <> "Annonce" AND forum_topic.forum_id = :forum
	ORDER BY topic_last_post DESC
	LIMIT :premier ,:nombre');
	$query->bindValue(':forum',$forum,PDO::PARAM_INT);

	if($id!=0)
	{
		$query->bindParam(':id',$id,PDO::PARAM_INT);
	}

	$query->bindValue(':premier',(int) $premierMessageAafficher,PDO::PARAM_INT);
	$query->bindValue(':nombre',(int) $nombreDeMessagesParPage,PDO::PARAM_INT);
	$query->execute();

	if ($query->rowCount()>0)
	{

	//On lance la boucle

	while ($data = $query->fetch())
	{
  	if (!empty($id)) // Si le membre est connecté
		{
  		if ($data['tv_id'] == $id) //S'il a lu le topic
  		{
  			if ($data['tv_poste'] == '0') // S'il n'a pas posté
  			{
  				if ($data['tv_post_id'] == $data['topic_last_post'])
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
  				if ($data['tv_post_id'] == $data['topic_last_post'])
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
	    else
	      { //S'il n'a pas lu le topic	{
		    $ico_mess = 'message_non_lu.png';
		  }
    }
    //S'il n'est pas connecté
    else
    {
      $ico_mess = 'message.png';
    }

	  //Ah bah tiens... re vla l'echo de fou
		echo'<div class="sujet">
				     <p class="avatar_createur">
				        <img src="../images/avatars/'.$data['avatar_createur'].'" alt="Createur"/>
				     </p>

				     <p class="infossujet">
	                     <span> <a href="./voirtopic.php?t='.$data['topic_id'].'" title="Topic commencé à '.$data['topic_time'].'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
	                     </span>

	                     <span> Par  <a class="at" href="./voirprofil.php?m='.$data['topic_createur'].'&amp;action=consulter">'.stripslashes(htmlspecialchars($data['membre_pseudo_createur'])).'</a> <i> '.$data['topic_time'].'</i>
	                     </span>

	                 </p>

				     <p class="vues">
				        <span><img src="../images/icones/vue.png"/></span>
				        <span>'.$data['topic_vu'].'</span>
				     </p>

				     <p class="messages">
	                     <span><img src="../images/icones/mess.png"/></span>
	                     <span> '.$data['topic_post'].'</span>
				     </p>';

					//Selection dernier message
					$nombreDeMessagesParPage = 15;
					$nbr_post = $data['topic_post'] + 1 ;
					$page = ceil($nbr_post / $nombreDeMessagesParPage);


			echo '<p class="derniermessage">
			       <span> 
			           Dernier message 
			        </span>   
			           <span> Par <a class="at" href="./voirprofil.php?m='.$data['post_createur'].'&amp;action=consulter">'.stripslashes(htmlspecialchars($data['membre_pseudo_last_posteur'])).'</a>
			         A <a  href="./voirtopic.php?t='.$data['topic_id'].'&amp;page='.$page.'#p_'.$data['post_id'].'">'.$data['post_time'].'</a>
			    </span>
			    </p>
			 </div>';

  }
}

  else
  {
    echo'<p>Ce forum ne contient aucun sujet actuellement</p>';
  }

  $query->CloseCursor();

  $page = (isset($_GET['page']))?intval($_GET['page']):1;

	//On affiche les pages 1-2-3, etc.

	echo '<p class="pagination">';

	for ($i = 1 ; $i <= $nombreDePages ; $i++)
	{
		if ($i == $page) //On ne met pas de lien sur la page actuelle
		{
			echo '<strong>'.$i.'</strong>';
		} 
		else 
		{
			echo '<a href="voirforum.php?f='.$forum.'&amp;page='.$i.'">'.$i.'</a>';
		}
	}
	echo '</p>';

echo '</div>';
include "../includes/footer.php";
?>

</body>
</html>
