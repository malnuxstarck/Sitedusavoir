
<?php

$titre="Edition Tuto | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

$tuto =(isset($_GET['tuto']))?$_GET['tuto']:"";



if(!$id)
{
	$_SESSION['flash']['danger'] = "Vous devez etre connecter d'abord";
	header('Location:../connexion.php');
}


$req = $bdd->prepare('SELECT tutos_titre , tutos.tutos_id , tutos_intro , tutos_conc ,tutos_cat
	                  FROM tutos
	                  LEFT JOIN tutos_par
	                  ON tutos.tutos_id = tutos_par.tutos_id
	                  LEFT JOIN membres
	                  ON membres.membre_id = tutos_par.membre_id
	                  WHERE tutos.tutos_id = :tuto 
	                  AND membres.membre_id = :membre');

$req->bindParam(':tuto', $tuto , PDO::PARAM_INT);
$req->bindParam(':membre', $id , PDO::PARAM_INT);

$req->execute();

$tutoriel = $req->fetch();

?>

<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Tutoriels</a></li><img class="fleche" src="../images/icones/fleche.png"/><li> <span style="color:black;">Edition Tuto</span> </li>
         </ul>
  </div>



<?php 

if(empty($_POST)) 
        {  
?>

<div class="page">

	<h1 class="titre"> Editer un tuto </h1>
	<div class="formulaire formulaire-edition">

	    <form action="" method="POST">
		     <?php

		     echo '<div class="input">
		             <label for="titre"></label>
		             <input type="text" name="titre" value="'.$tutoriel['tutos_titre'].'" required />
		         </div>
	              <input type="hidden" name="tuto" value="'.$tuto.'"/>
		         <div class="textarea">
		              <textarea name="intro" required>'.$tutoriel['tutos_intro'].'</textarea>
		         </div>';

		     $requete = $bdd->prepare('SELECT * FROM tutos_parties 
		                               WHERE tutos_id = :tuto ORDER BY parties_id');
		     $requete->execute(array('tuto' => $tuto));

		     while($t = $requete->fetch())
		     {
		     	echo '<div class="partie">

		     	              <h3 class="titre titre-partie">'.$t['parties_titre'].'</h3>
		     	                    
		     	               <div class="partie-text">

		     	                     <ul>
		     	                        <li> 
		     	                           <a href="editerpartie.php?partie='.$t['parties_id'].'&action=edit&tuto='.$tuto.'"><span>Editer</span><img src="../images/icones/edit.png"/></a>
		     	                        </li>

		     	                        <li>
		     	                           <a href="editerpartie.php?partie='.$t['parties_id'].'&action=sup&tuto='.$tuto.'"> <span>Delete</span><img src="../images/icones/del.png"/> </a> 
		     	                         </li>

		     	                    </ul>

		     	                   <p>'.htmlspecialchars($t['parties_contenu']).'</p>
		     	               </div>
		     	      </div>';
		     }

		     echo '<div class="textarea">
		              <textarea name="conc" required>'.htmlspecialchars($tutoriel['tutos_conc']).'</textarea>
		         </div>';

		     ?>

		     <div class="select">
			    <select name="cat">
			           <?php
		                   $req1 = $bdd->query('SELECT cat_id , cat_nom 
			                               FROM categorie
			                               ORDER BY cat_id');

			           while($cat = $req1->fetch())
			           {
			           	  if($cat['cat_id'] == $tutoriel['tutos_cat'])
			           	  {
			                 echo '<option value="'.$cat['cat_id'].'" selected="selected">'.$cat['cat_nom'].'</option>';
			              }
			              else{
			              	  echo '<option value="'.$cat['cat_id'].'">'.$cat['cat_nom'].'</option>';
			              }
			           }
			           ?>
			    </select>
		    </div>

		    <div class="submit submit-tuto">
		       <input  type="submit" value="Brouillon" />
		   </div>

		    <div class="submit submit-tuto">
		       <input name="validation" type="submit" value="validation" />
		   </div>

	    </form>
	</div>

	<div class="modification">
	    <span class="modification-entete">Modification </span>
	    <ul>
	      <?php

	        echo '<li><a href="ajouter.php?tuto='.$tuto.'&action=partie">Ajouter une partie </a></li>
	        <li><a href="ajouter.php?tuto='.$tuto.'&action=auteur">Ajouter un auteur </a></li>';

	        ?>
	    </ul>
	</div>
</div>	
<?php

  include "../includes/footer.php";
}

else
{
   $titre = (isset($_POST['titre']))?$_POST['titre']:"";
   $introduction = (isset($_POST['intro']))?$_POST['intro']:"";
   $conclusion = (isset($_POST['conc']))?$_POST['conc']:"";
   $tuto = (isset($_POST['tuto']))?$_POST['tuto']:"";
   $cat = (isset($_POST['cat']))?$_POST['cat']:$tutoriel['tutos_cat'];

    if(empty($introduction) || empty($conclusion) || empty($titre) || empty($tuto))
   {
	   	$_SESSION['flash']['danger'] = "Soit le titre et/ou l'introduction et/ou la conclusion est vide";
	   	header('Location:editiontuto.php?tuto='.$_POST['tuto']);
   }
   else
   {
   	$insertion = $bdd->prepare('UPDATE tutos 
   		                        SET tutos_intro = :intro ,tutos_conc = :conc , tutos_titre = :titre ,tutos_cat = :categorie
   		                        WHERE tutos_id = :tuto ');

   	$insertion->execute(array('intro' => $introduction , 
   		                      'titre' => $titre , 
   		                      'conc' => $conclusion,
   		                       'tuto' => $tuto,
   		                       'categorie' => $cat));
   	$insertion->closeCursor();

   	$_SESSION['flash']['success'] = "Tutos mis a jour ";
   	header('Location:editiontuto.php?tuto='.$tuto);
   }


}
