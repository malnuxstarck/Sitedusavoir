<?php
//Voir ses tutos

$titre = "Mes tutos| SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

echo '<ul class="fildariane">
  <li><a href="../index.php">Accueil</a></li>
  <li><a href="./voirmonprofil.php">'.$pseudo.'</a></li>
  <li><span>Mes Contenus</span></li>
</ul>

<div class="page">
<h2 class="titre"> Mes Contenus </h2>';

if(!$id)
{
	header('Location:../connexion.php');
}
?>

<form method="POST" action ="">
      <select name="type" id="">
      <?php if(Membre::verif_auth(MODO))
         echo '<option value="article">Article</option>';
      ?>
        <option value="tutoriel">Tutoriel</option>
      </select>
      <input type="submit" value="Envoyer">
</form>

<?php

$managerContenu = new ManagerContenu($bdd);
$managerAuteur = new ManagerAuteur($bdd);
$managerCategorie = new ManagerCategorie($bdd);

$type = (!empty($_POST['type']))?$_POST['type']:'tutoriel';
$page = (!empty($_GET['page']))?$_GET['page']:1;
$contenus_par_page = 20 ;

$nombres_contenus = $managerContenu->totalDeMesContenus($type , $id);
$nbre_pages = ceil($nombres_contenus / $contenus_par_page);

?>


<p class="page">
<?php  paginationListe($page ,$nbre_pages, 'mescontenus.php'); ?>

</p>

<?php

$premiercontenu = ($page - 1) * $contenus_par_page ;
$infosContenus = $managerContenu->tousMesContenus($type ,$id ,$premiercontenu ,$contenus_par_page);


if(!empty($infosContenus))
{

  foreach ($infosContenus as $infosContenu) {

    $contenu = new Contenu($infosContenu);

    if($contenu->type() == 'tutoriel')
      $dossier = 'tutoriels/lire.php?tuto=';
    else
      $dossier = 'blog/lire.php?article=';
   
    echo 
        '<div class="tutos">

              <div class="banniere">
                   <span class="edit-btn"><a href="../contenus/editioncontenu.php?contenu='.$contenu->id().'">Modifier </a></span><img src="../contenus/bannieres/'.$contenu->banniere().'" alt="banniere" style="width:300px; height: 225px ;"/>
              </div>
              <div class="tutos-infos">
                 <h3 class="titre-tuto"><a href="../'.$dossier.$contenu->id().'">'.htmlspecialchars($contenu->titre()).'</a></h3>';

                     $infosAuteurs = $managerAuteur->tousLesAuteurs($contenu->id());

                        foreach ($infosAuteurs as $infosAuteur)
                        {
                          $auteur = new Auteur($infosAuteur);
                            echo '<span class="auteur-tuto"><a href="../forum/voirprofil.php?action=consulter&m='.$auteur->membre().'">'.$auteur->pseudo().'</a></span>';
                        }

                        $donnneesCat = $managerCategorie->infosCategorie($contenu->cat());
                        $cat = new Categorie($donnneesCat);

                 echo '<span class="cat-tuto">'.$cat->nom().'</span>
              </div>  


         </div>';
    }
}
else
{
  
        echo '<p>  Aucun contenus pour vous pour le moment :)
             <p>';
}

?>

<p class="pagination">

<?php  paginationListe($page ,$nbre_pages, 'mescontenus.php')    
?>

</p>

</div>

<?php include "../includes/footer.php"; ?>