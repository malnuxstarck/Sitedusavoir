<?php

$titre="Blog | SiteduSavoir.com";
include("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

?>

<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Tuto's</a></li>
         </ul>
  </div>

 <div class="page">

<h2 class="titre" > Listes des Tutos </h2>

<?php

$managerContenu = new ManagerContenu($bdd);
$page = (!empty($_GET['page']))?$_GET['page']:1;

$tutos_par_page = 20 ;

$nombres_tutos = $managerContenu->totalDeContenu('tutoriel');

$nbre_pages = ceil($nombres_tutos / $tutos_par_page);

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
		echo ' <a href="index.php?page='.$i.'</a> ';
	}

}
   
?>

</p>

<?php

	if($id)
	   echo '<p class="nouveau-sujet"><img src="../images/icones/new.png"/><a href="../contenus/debutercontenu.php">Ecrire un Tuto </a></p>';



$premiertuto = ($page - 1) * $tutos_par_page ;

$infosTutos = $managerContenu->tousLesContenus('tutoriel',$premiertuto);

if(!empty($infosTutos))
{

	foreach ($infosTutos as $infosTuto)
	{
		$tuto = new Contenu($infosTuto);
		$managerCategorie = new ManagerCategorie($bdd);
		$infosCategorie = $managerCategorie->infosCategorie($tuto->cat());
		$categorie = new Categorie($infosCategorie);
	  
	    echo 
	      '<div class="tutos">
	            <div class="banniere">
	                <img style="width:300px; height: 225px ;" src="../contenus/bannieres/'.$tuto->banniere().'" alt="banniere"/>
	            </div>
	            <div class="tutos-infos">
	               <h3 class="titre-tuto"><a href="lire.php?tuto='.$tuto->id().'">'.htmlspecialchars($tuto->titre()).'</a></h3>';

	                    $managerAuteur = new ManagerAuteur($bdd);
	                    $infosAuteurs = $managerAuteur->tousLesAuteurs($tuto->id());

                        foreach ($infosAuteurs as $infosAuteur)
                        {
                        	$auteur = new Auteur($infosAuteur);

		                    echo '<span class="auteur-tuto"><a href="../forum/voirprofil.php?m='.$auteur->membre().'" >'.$auteur->pseudo().'</a></span>';
                        }
	               echo '<span class="cat-tuto">'.$categorie->nom().'</span>
	            </div>  


	       </div>';
    }
}
else
{
	
        echo '<p>  Il n y \' a aucun tutoriels actuelement Sur le site
	           <p>';
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
		echo ' <a href="index.php?page='.$i.'">'.$i.'</a> ';
	}

}
   
?>

</p>

</div>

<?php include "../includes/footer.php"; ?>