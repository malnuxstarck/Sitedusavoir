
<?php

$titre="Groupes | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

echo '<p id="fildariane"><i><a href="../index.php">Accueil</a>--><a href="/index.php">Social</a>-->Mes groupes</i></p>';

echo '<h3> Vos groupes </h3>';

$groupesad = $bdd->prepare('SELECT * FROM social_groupes 
	                      JOIN social_gs_admin
	                      ON social_gs_admin.membre_id = social_groupes.groupes_createur
	                      JOIN membres
	                      ON membres.membre_id = social_groupes.groupes_createur
	                      WHERE membres.membre_id = :id');
$groupesad->bindParam(':id', $id, PDO::PARAM_INT)	;

$groupesad->execute();

echo '<ul>';

if($groupesad->rowCount() > 0)
{

	while($groupe = $groupesad->fetch())
	{
	  echo '<li>
	           <div class="avatar_gr">
	                  <img src="'.$groupe['groupes_banniere_min'].'" alt="ban"/>
	           </div>
	           <p>
	             <a href="voirgroupe.php?g='.$groupe['groupes_id'].'">'.$groupe['groupes_nom'].'</a>
	             <a href="gerer.php?action=admin&g='.$groupe['groupes_id'].'">Administrer</a>
	            </p> 
	        </li>';
	} 
}
else
{
	echo '<li> 
	           <p>Vous n\'avez creer aucun groupe actuelement</p>
	           <p><a href="./gerer.php?action=creer"> Creer Un </a></p>
	      </li>';
}

echo '</ul>'; 


echo '<h3> Groupes auquel vous appartenez</h3>'; 


$groupesap = $bdd->prepare('SELECT * FROM social_groupes 
	                      JOIN social_gs_membres
	                      ON social_gs_membres.groupes_id = social_groupes.groupes_id
	                      JOIN membres
	                      ON membres.membre_id = social_gs_membres.membre_id
	                      WHERE social_gs_membre.membre_id = :id');
$groupesad->bindParam(':id', $id, PDO::PARAM_INT)	;

$groupesad->execute();

echo '<ul>';

if($groupesad->rowCount() > 0)
{

	while($groupe = $groupesad->fetch())
	{
	  echo '<li>
	            <div class="avatar_gr">
	                <img src="'.$groupe['groupes_banniere_min'].'" alt="ban"/>
	           </div>
	            <p>
	            <a href="voirgroupe.php?g='.$groupe['groupes_id'].'">'.$groupe['groupes_nom'].'</a>
	           </li>';
	} 
}
else
{
	echo '<li><p> Vous n\'etes dans aucun groupe actuelement</p></li>';
}

echo '</ul>'; 

