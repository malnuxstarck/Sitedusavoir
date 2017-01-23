<?php
  switch($action)
  {
    case "repondre":
		 //Premier cas : on souhaite répondre
		//Ici, on affiche le formulaire de réponse
		?>

		<h1>Poster une réponse</h1>
		<form method="post" action="postok.php?action=repondre&amp;t=<?php echo $topic ?>" name="formulaire">
			<?php require "../../vue/includes/miseenforme.php"; ?>
			 <fieldset>
			        <legend>Message</legend>
			        <textarea cols="80" rows="8" id="message" name="message"></textarea>
			</fieldset>
			<input type="submit" name="submit" value="Envoyer" />
			<input type="reset" name = "Effacer" value = "Effacer"/>
			</p>
		</form>

		<?php

    break;
		//Ici, on affiche le formulaire de nouveau topic
		?>

     <?php

    
    case "nouveautopic":

			?>
			<h1>Nouveau topic</h1>

			<form method="post" action="postok.php?action=nouveautopic&amp;f=<?php echo $forum;?>" name="formulaire">
					<fieldset><legend>Titre</legend>
					<input type="text" size="80" id="titre" name="titre" /></fieldset>
					<?php require "../../vue/includes/miseenforme.php";?>
					<fieldset><legend>Message</legend>

					<textarea cols=80 rows=8 id="message" name="message"></textarea>
					<br />
					<?php
					if (verif_auth($data['auth_annonce']))
					{
					?>
					<label><input type="radio" name="mess" value="Annonce"/>Annonce</label>

					<?php
					}
					?>
					<label><input type="radio" name="mess" value="Message" checked="checked" />Topic</label><br />
					<input type="submit" name="submit" value="Envoyer" />
					<input type="reset" name ="Effacer" value ="Effacer" />
			</form>
			</p>
			<?php
    break;


    case "edit": 

 //Si on veut éditer le post
//On récupère la valeur de p

$post = (int) $_GET['p'];
echo'<h1>Edition</h1>';
//On lance enfin notre requête
$data = EditPost($post, $bdd);
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
<form method="post" action="postok.php?action=edit&amp;p=<?php echo $post ?>" name="formulaire">
<?php require "../../vue/includes/miseenforme.php";?>
<fieldset><legend>Message</legend><textarea cols="80"
rows="8" id="message" name="message"><?php echo $text_edit ?>
</textarea>
</fieldset>
<p>
<input type="submit" name="submit" value="Editer !" />
<input type="reset" name = "Effacer" value = "Effacer"/></p>
</form>

<?php
}
break;

case "delete": //Si on veut supprimer le post
//On récupère la valeur de p
$post = (int) $_GET['p'];
//Ensuite on vérifie que le membre a le droit d'être ici
echo'<h1> Suppression </h1>';

$data = EditPost($post, $bdd);

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
}