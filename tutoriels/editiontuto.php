
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
<p id="fildariane"><i><a href="../index.php">Accueil </a>--><a href="./index.php"> Tutoriels </a>-->Edition de Tuto </i></p>
<h2 class="titre" style="text-align: center;"> Editer un tuto </h2>
<div class="edit-tuto">
    <form action="" method="POST">
	     <?php

	     echo '<div class="input">
	          <input type="text" name="titre" value="'.$tutoriel['tutos_titre'].'" required />
	         </div>
              <input type="hidden" name="tuto" value="'.$tuto.'"/>
	         <div>
	              <textarea name="intro" required>'.$tutoriel['tutos_intro'].'</textarea>
	         </div>';

	     $requete = $bdd->prepare('SELECT * FROM tutos_parties 
	                               WHERE tutos_id = :tuto ORDER BY parties_id');
	     $requete->execute(array('tuto' => $tuto));

	     while($t = $requete->fetch())
	     {
	     	echo '<div class="partie>
	     	               <div class="input">
	     	                    <h2>'.$t['parties_titre'].'</h2>
	     	                    <ul>
	     	                        <li> <a href="editerpartie.php?partie='.$t['parties_id'].'&action=edit&tuto='.$tuto.'"> Editer </a> </li>
	     	                        <li> <a href="editerpartie.php?partie='.$t['parties_id'].'&action=sup&tuto='.$tuto.'"> Supprimer </a> </li>
	     	                    </ul>
	     	               </div>

	     	               <div class="textarea">
	     	                   <p>'.htmlspecialchars($t['parties_contenu']).'</p>
	     	               </div>
	     	      </div>';
	     }

	     echo '<div>
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
		              echo '<option value="'.$cat['cat_id'].'">'.$cat['cat_nom'].'</option>';
		           }
		           ?>
		    </select>
	    </div>

	    <div class="valid">
	       <input type="submit" value="Envoyer" />
	   </div>

    </form>
</div>

<div class="Modification">
    <ul>
      <?php

        echo '<li><a href="ajouter.php?tuto='.$tuto.'&action=partie">Ajouter une partie </a></li>
        <li><a href="ajouter.php?tuto='.$tuto.'&action=auteur">Ajouter un auteur </a></li>';

        ?>
    </ul>
</div>

<?php

if(!empty($_POST))
{
   $titre = (isset($_POST['titre']))?$_POST['titre']:"";
   $introduction = (isset($_POST['intro']))?$_POST['intro']:"";
   $conclusion = (isset($_POST['conc']))?$_POST['conc']:"";
   $tuto = (isset($_POST['tuto']))?$_POST['tuto']:"";

    if(empty($introduction) || empty($conclusion) || empty($titre))
   {
	   	$_SESSION['flash']['danger'] = "Soit le titre et/ou l'introduction et/ou la conclusion est vide";
	   	header('Location:editiontuto.php?tuto='.$_POST['tuto']);
   }
   else
   {
   	$insertion = $bdd->prepare('UPDATE tutos 
   		                        SET tutos_intro = :intro ,tutos_conc = :conc , tutos_titre = :titre 
   		                        WHERE tutos_id = :tuto ');

   	$insertion->execute(array('intro' => $introduction , 
   		                      'titre' => $titre , 
   		                      'conc' => $conclusion,
   		                       'tuto' => $tuto));
   	$insertion->closeCursor();

   	$_SESSION['flash']['success'] = "Tutos mis a jour ";
   }


}
