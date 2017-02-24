<?php
//Voir ses tutos

$titre = "Mes tutos| SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

echo '<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./voirmonprofil.php">'.$pseudo.'</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><span style="color:black;">Mes tutos</span> </li>
         </ul>
  </div>
  <div class="page">
  <h2 class="titre"> Mes tutos </h2>';

if(!$id)
{
	header('Location:../connexion.php');
}

$t = NULL;
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

if($mestutos->rowCount() > 0)
{
  while($tuto = $mestutos->fetch())
  {
  	  

  	  echo '<div class="tutos mestutos">
               
  	           <div class="banniere">
	                 <span class="edit-btn"><a href="../tutoriels/editiontuto.php?tuto='.$tuto['tutos_id'].'">Modifier </a></span><img src="../tutoriels/tutos_ban/'.$tuto['tutos_banniere'].'" alt="banniere"/>
	            </div>

	            <div class="tutos-infos">
	               
	               <h3 class="titre-tuto"><a href="../tutoriels/liretuto.php?tuto='.$tuto['tutos_id'].'">'.htmlspecialchars($tuto['tutos_titre']).'</a></h3>';

	               $auteurs = $bdd->prepare('SELECT membre_pseudo , membres.membre_id 
                        	                      FROM tutos_par JOIN membres 
                        	                      ON membres.membre_id = tutos_par.membre_id WHERE tutos_id = :tuto');

                        $auteurs->execute(array('tuto' => $tuto['tutos_id']));

                        while($auteur = $auteurs->fetch())
                        {
		                 echo '<span class="auteur-tuto"><a href="../forum/voirprofil.php?action=consulter&m='.$auteur['membre_id'].'">'.$auteur['membre_pseudo'].'</a></span>';
                        }

	               echo '<span class="cat-tuto">'.$tuto['cat_nom'].'</span>
	            </div>  
               
  	      </div>
  	     ';
  }
}
else
{
	echo '<ul><li> Vous n\'avez aucun tutos <a href="../tutoriels/debutertuto.php">Creer un et parateger votre savoir </a> </li></ul> ';
}

echo '
</div>';

include "../includes/footer.php";