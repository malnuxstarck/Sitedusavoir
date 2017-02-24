.php<?php

$titre="Voir Groupe | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

echo '<p id="fildariane"><i><a href="../index.php">Accueil</a>--><a href="index.php">Social</a>--><a href="./mesgroupes.php"> Groupes </a>-->Voir groupe </i></p>';


if(!$id)
{
	header('Location:../connexion.php');
}

$groupeid = (isset($_GET['g']))?$_GET['g']:"";


if(empty($groupeid))
{

	header('Location:./index.php');

}



$groupeinfo = $bdd->prepare('SELECT * FROM social_groupes 
	                         JOIN social_gs_membres 
	                         ON social_groupes.groupes_id = social_gs_membres.groupes_id
	                         JOIN membres 
	                         ON social_gs_membres.membre_id = membres.membre_id
	                         WHERE social_groupes.groupes_id = :groupeid 
	                         AND social_gs_membres.membre_id = :membre');
$groupeinfo->bindParam(':membre', $id , PDO::PARAM_INT);
$groupeinfo->bindParam(':groupeid', $groupeid , PDO::PARAM_INT);
$groupeinfo->execute();

if($groupeinfo->rowcount() > 0)
{
	$groupe = $groupeinfo->fetch();

	echo'<div class="groupepre">
	               <div class="bannierer">
	                     <img src="./photos/'.$groupe['groupes_banniere'].'" alt=""/>
	               </div>
	               <h3 class="nom_groupe">'.$groupe['groupes_nom'].'</h3>
                  <aside class="infos">
                          
                  </aside>
	      </div>';

}   
else
{
	header('Location:./mesgroupes.php');
}                      