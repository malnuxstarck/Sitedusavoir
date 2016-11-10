<?php
session_start();

include("../includes/identifiants.php");
include("../includes/bbcode.php"); //On verra plus tard ce qu'est cefichier
//On récupère la valeur de t

if(isset($_GET['t']))
$topic = (int)$_GET['t'];
else
  $topic = 1 ;
//A partir d'ici, on va compter le nombre de messages pourn'afficher que les 15 premiers

$query = $bdd->prepare('SELECT topic_titre, topic_post, forum_topic.forum_id , topic_last_post,
forum_name, auth_view, auth_topic, auth_post,auth_modo
FROM forum_topic
LEFT JOIN forum ON forum_topic.forum_id = forum.forum_id WHERE topic_id = :topic');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->execute();

$data = $query->fetch();

$titre= $data['topic_titre'];


include("../includes/debut.php");
include("../includes/menu.php");


if (!verif_auth($data['auth_view']))
{
	erreur(ERR_AUTH_VIEW);
}


$forum = $data['forum_id'];
$totalDesMessages = $data['topic_post'] + 1 ;
$nombreDeMessagesParPage = 15;
$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);

?>

<?php
echo '<p id="fildariane"><i>Vous êtes ici</i> : <a href="./index.php">Forum</a> -->
<a href="./voirforum.php?f='.$forum.'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
--> <a href="./voirtopic.php?
t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>';
echo '<h1 class="titre">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</h1><br/><br />';
?>

<?php
//Nombre de pages
$page = (isset($_GET['page']))?intval($_GET['page']):1;
//On affiche les pages 1-2-3 etc...
echo '<p>Page : ';
for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
if ($i == $page) //On affiche pas la page actuelle en lien
{
echo $i;
}
else
{
echo '<a href="voirtopic.php?t='.$topic.'&page='.$i.'">
' . $i . '</a> ';
}

}
echo'</p>';


if(verif_auth($data['auth_modo']))
{

    $query=$bdd->prepare('SELECT forum_id, forum_name FROM forum
WHERE forum_id <> :forum');
$query->bindValue(':forum',$forum,PDO::PARAM_INT);
$query->execute();
//$forum a été définie tout en haut de la page !
echo'<p>Déplacer vers :</p>
<form method="post" action=postok.php?action=deplacer&amp;t='.$topic.'>
<select name="dest">';
while($data=$query->fetch())
{
echo'<option value='.$data['forum_id'].'
id='.$data['forum_id'].'>'.$data['forum_name'].'</option>';
}
$query->CloseCursor();
echo'
</select>
<input type="hidden" name="from" value='.$forum.'>
<input type="submit" name="submit" value="Envoyer" />
</form>';





	$query = $bdd->prepare('SELECT topic_locked FROM forum_topic WHERE
	topic_id = :topic');
	$query->bindValue(':topic',$topic,PDO::PARAM_INT);
	$query->execute();
	$datas =$query->fetch();

	if ($datas['topic_locked'] == 1) // Topic verrouillé !
	{
	echo'<a href="./postok.php?action=unlock&t='.$topic.'">
	<img src="./images/unlock.gif" alt="deverrouiller"
	title="Déverrouiller ce sujet" /></a>';
	}

	else //Sinon le topic est déverrouillé !
	{
	echo'<a href="./postok.php?action=lock&amp;t='.$topic.'">
	<img src="./images/lock.gif" alt="verrouiller" title="Verrouiller ce
	sujet" /></a>';
	}

	$query->CloseCursor();



    echo'<form method="post" action=postok.php?action=autorep&amp;t='.$topic.'>
<select name="rep">';

$query=$bdd->query('SELECT automess_id, automess_titre FROM forum_automess');
while ($data = $query->fetch())
{
echo '<option value="'.$data['automess_id'].'">
'.$data['automess_titre'].'</option>';

}
echo '</select><input type="submit" name="submit" value="Envoyer" /></form>';
$query->CloseCursor();








}






$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

//On affiche l'image répondre

if (verif_auth($data['auth_post']))
{//On affiche l'image répondre
echo'<a href="./poster.php?action=repondre&amp;t='.$topic.'">
<img src="../images/repondre.gif" alt="Répondre" title="Répondre à ce
topic"></a>';
}

if (verif_auth($data['auth_topic']))
{
//On affiche l'image nouveau topic
echo'<a href="./poster.php?
action=nouveautopic&amp;f='.$data['forum_id'].'">
<img src="../images/nouveau.gif" alt="Nouveau topic"
title="Poster un nouveau topic"></a>';
}

$query->CloseCursor();
//Enfin on commence la boucle !
?>

<?php
$query=$bdd->prepare('SELECT post_id , post_createur , post_texte ,
DATE_FORMAT(post_time ,\'%d/%m/%Y %H:%i:%s\') AS post_time,
membre_id, membre_pseudo, DATE_FORMAT(membre_inscrit, \'%d/%m/%Y %H:%i:%s\') AS membre_inscrit, membre_avatar,
membre_localisation, membre_post, membre_signature
FROM forum_post
LEFT JOIN membres ON membres.membre_id =
forum_post.post_createur
WHERE topic_id =:topic
ORDER BY post_id
LIMIT :premier, :nombre');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);

$query->bindValue(':premier',(int)$premierMessageAafficher,PDO::PARAM_INT);

$query->bindValue(':nombre',(int)$nombreDeMessagesParPage,PDO::PARAM_INT);

$query->execute();

//On vérifie que la requête a bien retourné des messages
if ($query->rowCount()<1)
{
echo'<p>Il n y a aucun post sur ce topic, vérifiez l url et
reessayez</p>';
}

else
{
//Si tout roule on affiche notre tableau puis on remplit avec une boucle
?><table>
<tr>
<th class="vt_auteur"><strong>Auteurs</strong></th>
<th class="vt_mess"><strong>Messages</strong></th>
</tr>

<?php

while ($data = $query->fetch())
{


	//On commence à afficher le pseudo du créateur du message :
	//On vérifie les droits du membre
	//(partie du code commentée plus tard)
	echo'<tr><td><strong>
	<a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">
	'.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a></strong></td>';
	/* Si on est l'auteur du message, on affiche des liens pour
	Modérer celui-ci.
	Les modérateurs pourront aussi le faire, il faudra donc revenir sur
	ce code un peu plus tard ! */
	if ($id == $data['post_createur'])
	{
	echo'<td id=p_'.$data['post_id'].'>Posté à '.$data['post_time'].'
	<a href="./poster.php?p='.$data['post_id'].'&amp;action=delete">
	<img src="../images/supprimer.gif" alt="Supprimer"
	title="Supprimer ce message" /></a>
	<a href="./poster.php?p='.$data['post_id'].'&amp;action=edit">
	<img src="../images/editer.gif" alt="Editer"
	title="Editer ce message" /></a></td></tr>';
	}
	else
	{
	echo'<td>Posté à '.$data['post_time'].'</td></tr>';
	}

	echo'<tr><td>
	<img src="../images/avatars/'.$data['membre_avatar'].'" alt="" />
	<br />Membre inscrit le '.$data['membre_inscrit'].'
	<br />Messages : '.$data['membre_post'].'<br />
	Localisation : '.stripslashes(htmlspecialchars($data['membre_localisation'])).'</td>';

	echo'<td>'.code(nl2br(stripslashes(htmlspecialchars($data['post_texte'])))).'<br /><hr/>'.code(nl2br(stripslashes(htmlspecialchars($data['membre_signature'])))).'</td></tr>';
} 
//Fin de la boucle ! \o/
$query->CloseCursor();

?>
</table>

<?php
echo '<p>Page : ';
for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
if ($i == $page) //On affiche pas la page actuelle en lien
{
echo $i;
}
else
{
echo '<a href="voirtopic.php?
t='.$topic.'&amp;page='.$i.'">
' . $i . '</a> ';
}
}
echo'</p>';
//On ajoute 1 au nombre de visites de ce topic
$query=$bdd->prepare('UPDATE forum_topic
SET topic_vu = topic_vu + 1 WHERE topic_id = :topic');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->execute();
$query->CloseCursor();
} //Fin du if qui vérifiait si le topic contenait au moins un message
?>
</div>

<div id="footer">
<?php


echo '<h2>Options de modération</h2>';
$query=$bdd->prepare('SELECT auth_view, auth_modo, auth_post FROM
forum WHERE forum_id=:forum');
$query->bindValue(':forum',$forum,PDO::PARAM_INT);
$query->execute();
$data=$query->fetch();

$view = (verif_auth($data['auth_view']))? 'Vous pouvez <b>voir</b>
ce topic':'Vous <i>ne</i> pouvez <i>pas</i> <b>voir</b> ce topic';
$post = (verif_auth($data['auth_post']))? 'Vous pouvez
<b>répondre</b> à ce topic':'Vous <i>ne</i> pouvez <i>pas</i>
<b>répondre</b> à ce topic';
$modo = (verif_auth($data['auth_modo']))? 'Vous pouvez
<b>modérer</b> ce topic':'Vous <i>ne</i> pouvez <i>pas</i>
<b>modérer</b> ce topic';
echo '<p>'.$view.'<br />'.$post.'<br />'.$modo.'</p>';
$query->CloseCursor();
?>
</div>

</body>
</html>
