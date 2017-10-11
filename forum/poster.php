<?php
include "../includes/session.php";

$titre="Poster";
$balises = true;

include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

//Qu'est ce qu'on veut faire ? poster, répondre ou éditer ?

$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';

//Il faut être connecté pour poster !

if ($id == 0)
	erreur(ManagerMembre::ERR_IS_CO);

//Si on veut poster un nouveau topic, la variable f se trouve dans l'url,
//On récupère certaines valeurs

if(isset($_GET['f'])){

    $idForum = (int)$_GET['f'];
	$managerForum = new ManagerForum($bdd);
	$donneesForum = $managerForum->infosForum($idForum);
	$forum = new Forum($donneesForum);

	echo '<ul class="fildariane">
    <li><a href="../index.php">Accueil</a></li>
    <li><a href="./index.php">Forum</a></li>
    <li><a href="./voirforum.php?f='.$forum->id().'">'.stripslashes(htmlspecialchars($forum->name())).'</a></li>
    <li><span>Nouveau topic</span></li>
  </ul>

  <div class="page">';
}


   //Sinon c'est un nouveau message, on a la variable t et
   //On récupère f grâce à une requête

elseif (isset($_GET['t']))
{
	$idTopic = (int) $_GET['t'];
	$managerTopic = new ManagerTopic($bdd);

	$donneesTopic = $managerTopic->infosTopic($idTopic);
	$topic = new Topic($donneesTopic);

	$forum = new Forum($donneesTopic);
	$forum->setId($topic->forum());

	echo '<ul class="fildariane">
    <li><a href="../index.php">Accueil</a></li>
    <li><a href="./index.php">Forum</a></li>
    <li><a href="./voirforum.php?f='.$forum->id().'">'.stripslashes(htmlspecialchars($forum->name())).'</a></li>
    <li><a href="./voirtopic.php?t='.$topic->id().'">'.stripslashes(htmlspecialchars($topic->titre())).'</a></li>
    <li><span>Répondre</span></li>
  </ul>

  <div class="page">';
}

    //Enfin sinon c'est au sujet de la modération(on verra plus tard en détail)
      //On ne connait que le post, il faut chercher le reste

elseif (isset ($_GET['p']))
{
	$idPost = (int) $_GET['p'];

	$managerPost = new ManagerPost($bdd);
    $donneesPost = $managerPost->infosPost($idPost);

    $post = new Post($donneesPost);
    $post->setID($idPost);

    $forum = new Forum($donneesPost);
    $forum->setID($post->forum());

    $topic = new Topic($donneesPost);

	echo '<ul class="fildariane">
    <li><a href="../index.php">Accueil</a></li>
    <li><a href="./index.php">Forum</a></li>
    <li><a href="./voirforum.php?f='.$forum->id().'">'.stripslashes(htmlspecialchars($forum->name())).'</a></li>
    <li><a href="./voirtopic.php?t='.$topic->id().'">'.stripslashes(htmlspecialchars($topic->titre())).'</a></li>
    <li><span>Modérer un message</span><li>
  </ul>

  <div class="page">';
}


switch($action)
{
    case "repondre":
		 //Premier cas : on souhaite répondre
		//Ici, on affiche le formulaire de réponse
		?>

		<h1 class="titre">Poster une réponse</h1>

		<div class="formulaire">

			<form method="post" name="formulaire"  action="postok.php?action=repondre&amp;t=<?php echo $topic->id() ?>">


				    <?php include "../includes/miseenforme.php"; ?>

				<fieldset>
				      <legend>Message</legend>

				 <div class="textarea">
				        <textarea  name="texte">Repondre</textarea>
				 </div>

				 <div class="submit submit-tuto">
					<input type="submit" name="submit" value="Envoyer" />
				</div>
				<div class="submit submit-tuto">
					<input type="reset" name = "Effacer" value ="Effacer"/>
				</div>

               </fieldset>

		 </form>
		</div>
		</div>

		<?php
		  include "../includes/footer.php";

    break;
		//Ici, on affiche le formulaire de nouveau topic
		?>

     <?php


    case "nouveautopic":

			?>

			<h1 class="titre"> Nouveau topic </h1>

			<div class="formulaire">

                 <form method="post" action="postok.php?action=nouveautopic&amp;f=<?php echo $forum->id();?>" name="formulaire">

					<fieldset>

					      <legend>Titre</legend>

							<div class="input">
		                       <label for="titre"></label>
							   <input type="text"  name="titre" placeholder="Votre titre" required />
							</div>
					</fieldset>

					<?php include "../includes/miseenforme.php"; ?>

					<fieldset>
					      <legend>Message</legend>

                          <div class="textarea">
					          <textarea  name="texte" required ></textarea>
					       </div>

					<?php

					if (Membre::verif_auth($forum->auth_annonce()))
					{
					?>
					   <label><input type="radio" name="genre" value="Annonce"/>Annonce</label>

					<?php
					}
					?>
					<label><input type="radio" name="genre" value="Message" checked="checked" />Topic</label><br />

					<div class="submit submit-tuto">
                         <input type="submit" name="submit" value="Envoyer" />
					</div>
					<div class="submit submit-tuto">
					   <input type="reset" name ="Effacer" value ="Effacer" />
					</div>
					</fieldset>

			</form>
		</div>
		</div>
     <?php
          include "../includes/footer.php";
    break;


    case "edit":

        //Si on veut éditer le post
        //On récupère la valeur de p

        $idPost = (int) $_GET['p'];

        echo'<h2 class="titre">Edition</h2>';

        //On lance enfin notre requête

	    $managerPost = new ManagerPost($bdd);
	    $donneesPost = $managerPost->infosPost($idPost);
	    $post = new Post($donneesPost);
	    $forum = new Forum($donneesPost);

		//On récupère le message
		//Ensuite on vérifie que le membre a le droit d'être ici (soit le créateur soit un modo/admin)

		if (!Membre::verif_auth($forum->auth_modo()) && $post->createur() != $id)
		{
		   // Si cette condition n'est pas remplie ça va barder :o
		    erreur(Membre::ERR_AUTH_EDIT);
		}

		else //Sinon ça roule et on affiche la suite
		{
		   //Le formulaire de postage
		?>
		<div class="formulaire">
			<form method="post" name="formulaire" action="postok.php?action=edit&amp;p=<?php echo $post->id(); ?>">


			    <?php include "../includes/miseenforme.php"; ?>

			<fieldset>
			    <legend>Message</legend>

		        <div class="textarea">
			          <textarea name="texte"><?php echo trim(htmlspecialchars($post->texte())); ?></textarea>
			     </div>

			     <div class="submit submit-tuto">
			       <input type="submit" value="Editer !" />
			    </div>
			   <div class="submit submit-tuto">
			       <input type="reset" value = "Effacer"/>
			  </div>
			</fieldset>

			</form>
		</div>

		</div>

		<?php
		   include "../includes/footer.php";
		}

    break;

	case "delete":

	    //Si on veut supprimer le post
		//On récupère la valeur de p

		$idPost = (int) $_GET['p'];
		//Ensuite on vérifie que le membre a le droit d'être ici

		echo'<h1> Suppression </h1>';

		$managerPost = new ManagerPost($bdd);
		$donneesPost = $managerPost->infosPost($idPost);

		$post = new Post($donneesPost);
		$post->setId($idPost);
		$forum = new Forum($donneesPost);
		$forum->setID($post->forum());





		if (!Membre::verif_auth($forum->auth_modo()) && $post->createur() != $id)
		{
			// Si cette condition n'est pas remplie ça va barder :o
			erreur(ManagerPost::ERR_AUTH_DELETE);
		}

		else //Sinon ça roule et on affiche la suite
		{
			echo'<p>
			        Êtes vous certains de vouloir supprimer ce post ?
			    </p>';

			echo'<p>
			         <a href="./postok.php?action=delete&amp;p='.$post->id().'">Oui</a> ou <a href="./voirtopic.php?t='.$topic->id().'">Non</a>
			    </p>';
		}


	break;


default: //Si jamais c'est aucun de ceux là c'est qu'il y a eu unproblème :o
echo'<p>Cette action est impossible</p>';
} //Fin du switch
?>

</body>
</html>
