<?php
//Voir ses tutos

$titre = "Mes tutos| SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

echo '<p id="fildariane"><i><a href="../index.php">Accueil</a>--><a href="./mestutos.php">Mes tutos</a></i></p>';

if(!$id)
{
	header('Location:../connexion.php');
}

$mestutos = $bdd->prepare('SELECT tutos_titre , tutos.tutos_id,membres.membre_id,membre_pseudo,tutos_banniere,tutos_date,tutos_cat,cat_nom
                           FROM tutos
	                       JOIN tutos_par
	                       ON tutos_par.tutos_id = tutos.tutos_id
	                       JOIN membres
	                       ON membres.membre_id = tutos_par.membre_id
	                       JOIN categorie
	                       ON categorie.cat_id = tutos.tutos_cat
	                       WHERE tutos_par.membre_id = :membre ORDER BY tutos_date');
$mestutos->bindParam(':membre', $id , PDO::PARAM_INT);
$mestutos->execute();


echo '<ul>';

if($mestutos->rowCount() > 0)
{
  while($tuto = $mestutos->fetch())
  {
  	echo '<div class="tutos">

  	           <div class="banniere">
	                <img src="../tutoriels/tutos_ban/'.$tuto['tutos_banniere'].'" alt="banniere"/>
	            </div>
	            <div class="tutos_infos">
	               <span class="edit"><a href="../tutoriels/editiontuto.php?tuto='.$tuto['tutos_id'].'">Modifier </a></span>
	               <h3 class="tuto_titre" style="color:#2b8bad;"><a href="../tutoriels/liretuto.php?tuto='.$tuto['tutos_id'].'">'.htmlspecialchars($tuto['tutos_titre']).'</a></h3>
	               <span> Par <a href="../forum/voirprofil.php?action=consulter&m='.$tuto['membre_id'].'">'.$tuto['membre_pseudo'].'</a></span><span>'.$tuto['cat_nom'].'</span>
	            </div>  

  	      </div>';
  }
}
else
{
	echo '<li> Vous n\'avez aucun tutos <a href="../tutoriels/debutertuto.php">Creer un et parateger votre savoir </a> </li> ';
}

echo '<ul>';