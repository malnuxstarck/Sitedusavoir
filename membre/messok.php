<?php



session_start();
$titre="Messagerie";
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");
include('../includes/bbcode.php');

if ($id==0) erreur(ERR_IS_CO);


$action =(isset($_GET['action']))?htmlspecialchars($_GET['action']):'';

$message = $_POST['message'];

$titre = $_POST['titre'];


switch($action)
{





case "repondremp": //Si on veut répondre
//On récupère le titre et le message

//On récupère la valeur de l'id du destinataire
$dest = (int) $_GET['dest'];
//Enfin on peut envoyer le message
$query=$bdd->prepare('INSERT INTO mp (mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(:id, :dest, :titre, :txt, NOW(),\'0\')');
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

case "nouveaump": //On envoie un nouveau mp
//On récupère le titre et le message
$message = $_POST['message'];
$titre = $_POST['titre'];
$dest = $_POST['to'];
//On récupère la valeur de l'id du destinataire
//Il faut déja vérifier le nom
$query = $bdd->prepare('SELECT membre_id FROM membres
WHERE LOWER(membre_pseudo) = :dest');
$query->bindValue(':dest',strtolower($dest),PDO::PARAM_STR);
$query->execute();

?>
<?php
if($data = $query->fetch())
{
$query=$bdd->prepare('INSERT INTO mp
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
$query = $bdd->prepare('SELECT mp_receveur
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

default:
 echo '<p> Action impossible</p>';
}
?>
 </div>
</body>
</html>

