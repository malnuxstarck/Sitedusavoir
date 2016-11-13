<?php
session_start();
$titre = $_SESSION['pseudo'];

include_once('../includes/identifiants.php');
include_once ('../includes/debut.php');
include_once('../includes/menu.php');




$membre = (int)$_GET['id'];

if($membre != $id)
{
	$_SESSION['flash']['danger'] = 'Vous avez tenter d\'acceder a une partie non autorisé';

	header('Location: ../index.php');
}

$requete = $bdd->prepare('SELECT membre_pseudo ,membre_localisation, membre_email,membre_inscrit,membre_siteweb,membre_signature,DATE_FORMAT(membre_derniere_visite,\'le %d-%M-%Y à %hH:%iMIN:%sSECS\') AS membre_derniere_visite,membre_avatar FROM membres WHERE membre_id = :idmembre');
$requete->execute(array('idmembre' => $membre));

$reponse = $requete->fetch();


echo '<h1 class="titre"> Bienvenue '.$reponse['membre_pseudo'].'</h1>

      <p> <span > Votre pseudo '.$reponse['membre_pseudo'].'
    <div class="avatar"><img src="../images/avatars/'.$reponse['membre_avatar'].'"/></div>

    <h2 clas="titre"> Habite a : <span>'.$reponse['membre_localisation'].'</span></h2>

    <h2 class="titre"> Signature </h2><div id="signature">'.$reponse['membre_signature'].'</div>

    <p id="vu"> Derniere Visite '.$reponse['membre_derniere_visite'].'</p>' 

    

;

