<?php
$titre="Tutoriels | SiteduSavoir.com";
include("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

?>

<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Tutoriels</a></li>
         </ul>
  </div>

 <div class="page">

	      <h2 class="titre"> Listes des Tutoriels </h2>

	<?php

	$page = (!empty($_GET['page']))?$_GET['page']:1;
	$tutos_par_page = 20 ;
	$req = $bdd->query('SELECT COUNT(*) AS nbr_tuto FROM tutos');
	$nombres_tutos = $req->fetch();
	$nombres_tutos = $nombres_tutos['nbr_tuto'];


	$nbre_pages = ceil($nombres_tutos / $tutos_par_page);


	?>


	<p class="pagination">
	<?php

	for($i = 1 ; $i <=$nbre_pages ; $i++)
	{
		if($i == $page)
		{
			echo '<strong>'.$i.'</strong>';
		}
		else
		{
			echo ' <a href="index.php?page='.$i.'">'.$i.'</a> ';
		}

	}
	   
	?>

	</p>

	<?php

	if($id)
		echo '<p class="nouveau-sujet"><img src="../images/icones/new.png"/><a href="debutertuto.php"> Ecrire Un tuto </a></p>';



	$premiertutos = ($page - 1) * $tutos_par_page ;

	$req = $bdd->prepare('SELECT tutos_titre ,tutos.tutos_id, tutos_banniere,cat_nom 
		                  FROM tutos
		                  LEFT JOIN categorie
		                  ON categorie.cat_id = tutos.tutos_cat
		                  ORDER BY tutos_date,tutos_cat
		                  LIMIT :premier , :nombres');

	$req->bindParam(':premier',$premiertutos, PDO::PARAM_INT);
	$req->bindParam(':nombres',$tutos_par_page,PDO::PARAM_INT);

	$req->execute();

	if($req->rowCount() > 0)
	{


		while($tuto = $req->fetch())
		{
		  
		  echo 
		      '<div class="tutos">
		            <div class="banniere">
		                <img src="tutos_ban/'.$tuto['tutos_banniere'].'" alt="banniere"/>
		            </div>
		            <div class="tutos-infos">
		               <h3 class="titre-tuto"><a href="liretuto.php?&tuto='.$tuto['tutos_id'].'">'.htmlspecialchars($tuto['tutos_titre']).'</a></h3>';
		               
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


		       </div>';
	    }
	}
	else
	{
		if($id)
		{
	        echo '<p>  Il n y \' a aucun tutos actuelement Sur le site
		           <p>';
		           
		  }
		  else
		  {
		  	echo '<p>  Il n y \' a aucun tutos actuelement  Vous devez etre inscrit pour rediger un tuto
		           <p>
		           <a href="../register.php">S\'inscrire</a>
		         </p>
		         <p> Ou se connecter <a href="../connexion.php">Se connecter </a>
		      </p>';

		  }
	}    

	?>

	<p class="pagination">

	<?php

	for($i = 1 ; $i <= $nbre_pages ; $i++)
	{
		if($i == $page)
		{
			echo '<strong>'.$i.'</strong>';
		}
		else{
			echo ' <a href="index.php?page='.$i.'"">'.$i.'</a> ';
		}

	}
	   
	?>

	</p>
</div>
<?php include "../includes/footer.php"; ?>