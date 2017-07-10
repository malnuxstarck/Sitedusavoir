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

if (isset($_GET['f']))
{

	$forum = (int) $_GET['f'];
	$query= $bdd->prepare('SELECT forum_id ,forum_name, auth_view, auth_post, auth_topic,auth_annonce, auth_modo
	                       FROM forum 
	                       WHERE forum_id =:forum');

	$query->bindValue(':forum',$forum,PDO::PARAM_INT);
	$query->execute();
	$data = $query->fetch();

	echo '<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Forum</a></li><img class="fleche" src="../images/icones/fleche.png" /> <li><a href="./voirforum.php?f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><span style="color:black;">Nouveau topic</span><li>
         </ul>
       </div>

       <div class="page">';
}

   //Sinon c'est un nouveau message, on a la variable t et
   //On récupère f grâce à une requête

elseif (isset($_GET['t']))
{
	$topic = (int) $_GET['t'];
	$query=$bdd->prepare('SELECT topic_titre, forum_topic.forum_id,forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
	                      FROM forum_topic
	                      LEFT JOIN forum 
	                      ON forum.forum_id = forum_topic.forum_id
	                      WHERE topic_id =:topic');
	$query->bindValue(':topic',$topic,PDO::PARAM_INT);

	$query->execute();
	$data = $query->fetch();

	$forum = $data['forum_id'];

	echo '<div class="fildariane">
	           <ul>
	               <li> <a href="../index.php">Accueil</a></li>
	               <img class="fleche" src="../images/icones/fleche.png"/>

	               <li>
	                    <a href="./index.php">Forum</a><li> <img class="fleche" src="../images/icones/fleche.png"/> <li><a href="./voirforum.php?f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
	               </li>
	                    <img class="fleche" src="../images/icones/fleche.png"/>

	               <li>
	                  <a href="./voirtopic.php?t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
	               </li>
	                <img class="fleche" src="../images/icones/fleche.png"/>
	                <li><span style="color:black;">Répondre</span></li>
	           </ul>     
	     </div>
	     <div class="page">';
}

    //Enfin sinon c'est au sujet de la modération(on verra plus tard en détail)
      //On ne connait que le post, il faut chercher le reste

elseif (isset ($_GET['p']))
{
	$post = (int) $_GET['p'];

	$query = $bdd->prepare('SELECT post_createur, forum_post.topic_id, topic_titre,forum_topic.forum_id,forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
	                        FROM forum_post
	                        LEFT JOIN forum_topic 
	                        ON forum_topic.topic_id = forum_post.topic_id
	                        LEFT JOIN forum 
	                        ON forum.forum_id = forum_topic.forum_id
	                        WHERE forum_post.post_id = :post');

	$query->bindValue(':post',$post,PDO::PARAM_INT);
	$query->execute();

	$data = $query->fetch();
	$topic = $data['topic_id'];
	$forum = $data['forum_id'];

	echo '<div class="fildariane">
	           <li>
	              <a href="./index.php">Forum</a>
	           </li>
	           <img class="fleche" src="../images/icones/fleche.png"/>
	           <li>
	                 <a href="./voirforum.php?f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
	           </li>
	           <img class="fleche" src="../images/icones/fleche.png"/>
               <li> 
                    <a href="./voirtopic.php?t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
               </li>
	          <img class="fleche" src="../images/icones/fleche.png"/>
	               <li><span style="color:black;">Modérer un message</span>
	          <li>
	    </div>
	    <div class="page">';
}

$query->CloseCursor();



 switch($action)
{
    case "repondre":
		 //Premier cas : on souhaite répondre
		//Ici, on affiche le formulaire de réponse
		?>
		
		<h1 class="titre">Poster une réponse</h1>

		<div class="formulaire">

			<form method="post" name="formulaire"  action="postok.php?action=repondre&amp;t=<?php echo $topic ?>">
				

				    <?php include "../includes/miseenforme.php"; ?>
				
				<fieldset>
				      <legend>Message</legend>

				 <div class="textarea">
				        <textarea  name="message">Repondre</textarea>
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

                 <form method="post" action="postok.php?action=nouveautopic&amp;f=<?php echo $forum;?>" name="formulaire">

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
					          <textarea  name="message" required ></textarea>
					       </div>   

					<?php
					if (verif_auth($data['auth_annonce']))
					{
					?>
					   <label><input type="radio" name="mess" value="Annonce"/>Annonce</label>

					<?php
					}
					?>
					<label><input type="radio" name="mess" value="Message" checked="checked" />Topic</label><br />

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

$post = (int) $_GET['p'];

echo'<h2 class="titre">Edition</h2>';

//On lance enfin notre requête

$query = $bdd->prepare('SELECT post_createur, post_texte, auth_modo
               FROM forum_post  
               LEFT JOIN forum 
               ON forum_post.post_forum_id = forum.forum_id
               WHERE post_id=:post');

$query->bindValue(':post',$post,PDO::PARAM_INT);
$query->execute();
$data = $query->fetch();

$text_edit = $data['post_texte'];

//On récupère le message
//Ensuite on vérifie que le membre a le droit d'être ici (soit le créateur soit un modo/admin)

if (!verif_auth($data['auth_modo']) && $data['post_createur'] != $id)
{
   // Si cette condition n'est pas remplie ça va barder :o
    erreur(ERR_AUTH_EDIT);
}

else //Sinon ça roule et on affiche la suite
{
   //Le formulaire de postage
?>
<div class="formulaire">
	<form method="post" name="formulaire" action="postok.php?action=edit&amp;p=<?php echo $post; ?>">

    
	    <?php include "../includes/miseenforme.php"; ?>
     
	<fieldset>
	    <legend>Message</legend>

        <div class="textarea">
	          <textarea name="message"><?php echo trim(htmlspecialchars($text_edit)); ?></textarea>
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

case "delete": //Si on veut supprimer le post
//On récupère la valeur de p
$post = (int) $_GET['p'];
//Ensuite on vérifie que le membre a le droit d'être ici
echo'<h1> Suppression </h1>';

$query = $bdd->prepare('SELECT post_createur, auth_modo
                        FROM forum_post
                        LEFT JOIN forum 
                        ON forum_post.post_forum_id = forum.forum_id
                        WHERE post_id= :post');
$query->bindValue(':post',$post,PDO::PARAM_INT);
$query->execute();
$data = $query->fetch();

if (!verif_auth($data['auth_modo']) && $data['post_createur'] != $id)
{
	// Si cette condition n'est pas remplie ça va barder :o
	erreur(ERR_AUTH_DELETE);
}

else //Sinon ça roule et on affiche la suite
{
	echo'<p>
	        Êtes vous certains de vouloir supprimer ce post ?
	    </p>';

	echo'<p>
	         <a href="./postok.php?action=delete&amp;p='.$post.'">Oui</a> ou <a href="./index.php">Non</a>
	    </p>';
}

$query->CloseCursor();
break;


default: //Si jamais c'est aucun de ceux là c'est qu'il y a eu unproblème :o
echo'<p>Cette action est impossible</p>';
} //Fin du switch
?>

</body>
</html>
