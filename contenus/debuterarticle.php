<?php
$titre="Nouveau Article | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

$req = $bdd->query('SELECT cat_id , cat_nom 
	                FROM categorie
	                ORDER BY cat_id');


if(!verif_auth(MODO))
{
	$_SESSION['flash']['danger'] = "Reservez au moderateur ";
	header('Location:./index.php');
}


if(empty($_POST))
{

?>



   <div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><a href="./index.php">Blog</a></li><img class="fleche" src="../images/icones/fleche.png"/><li> <span style="color:black;">Commencer Article</span> </li>
         </ul>
  </div>
  <div class="page">

	<h1 class="titre"> Debuter un Article </h1>


	<div class="formulaire formulaire-tuto">

		   <form method="POST" action="debuterarticle.php" enctype="multipart/form-data">

			<div class="input input-tuto">
			      <label for="titre"></label>
			        <input type="text" name="titre" placeholder="Le titre du Article" required />
			</div>

			<div class="textarea textarea-tuto">
			      <textarea name="intro" required >Votre introduction</textarea>
			</div>

			<div class="textarea textarea-tuto">
			      <textarea  name="conc" required >Votre conclusion </textarea>
			</div>

			<div class="input input-tuto">
			      <label for="banniere"></label>
			         <input type="file" name="banniere"/>
			</div>

			<div class="select">
			    <select name="cat">
			           <?php

			           while($cat = $req->fetch())
			           {
			              echo '<option value="'.$cat['cat_id'].'">'.$cat['cat_nom'].'</option>';
			           }

			           ?>
			    </select>
			</div>

			<div class="submit submit-tuto">
			     <input type="submit" value="Envoyer" />
			</div>

		</form>
   </div>
   </div>		

	<?php
}
 
else
{
	$i = 0 ;

	$titre = (isset($_POST['titre']))?$_POST['titre']:"";
	$intro = (isset($_POST['intro']))?$_POST['intro']:"";
	$conc = (isset($_POST['conc']))?$_POST['conc']:"";
    $cat = (isset($_POST['cat']))?$_POST['cat']:"";

	if(empty($_POST['titre']))
	{
      $i++;
      
      $mess1 = 'Votre titre est vide .';
    }

    if(empty($intro) || empty($conc))
    {
      	$i++;

      	$mess2 = "Votre introduction ou votre conclusion est vide .";
     }

      if(empty($_POST['cat']))
      {
      	$i++;
      	$mess3 = "Une categorie doit etre selectionner .";
      }
      

      if(isset($_FILES['banniere']) AND $_FILES['banniere']['error'] == 0)
       {
       	   $banniere = $_FILES['banniere'];
           $extensions_valides  = array('png','jpg','jpeg','gif');
           $extension = substr(strchr($banniere['name'],'.'),1);

           if(in_array($extension,$extensions_valides))
           {

	           	$nom_ban = $id.'-'.time().'.'.$extension;
	           	move_uploaded_file($banniere['tmp_name'], './articles_ban/'.$nom_ban);


	           	switch($extension)
		      	{
		      		case "png":

		            $source = imagecreatefrompng('./articles_ban/'.$nom_ban);
			      	$destination = imagecreatetruecolor(300,225);

			      	$largeur_s = imagesx($source);
			      	$hauteur_s = imagesy($source);
			      	$largeur_d = imagesx($destination);
			      	$hauteur_d = imagesy($destination);

			      	imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_d, $hauteur_d, $largeur_s, $hauteur_s);
			      	imagepng($destination,'./articles_ban/'.$nom_ban);

	                break;

	                case "jpg":

	                $source = imagecreatefromjpeg('./articles_ban/'.$nom_ban);
			      	$destination = imagecreatetruecolor(300,225);

			      	$largeur_s = imagesx($source);
			      	$hauteur_s = imagesy($source);
			      	$largeur_d = imagesx($destination);
			      	$hauteur_d = imagesy($destination);

			      	imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_d, $hauteur_d, $largeur_s, $hauteur_s);
			        imagejpeg($destination,'./articles_ban/'.$nom_ban);

	                break;

	                case "gif":

	                $source = imagecreatefromgif('./articles_ban/'.$nom_ban);
			      	$destination = imagecreatetruecolor(300,225);

			      	$largeur_s = imagesx($source);
			      	$hauteur_s = imagesy($source);
			      	$largeur_d = imagesx($destination);
			      	$hauteur_d = imagesy($destination);

			      	imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_d, $hauteur_d, $largeur_s, $hauteur_s);
			      	
			        imagegif($destination,'./articles_ban/'.$nom_ban);

	                break;


	                case "jpeg":

	                $source = imagecreatefromjpeg('./articles_ban/'.$nom_ban);
			      	$destination = imagecreatetruecolor(300,225);

			      	$largeur_s = imagesx($source);
			      	$hauteur_s = imagesy($source);
			      	$largeur_d = imagesx($destination);
			      	$hauteur_d = imagesy($destination);

			      	imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_d, $hauteur_d, $largeur_s, $hauteur_s);
			        imagejpeg($destination,'./articles_ban/'.$nom_ban);

	                break;



               }





           }


           

       }
       else
       {
       	   $banniere = './articles_ban/ban.jpg';
           $source = imagecreatefromjpeg($banniere);
		   $destination = imagecreatetruecolor(300,225);

			      	$largeur_s = imagesx($source);
			      	$hauteur_s = imagesy($source);
			      	$largeur_d = imagesx($destination);
			      	$hauteur_d = imagesy($destination);

			      	imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_d, $hauteur_d, $largeur_s, $hauteur_s);

			      	$nom_ban = $id.'-'.time().'.png';

			      	imagejpeg($destination,'./articles_ban/'.$nom_ban);

       }

     

  



      if(!$i)
      {
      	$req = $bdd->prepare('INSERT INTO articles (articles_titre , articles_intro, articles_conc ,articles_banniere ,articles_date,articles_cat,articles_validation)
      		                  VALUES(:titre , :intro ,:conc ,:ban , NOW(), :cat , \'0\')');
      	$req->execute(array(

              'titre'  => $titre,
              'intro'  => $intro,
              'conc'   => $conc,
              'ban'    => $nom_ban,
              'cat'    => $cat


      		));

      	$req->closeCursor();

      	$dernierid = $bdd->lastInsertId();

      	$req1 = $bdd->prepare('INSERT INTO articles_par (membre_id,articles_id)
      		                  VALUES(:membre , :article)');
      	$req1->bindParam(':membre',$id,PDO::PARAM_INT);
      	$req1->bindParam(':article',$dernierid, PDO::PARAM_INT);

      	$req1->execute();

      	$_SESSION['flash']['success'] = " Votre Article a bien été creer , rendez vous Ld le modifier et/ou l'achever";
      	header('Location:index.php');

      }
      else
      {
      	$_SESSION['flash']['danger'] = $mess1 ."</br>".$mess2 ."</br>" . $mess3 ; 
      	header('Location:index.php');
      }	

}







