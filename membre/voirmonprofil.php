<?php
session_start();
$titre = $_SESSION['pseudo'];
include_once ('../includes/debut.php');
include_once('../includes/identifiants.php');
include_once('../includes/menu.php');




$membre = (int)$_GET['id'];

if($membre != $id)
{
	$_SESSION['flash']['danger'] = 'Vous avez tenter d\'acceder a une partie non autorisé';

	header('Location: ../index.php');
}

$requete = $bdd->prepare('SELECT membre_pseudo ,membre_avatar, membre_email,membre_inscrit,membre_siteweb,membre_signature,membre_derniere_visite FROM membres WHERE membre_id = :idmembre');
$requete->execute(array('idmembre' => $membre));

$reponse = $requete->fetch();

echo '<div id="profil"><img src="../images/avatars/'.$reponse['membre_avatar'].'" alt="profil"></div>';
echo '<div>'.$reponse['membre_pseudo'].'</div>';

?>

</div>
</body>
</html>