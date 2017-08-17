<?php

include "../includes/session.php";
$titre="Traitement Post | SiteduSavoir.com";
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

//On récupère la valeur de la variable action

$action =(isset($_GET['action']))?htmlspecialchars($_GET['action']):'';
// Si le membre n'est pas connecté, il est arrivé ici par erreur
if ($id == 0) 
	erreur(ManagerMembre::ERR_IS_CO);

switch($action)
{
	//Premier cas : nouveau topic

	case "nouveautopic":
        
        $forum = (int) $_GET['f'];

        $managerTopic = new ManagerTopic($bdd);
        $managerPost = new ManagerPost($bdd);
        $topic = new Topic($_POST);

        $topic->setForum($forum);
        $topic->setCreateur($id);

        $post = new Post($_POST);
        $post->setCreateur($id);
        $post->setForum($forum);

        $managerPost->verifierChamps($post);
        $managerTopic->verifierChamps($topic);

		

		if (!empty($managerPost->errors()) || !empty($managerTopic->errors()))
		{
		    echo $managerPost->errors()['titre'] ;
		    echo $managerTopic->errors()['texte'] ;
		}
		else //Si jamais le message n'est pas vide
		{

			//On entre le topic dans la base de donnée en laissant et on renvoie l'id tu topic cree
			//le champ topic_last_post à 0

            $idTopic = $managerTopic->nouveautopic($topic);
		    $topic->setId($idTopic);
		    $post->setTopic($topic->id());

		    //Puis on entre le message dans la table post

		    $idPost  = $managerPost->nouveauPost($post);
		    $post->setId($idPost);

            //Ici on update comme prévu la valeur de topic_last_post et de topic_first_post

            $topic->setLast_post($post->id());
            $topic->setFirst_post($post->id());
            $managerTopic->diminuerNombrePostDuTopic($topic , 1); // on augmneter le nombres de post

            $topic->setVus(0);
            $topic->setLocked('0');

            $managerTopic->miseAjoursTopic($topic);

		    //Enfin on met à jour les tables forum et membres

            $managerForum = new ManagerForum($bdd);
            $managerForum->augmenterNombreTopicEtPost($topic->forum() , $topic->last_post());

            // La tables membres , ensuites

            $managerMembre = new ManagerMembre($bdd);
            $managerMembre->augmenterNombrePostMembre($id); 

            // La table vus des topics

            $donnees = array('tv_id' => $id , 'tv_topic_id' => $topic->id() , 'tv_forum_id' => $topic->forum() , 'tv_post_id' => $post->id() ,'tv_poste' => '1');

            $topic_view = new TopicView($donnees);
            $managerTopicView = new ManagerTopicView($bdd);

            $managerTopicView->nouvelleVu($topic_view);
            $_SESSION['flash']['success'] = 'Topic cree avec succes';

		    //Et un petit message
		    header('Location:./voirtopic.php?t='.$topic->id());

		}

    break;


    //Deuxième cas : répondre
    case "repondre":
        
        $idTopic = (int)$_GET['t'];
		$post = new Post($_POST);
		$post->setTopic($idTopic);

		$managerPost = new ManagerPost($bdd);
		$managerPost->verifierChamps($post);

		$managerTopic = new ManagerTopic($bdd);
		$donneesTopic = $managerTopic->infosTopic($post->topic());
		$topic = new Topic($donneesTopic);

		$post->setForum($topic->forum());
		$post->setCreateur($id);

		if($topic->locked() != 0)
		{
		    	erreur(ManagerTopic::ERR_TOPIC_VERR); 
		}

        if(!empty($managerPost->errors()))
		{
			echo $managerPost->errors()['texte']. ' </br> <a href="./poster.php?action=repondre&t='.$post->topic() ;
		}
		else
		{
			$idPost = $managerPost->nouveauPost($post);
			$post->setId($idPost);

            $topic->setLast_post($post->id());
            $managerTopic->diminuerNombrePostDuTopic($topic , 1); // on augmente de 1

            // On met a jour last post du topic
			$managerTopic->miseAjoursTopic($topic);

			//Puis même combat sur les 2 autres tables
			$managerForum = new ManagerForum($bdd);
			$managerForum->augmenterNombreTopicEtPost($topic->forum() , $post->id());

			// comme d'hab maintenant le membre :D

			$managerMembre = new ManagerMembre($bdd);
			$managerMembre->augmenterNombrePostMembre($id);
            

			$nombreDeMessagesParPage = 15;

			$nbr_post = $topic->posts() + 1;
			$page = ceil($nbr_post / $nombreDeMessagesParPage);

            $donnees = array('tv_id' => $id , 'tv_topic_id' => $topic->id() , 'tv_forum_id' => $topic->forum() , 'tv_post_id' => $post->id() ,'tv_poste' => '1');

            $topic_view = new TopicView($donnees);
            $managerTopicView = new ManagerTopicView($bdd);
            
			$managerTopicView->miseAjoursVu($topic_view);



			$_SESSION['flash']['success'] = 'Vous venez de repondre a un post';

			header('Location:./voirtopic.php?t='.$topic->id().'&page='.$page.'#p_'.$post->id());

		}

        //Fin du else

    break;

    case "resoudre":

    		$idTopic = (int)$_GET['t'];
    		$managerTopic = new ManagerTopic($bdd);
    		$managerTopic->topicResolu($idTopic);

    		header('Location:voirtopic.php?t='.$idTopic);

    break;

    case "edit": //Si on veut éditer le post

        //On récupère la valeur de p

        $texte = $_POST['texte'];
        $idPost = (int)$_GET['p'];

        $managerPost = new ManagerPost($bdd);
        $donneesPost = $managerPost->infosPost($idPost);

        $post = new Post($donneesPost);
        $post->setId($idPost);
        $forum = new Forum($donneesPost);
        $forum->setId($post->forum());

		//On récupère le message
		//Ensuite on vérifie que le membre a le droit d'être ici (soit le créateur soit un modo/admin)

	
		//On récupère la place du message dans le topic (pour le lien)

		$totalMessageAvant = $managerPost->positionDuPostEditer($post);

		if (!Membre::verif_auth($forum->auth_modo()) && $post->createur() != $id)
		{
		    // Si cette condition n'est pas remplie ça va barder :o
		    erreur(ManagerPost::ERR_AUTH_EDIT);
		}

		else //Sinon ça roule et on continue
		{
			$post->setTexte($texte);

			$managerPost->miseAjoursPost($post);
            $nbr_post = $totalMessageAvant + 1;
            $nombreDeMessagesParPage = 15;

		    $page = ceil($nbr_post / $nombreDeMessagesParPage);

			echo'<p>Votre message a bien été édité!<br /><br /> Cliquez <a href="./index.php">ici</a> pour revenir à l\'index du forum<br />
			Cliquez <a href="./voirtopic.php?t='.$post->topic().'&amp;page='.$page.'#p_'.$post->id().'">ici</a> pour le voir</p>';
			
		}

    break;

    case "delete": 

        //Si on veut supprimer le post
        //On récupère la valeur de p

        $idPost = (int)$_GET['p'];
        $managerPost = new ManagerPost($bdd);
        $managerForum = new ManagerForum($bdd);

        $managerMembre = new ManagerMembre($bdd);
        $donneesPost = $managerPost->infosPost($idPost);

        $post = new Post($donneesPost);
        $post->setId($idPost);

        $forum = new Forum($donneesPost);
        $forum->setId($post->forum());

        //Ensuite on vérifie que le membre a le droit d'être ici
        //(soit le créateur soit un modo/admin)

		if (!Membre::verif_auth($forum->auth_modo()) && $post->createur() != $id)
		{
		    // Si cette condition n'est pas remplie ça va barder :o
		    erreur(ManagerPost::ERR_AUTH_DELETE);
		}
		else //Sinon ça roule et on continue
		{

			//Ici on vérifie plusieurs choses :
			//est-ce un premier post ? Dernier post ou post classique ?

			$managerTopic = new ManagerTopic($bdd);
			$managerForum = new ManagerForum($bdd);

			$donneesTopic = $managerTopic->infosTopic($post->topic());
			$topic = new Topic($donneesTopic);

		    //On distingue maintenant les cas

		    if ($topic->first_post() == $post->id()) //Si le message est le premier
		    {

		        //Les autorisations ont changé !
		        //Normal, seul un modo peut décider de supprimer tout un topic

		        if (!Membre::verif_auth($forum->auth_modo()))
		        {
		            erreur(ManagerTopic::ERR_AUTH_DELETE_TOPIC);
		        }

		        //Il faut s'assurer que ce n'est pas une erreur

		        echo'<p>Vous avez choisi de supprimer un post. Cependant ce post est le premier du topic. Voulez vous supprimer le topic ? <br />
		                 <a href="./postok.php?action=delete_topic&amp;t='.$topic->id().'">oui</a> - <a href="./voirtopic.php?t='.$topic->id().'">non</a>
		            </p>';
            }

		    elseif ($topic->last_post() == $post->id()) //Si le message est le dernier
		    {

		        //On supprime le post

		        $managerPost->deletePost($post->id());

		        //On modifie la valeur de (topic) last_post pour cela on
		        //récupère l'id du plus récent message de ce topic

		        $donneesDernierPost = $managerPost->dernierPost($post->topic() , 'topic');
		        $dernierPostDuTopic = new Post($donneesDernierPost);

		        //On fait de même pour (forum) last_post_id

		        $donneesDernierPostDuForum = $managerPost->dernierPost($post->forum() , 'forum');
		        $dernierPostDuForum = new Post($donneesDernierPostDuForum);

		        //On met à jour la valeur de last_post pour le topic ;D

		        $topic->setLast_post($dernierPostDuTopic->id());
                $managerTopic->miseAjoursTopic($topic);

		        //On enlève 1 au nombre de messages du forum et on met à
		        //jour (forum) last_post_id

		        $forum->setLast_post_id($dernierPostDuForum->id());
                $managerForum->diminuerNombrePost($forum);
                
                //On enlève 1 au nombre de messages du topic
                $managerTopic->diminuerNombrePostDuTopic($topic);

                //On enlève 1 au nombre de messages du membre
		        
		        $managerMembre->diminuerNombrePostMembre($id);
		        //Enfin le message

		        echo'<p>Le message a bien été supprimé !<br /> Cliquez <a href="./voirtopic.php?t='.$topic->id().'">ici</a> pour retourner au topic<br />
		               Cliquez <a href="./index.php">ici</a> pour revenir à l\'index du forum
		             </p>';
		    }

		    else // Si c'est un post classique
		    {

		        //On supprime le post
                $managerPost->deletePost($post->id());
		       //On enlève 1 au nombre de messages du forum

                $donneesDernierPostDuForum = $managerPost->dernierPost($forum->id() , 'forum');
                $forum->setLast_post_id($donneesDernierPostDuForum['id']);

                $managerForum->diminuerNombrePost($forum);
                //On enlève 1 au nombre de messages du topic

                $managerTopic->diminuerNombrePostDuTopic($topic);
		       //On enlève 1 au nombre de messages du membre
                $managerMembre->diminuerNombrePostMembre($id);

		        echo'<p>Le message a bien été supprimé !<br />Cliquez <a href="./voirtopic.php?t='.$topic->id().'">ici</a> pour retourner au topic<br />
		                 Cliquez <a href="./index.php">ici</a> pour revenir à l\'index du forum
		             </p>';

		       
		    }

		} //Fin du else

		break;

        case "delete_topic":

			$idTopic = (int) $_GET['t'];
			$managerTopic = new ManagerTopic($bdd);
			$managerForum = new ManagerForum($bdd);

			$donneesTopic = $managerTopic->infosTopic($idTopic);
			$managerMembre = new ManagerMembre($bdd);

			$topic = new Topic($donneesTopic);
			$forum = new Forum($donneesTopic);
			$forum->setId($topic->forum());

			//Ensuite on vérifie que le membre a le droit d'être ici
			//c'est-à-dire si c'est un modo / admin

			if (!Membre::verif_auth($forum->auth_modo()))
			{
			    erreur(ManagerTopic::ERR_AUTH_DELETE_TOPIC);
			}
			else //Sinon ça roule et on continue
			{
                //On compte le nombre de post du topic
			    $nombrepost = $topic->posts() + 1;

                //On supprime le topic

	            $managerTopic->deleteTopic($topic->id());
                $managerPost = new ManagerPost($bdd);
                $datas = $managerPost->nombreDePostParMembreDuTopic($topic->id());

				foreach ($datas as $data){

					$infosMembre = $managerMembre->infosMembre($data['createur']);
					$membreCreateur = new Membre($infosMembre);

					$membreCreateur->setPosts($membreCreateur->posts() - $data['nombre_mess']);
					$managerMembre->miseAjoursMembre($membreCreateur); 

				}

				// Les post du topic

				$managerPost->deletePostsDuTopic($topic->id());

				//Dernière chose, on récupère le dernier post du forum
				$donneesDernierPostDuForum = $managerPost->dernierPost($forum->id() , 'forum');
				$dernierPost = new Post($donneesDernierPostDuForum);

			    $forum->setLast_post_id($dernierPost->id());
			    $managerForum->diminuerNombrePostEtTopic($forum , $nombrepost);

				//Enfin le message
				echo'<p>Le topic a bien été supprimé !<br />
				Cliquez <a href="./index.php">ici</a> pour revenir à l\'index du forum</p>';
			}
    break;

    case "lock": //Si on veut verrouiller le topic

		//On récupère la valeur de t

		$idTopic = (int) $_GET['t'];

		$managerTopic = new ManagerTopic($bdd);
		$donneesTopic = $managerTopic->infosTopic($idTopic);
		$topic = new Topic($donneesTopic);
		$forum = new Forum($donneesTopic);
		$forum->setId($topic->forum());

		//Ensuite on vérifie que le membre a le droit d'être ici

		if (!Membre::verif_auth($forum->auth_modo()))
		{
		    // Si cette condition n'est pas remplie ça va barder :o
		    erreur(ManagerTopic::ERR_AUTH_VERR);
		}
		else //Sinon ça roule et on continue
		{

		    //On met à jour la valeur de topic_locked
			$topic->setLocked('1');

			var_dump($topic);
			$managerTopic->miseAjoursTopic($topic);

			echo'<p>Le topic a bien été verrouillé ! <br />Cliquez <a href="./voirtopic.php?t='.$topic->id().'">ici</a> pour retourner au topic<br />
			Cliquez <a href="./index.php">ici</a> pour revenir à l index du forum</p>';
		}

    break;



    case "unlock": //Si on veut déverrouiller le topic
        
        $idTopic = (int) $_GET['t'];

		$managerTopic = new ManagerTopic($bdd);
		$donneesTopic = $managerTopic->infosTopic($idTopic);
		$topic = new Topic($donneesTopic);
		$forum = new Forum($donneesTopic);
		$forum->setId($topic->forum());

		//Ensuite on vérifie que le membre a le droit d'être ici

		if (!Membre::verif_auth($forum->auth_modo()))
		{
		    // Si cette condition n'est pas remplie ça va barder :o
		    erreur(ManagerTopic::ERR_AUTH_VERR);
		}
		else //Sinon ça roule et on continue
		{

		    //On met à jour la valeur de topic_locked
			$topic->setLocked('0');
			$managerTopic->miseAjoursTopic($topic);

			echo'<p>Le topic a bien été verrouillé ! <br />Cliquez <a href="./voirtopic.php?t='.$topic->id().'">ici</a> pour retourner au topic<br />
			Cliquez <a href="./index.php">ici</a> pour revenir à l index du forum</p>';
		}

    break;


    case "deplacer":

		$idTopic = (int) $_GET['t'];
		$managerTopic = new ManagerTopic($bdd);
        $managerForum = new ManagerForum($bdd);

		$donneesTopic = $managerTopic->infosTopic($idTopic);
        $topic = new Topic($donneesTopic);
		$donneesForum = $managerForum->infosForum($topic->forum());

		$forum = new Forum($donneesForum);
		if (!Membre::verif_auth($forum->auth_modo()))
		{
		    // Si cette condition n'est pas remplie ça va barder :o
		    erreur(ERR_AUTH_MOVE);
		}
		else //Sinon ça roule et on continue
		{

			$destination = (int) $_POST['dest'];
			$origine = (int) $_POST['from'];

			//On déplace le topic vers la destination (Nouveau Forum)
            
            $topic->setForum($destination);
            $managerTopic->changerForum($topic->id() , $topic->forum());
            $managerPost = new ManagerPost($bdd);

            //On déplace les posts versle nouveau forum 
			$managerPost->deplacerPostsVersForum($topic);

		    //On s'occupe d'ajouter / enlever les nombres de post /topic aux
			//forum d'origine et de destination
			//Pour cela on compte le nombre de post déplacé

            $nombrepost = $managerPost->nombrePostPourCeTopic($topic->id());

			//Il faut également vérifier qu'on a pas déplacé un post qui été
			//l'ancien premier post du forum (champ last_post_id)

			$donneesDernierPostDuForum = $managerPost->dernierPost($origine, 'forum');
			$dernierPostDuForum = new Post($donneesDernierPostDuForum);

			//Puis on met à jour le forum d'origine diminuants le nombres de post et topic 

			
			$forum->setLast_post_id($dernierPostDuForum->id());
            $managerForum->diminuerNombrePostEtTopic($forum , $nombrepost);

			//Avant de mettre à jour le forum de destination il faut
			//vérifier la valeur de forum_last_post_id

			$donneesdernierPostDuForum = $managerPost->dernierPost($destination , 'forum');
			$dernierPostDuForum = new Post($donneesdernierPostDuForum);

			//Et on met à jour enfin !
			$infosDestination = $managerForum->infosForum($destination);
			$forumDestination = new Forum($infosDestination);

			$forumDestination->setLast_post_id($dernierPostDuForum->id());
			$managerForum->augmenterNombreTopicEtPost($forumDestination->id() ,$forum->last_post_id() , $nombrepost);
			
			//C'est gagné ! On affiche le message

			echo'<p>Le topic a bien été déplacé <br /> Cliquez <a href="./voirtopic.php?t='.$topic->id().'">ici</a> pour revenir au topic<br />
			Cliquez <a href="./index.php">ici</a> pour revenir à l\'index du forum</p>';
		
		}

    break;


    case "autorep":

        $idTopic = (int) $_GET['t'];
        $managerTopic = new ManagerTopic($bdd);
        $donneesTopic = $managerTopic->infosTopic($idTopic);

        $topic = new Topic($donneesTopic);
        $forum = new Forum($donneesTopic);
        $forum->setId($topic->forum());


		if($topic->locked() == 1)
		{
			$_SESSION['flash']['danger'] = 'Topic déja verrouillé';
			header('Location:voirtopic.php?t='.$topic->id());
		}

		if (!Membre::verif_auth($forum->auth_modo()))
		{
		    erreur(ManagerTopic::ERR_AUTH_MODO);
		}

		$rep = (int) $_POST['rep'];
        $managerAutoMessage = new ManagerAutoMessage($bdd);
        $managerPost = new ManagerPost($bdd);

        $donneesAutoMessage = $managerAutoMessage->infosAutoMessage($rep);
        $autoMessage = new AutoMessage($donneesAutoMessage);

        $post = new Post(array('createur' => $id , 'texte' => $autoMessage->message() ,'topic' => $topic->id() ,'forum' => $forum->id()));
        $post->setId($managerPost->nouveauPost($post)); // on cree un nouveau post dans la bdd et retourne l'id du post
        

        // le nouveau post devient le dernier du topic ;)
        $topic->setLast_post($post->id());

		//On change un peu la table forum_topic

		$managerTopic->diminuerNombrePostDuTopic($topic , 1); // on l'augmente ici de 1 , pour diminuer cest -1 
		//Puis même combat sur les 2 autres tables

		$managerForum = new ManagerForum($bdd);
		$forum->setLast_post_id($post->id());
		$managerForum->diminuerNombrePost($forum , 1);  //on l'augmente ici de 1 , pour diminuer cest -1

		$managerMembre = new ManagerMembre($bdd);
		$managerMembre->diminuerNombrePostMembre($id , 1);

		$topic->setLocked('1');

		$managerTopic->miseAjoursTopic($topic);

		echo '<p>
				La réponse automatique a bien été envoyée ! <br />
				Cliquez <a href="./voirtopic.php?t='.$topic->id().'">ici</a> pour revenir au topic<br />
				Cliquez <a href="./index.php">ici</a> pour revenir à l\'index du forum
		    </p>';

    break;

    default:
        echo'<p>Cette action est impossible</p>';

} 
//Fin du Switch
?>
</div>
</body>
</html>
