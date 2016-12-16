<?php
session_start();
$titre="Poster";
$balises = true;
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

?>

<?php
//Qu'est ce qu'on veut faire ? poster, répondre ou éditer ?
$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';
//Il faut être connecté pour poster !
if ($id==0) erreur(ERR_IS_CO);
//Si on veut poster un nouveau topic, la variable f se trouve dans l'url,
//On récupère certaines valeurs
if (isset($_GET['f']))
{

$forum = (int) $_GET['f'];
$query= $bdd->prepare('SELECT forum_id ,forum_name, auth_view, auth_post, auth_topic,auth_annonce, auth_modo
FROM forum WHERE forum_id =:forum');

$query->bindValue(':forum',$forum,PDO::PARAM_INT);
$query->execute();
$data = $query->fetch();

echo '<p id="fildariane"><i>Vous êtes ici</i> : <a href="./index.php">Forum</a> --> <a href="./voirforum.php?f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a> --> Nouveau topic</p>';
}

//Sinon c'est un nouveau message, on a la variable t et
//On récupère f grâce à une requête

elseif (isset($_GET['t']))
{
$topic = (int) $_GET['t'];
$query=$bdd->prepare('SELECT topic_titre, forum_topic.forum_id,
forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
FROM forum_topic
LEFT JOIN forum ON forum.forum_id = forum_topic.forum_id
WHERE topic_id =:topic');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);

$query->execute();
$data=$query->fetch();

$forum = $data['forum_id'];

echo '<p id="fildariane"><i>Vous êtes ici</i> : <a href="./index.php">Forum</a> -->
<a href="./voirforum.php?
f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
--> <a href="./voirtopic.php?
t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
--> Répondre</p>';
}
//Enfin sinon c'est au sujet de la modération(on verra plus tard en détail)
//On ne connait que le post, il faut chercher le reste
elseif (isset ($_GET['p']))
{
$post = (int) $_GET['p'];

$query = $bdd->prepare('SELECT post_createur, forum_post.topic_id, topic_titre,
forum_topic.forum_id,
forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
FROM forum_post
LEFT JOIN forum_topic ON forum_topic.topic_id = forum_post.topic_id
LEFT JOIN forum ON forum.forum_id = forum_topic.forum_id
WHERE forum_post.post_id = :post');

$query->bindValue(':post',$post,PDO::PARAM_INT);
$query->execute();

$data=$query->fetch();
$topic = $data['topic_id'];
$forum = $data['forum_id'];

echo '<p id="fildariane"><i>Vous êtes ici</i> : <a href="./index.php">Forum</a> -->
<a href="./voirforum.php?
f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
--> <a href="./voirtopic.php?
t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
--> Modérer un message</p>';
}
$query->CloseCursor();



 switch($action)
{
case "repondre":
 //Premier cas : on souhaite répondre
//Ici, on affiche le formulaire de réponse
?>

<h1>Poster une réponse</h1>
<form method="post" action="postok.php?action=repondre&amp;t=<?php echo $topic ?>" name="formulaire">
<fieldset><legend>Mise en forme</legend>
<input type="button" id="gras" name="gras" value="Gras"
onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
<input type="button" id="italic" name="italic" value="Italic"
onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
<input type="button" id="souligné" name="souligné" value="Souligné"
onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
<input type="button" id="lien" name="lien" value="Lien"
onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
<br /><br />
<img src="../images/smileys/heureux.gif" title="heureux"
alt="heureux" onClick="javascript:smilies(' :D ');return(false)" />
<img src="../images/smileys/lol.gif" title="lol" alt="lol"
onClick="javascript:smilies(' :lol: ');return(false)" />
<img src="../images/smileys/triste.gif" title="triste" alt="triste" onClick="javascript:smilies(' :triste: ');return(false)" />
<img src="../images/smileys/cool.gif" title="cool" alt="cool"
onClick="javascript:smilies(' :frime: ');return(false)" />
<img src="../images/smileys/rire.gif" title="rire" alt="rire"
onClick="javascript:smilies(' XD ');return(false)" />
<img src="../images/smileys/confus.gif" title="confus" alt="confus"
onClick="javascript:smilies(' :s ');return(false)" />
<img src="../images/smileys/choc.gif" title="choc" alt="choc"
onClick="javascript:smilies(' :o ');return(false)" />
<img src="../images/smileys/question.gif" title="?" alt="?"
onClick="javascript:smilies(' :interrogation: ');return(false)" />
<img src="../images/smileys/exclamation.gif" title="!" alt="!"
onClick="javascript:smilies(' :exclamation: ');return(false)" />
</fieldset>
<fieldset><legend>Message</legend><textarea cols="80" rows="8"
id="message" name="message"></textarea></fieldset>
<input type="submit" name="submit" value="Envoyer" />
<input type="reset" name = "Effacer" value = "Effacer"/>
</p></form>

<?php

break;
//Ici, on affiche le formulaire de nouveau topic
?>

<?php

break;

case "nouveautopic":

?>
<h1>Nouveau topic</h1>
<form method="post" action="postok.php?action=nouveautopic&amp;f=<?php echo $forum;?> "name="formulaire">
<fieldset><legend>Titre</legend>
<input type="text" size="80" id="titre" name="titre" /></fieldset>
<fieldset><legend>Mise en forme</legend>
<input type="button" id="gras" name="gras" value="Gras"
onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
<input type="button" id="italic" name="italic" value="Italic"
onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
<input type="button" id="souligné" name="souligné" value="Souligné"
onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
<input type="button" id="lien" name="lien" value="Lien"
onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
<br /><br />
<img src="../images/smileys/heureux.gif" title="heureux"
alt="heureux" onClick="javascript:smilies(':D');return(false)" />
<img src="../images/smileys/lol.gif" title="lol" alt="lol"
onClick="javascript:smilies(':lol:');return(false)" />
<img src="../images/smileys/triste.gif" title="triste" alt="triste"
onClick="javascript:smilies(':triste:');return(false)" />
<img src="../images/smileys/cool.gif" title="cool" alt="cool"
onClick="javascript:smilies(':frime:');return(false)" />
<img src="../images/smileys/rire.gif" title="rire" alt="rire"
onClick="javascript:smilies('XD');return(false)" />
<img src="../images/smileys/confus.gif" title="confus" alt="confus"
onClick="javascript:smilies(':s');return(false)" />
<img src="../images/smileys/choc.gif" title="choc" alt="choc"
onClick="javascript:smilies(':O');return(false)" />
<img src="../images/smileys/question.gif" title="?" alt="?"
onClick="javascript:smilies(':interrogation:');return(false)" />
<img src="../images/smileys/exclamation.gif" title="!" alt="!"
onClick="javascript:smilies(':exclamation:');return(false)"
/></fieldset>
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
</form></p>
<?php
break;


 case "edit": //Si on veut éditer le post
//On récupère la valeur de p
$post = (int) $_GET['p'];
echo'<h1>Edition</h1>';
//On lance enfin notre requête
$query = $bdd->prepare('SELECT post_createur, post_texte, auth_modo
FROM forum_post  LEFT JOIN forum ON forum_post.post_forum_id = forum.forum_id
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
<form method="post" action="postok.php?action=edit&amp;p=<?php echo $post ?>" name="formulaire">
<fieldset><legend>Mise en forme</legend>
<input type="button" id="gras" name="gras" value="Gras"
onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
<input type="button" id="italic" name="italic"
value="Italic" onClick="javascript:bbcode('[i]',
'[/i]');return(false)" />
<input type="button" id="souligné" name="souligné"
value="Souligné" onClick="javascript:bbcode('[s]',
'[/s]');return(false)"/>
<input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
<br /><br />
<img src="../images/smileys/heureux.gif" title="heureux"
alt="heureux" onClick="javascript:smilies(':D');return(false)" />
<img src="../images/smileys/lol.gif" title="lol" alt="lol"
onClick="javascript:smilies(':lol:');return(false)" />
<img src="../images/smileys/triste.gif" title="triste"
alt="triste" onClick="javascript:smilies(':triste:');return(false)"
/>
<img src="../images/smileys/cool.gif" title="cool" alt="cool"
onClick="javascript:smilies(':frime:');return(false)" />
<img src="../images/smileys/rire.gif" title="rire" alt="rire"
onClick="javascript:smilies('XD');return(false)" />
<img src="../images/smileys/confus.gif" title="confus"
alt="confus" onClick="javascript:smilies(':s');return(false)" />
<img src="../images/smileys/choc.gif" title="choc" alt="choc"
onClick="javascript:smilies(':O');return(false)" />
<img src="../images/smileys/question.gif" title="?" alt="?"
onClick="javascript:smilies(':interrogation:');return(false)" />
<img src="../images/smileys/exclamation.gif" title="!"
alt="!" onClick="javascript:smilies(':exclamation:');return(false)"
/>
</fieldset>
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
echo'<h1>Suppression</h1>';

$query=$bdd->prepare('SELECT post_createur, auth_modo
FROM forum_post
LEFT JOIN forum ON forum_post.post_forum_id =
forum.forum_id
WHERE post_id= :post');
$query->bindValue(':post',$post,PDO::PARAM_INT);
$query->execute();
$data = $query->fetch();
if (!verif_auth($data['auth_modo']) && $data['post_createur'] !=
$id)
{
// Si cette condition n'est pas remplie ça va barder :o
erreur(ERR_AUTH_DELETE);
}
else //Sinon ça roule et on affiche la suite
{
echo'<p>Êtes vous certains de vouloir supprimer ce post ?
</p>';
echo'<p><a href="./postok.php?
action=delete&amp;p='.$post.'">Oui</a> ou <a
href="./index.php">Non</a></p>';
}
$query->CloseCursor();
break;


default: //Si jamais c'est aucun de ceux là c'est qu'il y a eu unproblème :o
echo'<p>Cette action est impossible</p>';
} //Fin du switch
?>
</div>
</body>
</html>
