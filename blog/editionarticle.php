
<?php

$titre="Edition Article | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

$art =(isset($_GET['article']))?$_GET['article']:"";



if(!verif_auth(MODO))
{
	$_SESSION['flash']['danger'] = " Reservez au moderateur ";
	header('Location:../index.php');
}


$req = $bdd->prepare('SELECT articles_titre , articles.articles_id , articles_intro , articles_conc ,articles_cat
	                  FROM articles
	                  LEFT JOIN articles_par
	                  ON articles.articles_id = articles_par.articles_id
	                  LEFT JOIN membres
	                  ON membres.membre_id = articles_par.membre_id
	                  WHERE articles.articles_id = :article 
	                  AND membres.membre_id = :membre');

$req->bindParam(':article', $art , PDO::PARAM_INT);
$req->bindParam(':membre', $id , PDO::PARAM_INT);

$req->execute();

$article = $req->fetch();

?>
<p id="fildariane"><i><a href="../index.php">Accueil </a>--><a href="./index.php"> Blog </a>-->Edition de Article </i></p>
<h2 class="titre" style="text-align: center;"> Editer un Article </h2>
<div class="edit-article">
    <form action="" method="POST">
	     <?php

	     echo '<div class="input">
	          <input type="text" name="titre" value="'.$article['articles_titre'].'" required />
	         </div>
              <input type="hidden" name="article" value="'.$art.'"/>
	         <div>
	              <textarea name="intro" required>'.$article['articles_intro'].'</textarea>
	         </div>';

	     $requete = $bdd->prepare('SELECT * FROM articles_parties 
	                               WHERE articles_id = :article ORDER BY parties_id');
	     $requete->execute(array('article' => $art));

	     while($a = $requete->fetch())
	     {
	     	echo '<div class="partie>
	     	               <div class="input">
	     	                    <h2>'.$a['parties_titre'].'</h2>
	     	                    <ul>
	     	                        <li> <a href="editerpartie.php?partie='.$a['parties_id'].'&action=edit&article='.$art.'"> Editer </a> </li>
	     	                        <li> <a href="editerpartie.php?partie='.$a['parties_id'].'&action=sup&article='.$art.'"> Supprimer </a> </li>
	     	                    </ul>
	     	               </div>

	     	               <div class="textarea">
	     	                   <p>'.htmlspecialchars($a['parties_contenu']).'</p>
	     	               </div>
	     	      </div>';
	     }

	     echo '<div>
	              <textarea name="conc" required>'.htmlspecialchars($article['articles_conc']).'</textarea>
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

        echo '<li><a href="ajouter.php?article='.$art.'&action=partie">Ajouter une partie </a></li>
        <li><a href="ajouter.php?article='.$art.'&action=auteur">Ajouter un auteur </a></li>';

        ?>
    </ul>
</div>

<?php

if(!empty($_POST))
{
   $titre = (isset($_POST['titre']))?$_POST['titre']:"";
   $introduction = (isset($_POST['intro']))?$_POST['intro']:"";
   $conclusion = (isset($_POST['conc']))?$_POST['conc']:"";
   $art = (isset($_POST['article']))?$_POST['article']:"";

    if(empty($introduction) || empty($conclusion) || empty($titre) ||empty($art))
   {
	   	$_SESSION['flash']['danger'] = "Soit le titre et/ou l'introduction et/ou la conclusion est vide";
	   	header('Location:editionarticle.php?article='.$_POST['article']);
   }
   else
   {
   	$insertion = $bdd->prepare('UPDATE articles 
   		                        SET aricles_intro = :intro ,articles_conc = :conc , articles_titre = :titre 
   		                        WHERE articles_id = :article ');

   	$insertion->execute(array('intro' => $introduction , 
   		                      'titre' => $titre , 
   		                      'conc' => $conclusion,
   		                       'article' => $art));
   	$insertion->closeCursor();

   	$_SESSION['flash']['success'] = "Articles mis a jour ";
   }


}
