<?php
$titre="Tutoriels | SiteduSavoir.com";
include("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

?>

<p id="fildariane"> <i><a href="../index.php">Accueil </a> --> <a href="index.php">Tutoriels</a></i></p>

<h2 class="titre" style="text-align:center"> Listes des Tutoriels </h2>

<?php

$page = (!empty($_GET['page']))?$_GET['page']:1;
$tutos_par_page = 20 ;
$req = $bdd->query('SELECT COUNT(*) AS nbr_tuto FROM tutos');
$nombres_tutos = $req->fetch();
$nombres_tutos = $nombres_tutos['nbr_tuto'];


$nbre_pages = ceil($nombres_tutos / $tutos_par_page);


?>


<p class="page">
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
	echo '<p><a href="debutertuto.php"> Ecrire Un tuto </a></p>';



$premiertutos = ($page - 1) * $tutos_par_page ;

$req = $bdd->prepare('SELECT tutos_titre ,tutos.tutos_id, tutos_banniere ,membre_pseudo,membres.membre_id,cat_nom 
	                  FROM tutos
	                  LEFT JOIN tutos_par
	                  ON tutos.tutos_id = tutos_par.tutos_id
	                  LEFT JOIN membres 
	                  ON membres.membre_id = tutos_par.membre_id
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
	            <div class="tutos_infos">
	               <h3 class="tuto_titre" style="color:#2b8bad;"><a href="liretuto.php?&tuto='.$tuto['tutos_id'].'">'.htmlspecialchars($tuto['tutos_titre']).'</a></h3>
	               <span> Par <a href="../forum/voirprofil.php?action=consulter&m='.$tuto['membre_id'].'">'.$tuto['membre_pseudo'].'</a></span><span>'.$tuto['cat_nom'].'</span>
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

<p class="page">

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