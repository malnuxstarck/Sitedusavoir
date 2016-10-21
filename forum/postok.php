<?php
session_start();
$titre="Poster";
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");
//On récupère la valeur de la variable action
$action =(isset($_GET['action']))?htmlspecialchars($_GET['action']):'';
// Si le membre n'est pas connecté, il est arrivé ici par erreur
if ($id==0) erreur(ERR_IS_CO);
?>

<?php
switch($action)
{
//Premier cas : nouveau topic
case "nouveautopic":

/*

if (!verif_auth($data['auth_annonce']) && isset($_POST['mess']))
{
exit('</div></body></html>');
}

*/

//On passe le message dans une série de fonction
$message = $_POST['message'];
$mess = $_POST['mess'];
//Pareil pour le titre
$titre = $_POST['titre'];
//ici seulement, maintenant qu'on est sur qu'elle existe, onrécupère la valeur de la variable f
$forum = (int) $_GET['f'];

if (empty($message) || empty($titre))
{
echo'<p>Votre message ou votre titre est vide,
cliquez <a href="./poster.php?action=nouveautopic&amp;f='.$forum.'">ici</a> pour recommencer</p>';
}
else //Si jamais le message n'est pas vide
{

//On entre le topic dans la base de donnée en laissant
//le champ topic_last_post à 0
$query = $bdd->prepare('INSERT INTO forum_topic (forum_id, topic_titre, topic_createur, topic_vu, topic_time,topic_genre) VALUES (:forum, :titre, :id, 1, NOW(), :mess)');
$query->bindValue(':forum', $forum, PDO::PARAM_INT);
$query->bindValue(':titre', $titre, PDO::PARAM_STR);

$query->bindValue(':id', $id, PDO::PARAM_INT);

$query->bindValue(':mess', $mess, PDO::PARAM_STR);
$query->execute();

$nouveautopic = $bdd->lastInsertId();
 //Notre fameuse fonction !
$query->CloseCursor();


//Puis on entre le message
$query = $bdd->prepare('INSERT INTO forum_post
(post_createur, post_texte, post_time, topic_id, post_forum_id)
VALUES (:id, :mess, NOW(), :nouveautopic, :forum)');
$query->bindValue(':id', $id, PDO::PARAM_INT);
$query->bindValue(':mess', $message, PDO::PARAM_STR);
$query->bindValue(':nouveautopic', (int)$nouveautopic, PDO::PARAM_INT);
$query->bindValue(':forum', $forum, PDO::PARAM_INT);
$query->execute();

$nouveaupost = $bdd->lastInsertId();
 //Encore notre fameuse fonction !
$query->CloseCursor();

//Ici on update comme prévu la valeur de topic_last_post et de topic_first_post
$query=$bdd->prepare('UPDATE forum_topic
SET topic_last_post = :nouveaupost,
topic_first_post = :nouveaupost
WHERE topic_id = :nouveautopic');
$query->bindValue(':nouveaupost', (int)$nouveaupost,PDO::PARAM_INT);

$query->bindValue(':nouveautopic', (int)$nouveautopic,PDO::PARAM_INT);

$query->execute();

$query->CloseCursor();


//Enfin on met à jour les tables forum_forum et forum_membres
$query=$bdd->prepare('UPDATE forum SET forum_post =
forum_post + 1 ,forum_topic = forum_topic + 1,
forum_last_post_id = :nouveaupost
WHERE forum_id = :forum');
$query->bindValue(':nouveaupost', (int) $nouveaupost,
PDO::PARAM_INT);
$query->bindValue(':forum', (int) $forum, PDO::PARAM_INT);
$query->execute();
$query->CloseCursor();

$query=$bdd->prepare('UPDATE membres SET membre_post =
membre_post + 1 WHERE membre_id = :id');

$query->bindValue(':id', $id, PDO::PARAM_INT);
$query->execute();
$query->CloseCursor();
//Et un petit message
echo'<p>Votre message a bien été ajouté!<br /><br />Cliquez
<a href="./index.php">ici</a> pour revenir à l index du forum<br />
Cliquez <a href="./voirtopic.php?t='.$nouveautopic.'">ici</a> pour
le voir</p>';
}
break;


//Deuxième cas : répondre
case "repondre":
$message = $_POST['message'];
//ici seulement, maintenant qu'on est sur qu'elle existe, on récupère la valeur de la variable t
$topic = (int) $_GET['t'];

if (empty($message))
{
echo'<p>Votre message est vide, cliquez <a
href="./poster.php?action=repondre&amp;t='.$topic.'">ici</a> pour
recommencer</p>';
}
else //Sinon, si le message n'est pas vide
{
//On récupère l'id du forum
$query=$bdd->prepare('SELECT forum_id, topic_post FROM
forum_topic WHERE topic_id = :topic');
$query->bindValue(':topic', $topic, PDO::PARAM_INT);
$query->execute();
$data=$query->fetch();
$forum = $data['forum_id'];
//Puis on entre le message
$query=$bdd->prepare('INSERT INTO forum_post
(post_createur, post_texte, post_time, topic_id, post_forum_id)
VALUES(:id,:mess,NOW(),:topic,:forum)');
$query->bindValue(':id', $id, PDO::PARAM_INT);
$query->bindValue(':mess', $message, PDO::PARAM_STR);

$query->bindValue(':topic', $topic, PDO::PARAM_INT);
$query->bindValue(':forum', $forum, PDO::PARAM_INT);
$query->execute();
$nouveaupost = $bdd->lastInsertId();
$query->CloseCursor();
//On change un peu la table forum_topic
$query=$bdd->prepare('UPDATE forum_topic SET topic_post = topic_post + 1, topic_last_post = :nouveaupost WHERE topic_id
=:topic');
$query->bindValue(':nouveaupost', (int) $nouveaupost,
PDO::PARAM_INT);

$query->bindValue(':topic', (int) $topic, PDO::PARAM_INT);
$query->execute();
$query->CloseCursor();

//Puis même combat sur les 2 autres tables
$query=$bdd->prepare('UPDATE forum SET forum_post =
forum_post + 1 , forum_last_post_id = :nouveaupost WHERE forum_id =
:forum');
$query->bindValue(':nouveaupost', (int) $nouveaupost,
PDO::PARAM_INT);
$query->bindValue(':forum', (int) $forum, PDO::PARAM_INT);
$query->execute();
$query->CloseCursor();
$query=$bdd->prepare('UPDATE membres SET membre_post =
membre_post + 1 WHERE membre_id = :id');
$query->bindValue(':id', $id, PDO::PARAM_INT);
$query->execute();
$query->CloseCursor();
//Et un petit message
$nombreDeMessagesParPage = 15;
$nbr_post = $data['topic_post']+1;
$page = ceil($nbr_post / $nombreDeMessagesParPage);
echo'<p>Votre message a bien été ajouté!<br /><br />
Cliquez <a href="./index.php">ici</a> pour revenir à l index du
forum<br />
Cliquez <a href="./voirtopic.php?
t='.$topic.'&amp;page='.$page.'#p_'.$nouveaupost.'">ici</a> pour le
voir</p>';
}//Fin du else
break;
?>

<?php
break;


case "repondremp": //Si on veut répondre
//On récupère le titre et le message
$message = $_POST['message'];
$titre = $_POST['titre'];

//On récupère la valeur de l'id du destinataire
$dest = (int) $_GET['dest'];
//Enfin on peut envoyer le message
$query= $bdd->prepare('INSERT INTO forum_mp (mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(:id, :dest, :titre, :txt, NOW(), 0)');
$query->bindValue(':id',$id,PDO::PARAM_INT);
$query->bindValue(':dest',$dest,PDO::PARAM_INT);
$query->bindValue(':titre',$titre,PDO::PARAM_STR);
$query->bindValue(':txt',$message,PDO::PARAM_STR);
$query->execute();
$query->CloseCursor();
echo'<p>Votre message a bien été envoyé!<br />
<br />Cliquez <a href="./index.php">ici</a> pour revenir à l index
du
forum<br />
<br />Cliquez <a href="./messagesprives.php">ici</a> pour retourner
à la messagerie</p>';
break;
?>


<?php
case "nouveaump": //On envoie un nouveau mp
//On récupère le titre et le message
$message = $_POST['message'];
$titre = $_POST['titre'];
$dest = $_POST['to'];
//On récupère la valeur de l'id du destinataire
//Il faut déja vérifier le nom
$query=$bdd->prepare('SELECT membre_id FROM forum_membres
WHERE LOWER(membre_pseudo) = :dest');
$query->bindValue(':dest',strotolower($dest),PDO::PARAM_STR);
$query->execute();

?>
<?php
if($data = $query->fetch())
{
$query=$bdd->prepare('INSERT INTO forum_mp
(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu)
VALUES(:id, :dest, :titre, :txt, NOW(), :lu)');
$query->bindValue(':id',$id,PDO::PARAM_INT);
$query->bindValue(':dest',(int)
$data['membre_id'],PDO::PARAM_INT);
$query->bindValue(':titre',$titre,PDO::PARAM_STR);
$query->bindValue(':txt',$message,PDO::PARAM_STR);

$query->bindValue(':lu','0',PDO::PARAM_STR);
$query->execute();
$query->CloseCursor();
echo'<p>Votre message a bien été envoyé!
<br /><br />Cliquez <a href="./index.php">ici</a> pour revenir à l
index du
forum<br />
<br />Cliquez <a href="./messagesprives.php">ici</a> pour retourner
àl
a messagerie</p>';
}
//Sinon l'utilisateur n'existe pas !
else
{
echo'<p>Désolé ce membre n existe pas, veuillez vérifier et
réessayez à nouveau.</p>';
}
break;
?>

<?php

case "supprimer":
//On récupère la valeur de l'id
$id_mess = (int) $_GET['id'];
//Il faut vérifier que le membre est bien celui qui a reçu le message
$query=$db->prepare('SELECT mp_receveur
FROM forum_mp WHERE mp_id = :id');
$query->bindValue(':id',$id_mess,PDO::PARAM_INT);
$query->execute();
$data = $query->fetch();
//Sinon la sanction est terrible :p
if ($id != $data['mp_receveur']) erreur(ERR_WRONG_USER);
$query->CloseCursor();

$sur = (int) $_GET['sur'];
//Pas encore certain
if ($sur == 0)
{
echo'<p>Etes-vous certain de vouloir supprimer ce message ?<br
/>
<a href="./messagesprives.php?
action=supprimer&amp;id='.$id_mess.'&amp;sur=1">
Oui</a> - <a href="./messagesprives.php">Non</a></p>';
}
//Certain
else
{
$query=$bdd->prepare('DELETE from forum_mp WHERE mp_id =
:id');
$query->bindValue(':id',$id_mess,PDO::PARAM_INT);
$query->execute();
$query->CloseCursor();
echo'<p>Le message a bien été supprimé.<br />
Cliquez <a href="./messagesprives.php">ici</a> pour revenir à la
boite
de messagerie.</p>';
}
break;
?>




<?php
default;
echo'<p>Cette action est impossible</p>';
} //Fin du Switch
?>
</div>
</body>
</html>








