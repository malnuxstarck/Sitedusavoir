
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

<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Blog</a></li><img class="fleche" src="../images/icones/fleche.png"/><li> <span style="color:black;">Edition Article</span> </li>
         </ul>
  </div>

  <div class="page">

 <?php 
 
 if(empty($_POST))
 {

?>

<h1 class="titre"> Editer un Article </h1>
	<div class="formulaire formulaire-edition">
	    <form action="" method="POST">

        <?php
	     echo '<div class="input">
	          <label for="titre"></label>
	          <input type="text" name="titre" value="'.$article['articles_titre'].'" required />
	         </div>
              <input type="hidden" name="article" value="'.$art.'"/>
	         <div class="textarea">
	              <textarea name="intro" required>'.$article['articles_intro'].'</textarea>
	         </div>';

	     $requete = $bdd->prepare('SELECT * FROM articles_parties 
	                               WHERE articles_id = :article ORDER BY parties_id');
	     $requete->execute(array('article' => $art));

	     while($a = $requete->fetch())
	     {
	     	echo '<div class="partie">
	     	               
	     	                    <h3 class="titre titre-partie">'.$a['parties_titre'].'</h3>

	     	                    <div class="partie-text">
	     	                    <ul>
	     	                        <li> <a href="editerpartie.php?partie='.$a['parties_id'].'&action=edit&article='.$art.'"><span>Editer</span><img src="../images/icones/edit.png"/></a> </li>
	     	                        <li> <a href="editerpartie.php?partie='.$a['parties_id'].'&action=sup&article='.$art.'"> <span>Delete</span><img src="../images/icones/edit.png"/></a> </li>
	     	                    </ul>
	     	                    <p>'.htmlspecialchars($a['parties_contenu']).'</p>
	     	               </div>
	     
	     	      </div>';
	     }

	     echo '<div class="textarea">
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

	        <div class="submit submit-tuto">
		       <input name="validation" type="submit" value="Brouillon" />
		   </div>

		    <div class="submit submit-tuto">
		       <input type="submit" value="validation" />
		   </div>

    </form>
</div>

<div class="modification">
<span class="modification-entete">Modification </span>
    <ul>
      <?php

        echo '<li><a href="ajouter.php?article='.$art.'&action=partie">Ajouter une partie </a></li>
        <li><a href="ajouter.php?article='.$art.'&action=auteur">Ajouter un auteur </a></li>';

        ?>
    </ul>
</div>

<?php

}

else
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
