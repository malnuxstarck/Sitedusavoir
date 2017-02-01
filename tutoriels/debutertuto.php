<?php

$titre="Nouveau Tuto | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

$req = $bdd->query('SELECT cat_id , cat_nom 
	                FROM categorie
	                ORDER BY cat_id');


if(!$id)
{
	$_SESSION['flash']['danger'] = "Vous devez etre connecter  pour rediger un tuto";
	header('Location:../connexion.php');
}


?>


<p id="fildariane"> <i><a href="../index.php">Accueil </a> --> <a href="index.php">Tutoriels</a>-->Nouveau tutoriel</i></p>

<h2 class="titre" style="text-align:center"> Debuter un tutoriel </h2>

<form method="POST" action="debutertuto.php" enctype="multipart/form-data">

	<div class="input_titre">
	      <p class="icon">
	         <img src="../images/icones/text.png" alt="icon"/>
	      </p>
	      <p>
	        <input type="text" name="titre" placeholder="Le titre du tutoriel" required />
	      </p>  
	</div>

	<div class="textarea-titre">
	      <textarea class="textarea" name="intro" required >Votre introduction</textarea>
	</div>

	<div class="textarea-titre">
	      <textarea class="textarea" name="conc" required >Votre conclusion </textarea>
	</div>

	<div class="input_fil">
	      <p>
	        <input type="file" name="banniere"/>
	      </p>  
	</div>

	<div class="select">
	    <select name="cat">
	           <?php
                   $req = $bdd->query('SELECT cat_id , cat_nom 
	                               FROM categorie
	                               ORDER BY cat_id');

	           while($cat = $req->fetch())
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

<?php
 
if(!empty($_POST))
{
	$i = 0 ;

	$titre = (isset($_POST['titre']))?$_POST['titre']:"";
	$intro = (isset($_POST['intro']))?$_POST['intro']:"";
	$conc = (isset($_POST['conc']))?$_POST['conc']:"";
	$banniere = (isset($_POST["banniere"]))?$_POST['ban']:"./tutos_ban/350x150.png";
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
      

      if(!empty($banniere))
      {
	      	$extension_autorisés = array('png','jpg','gif','jpeg');

	      	$extension = substr(strrchr($banniere ,'.'),1);

	      	if(!in_array($extension ,$extension_autorisés))
	      	{
		        $banniere = './tutos_ban/ban.png';
		      	
	            $source = imagecreatefrompng($banniere);
		      	$destination = imagecreatetruecolor(300,150);

		      	$largeur_s = imagex($source);
		      	$hauteur_s = imagey($source);
		      	$largeur_d = imagex($destination);
		      	$hauteur_d = imagey($destination);

		      	imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_d, $hauteur_d, $largeur_s, $hauteur_s);
		      	$banniere = $id.'-'.time().'.png';

		      	imagepng($destination,'./tutos_ban/'.$banniere);
	        }
	        else
	        {
		      	switch($extension)
		      	{
		      		case "png":

		            $source = imagecreatefrompng($banniere);
			      	$destination = imagecreatetruecolor(300,150);

			      	$largeur_s = imagesx($source);
			      	$hauteur_s = imagesy($source);
			      	$largeur_d = imagesx($destination);
			      	$hauteur_d = imagesy($destination);

			      	imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_d, $hauteur_d, $largeur_s, $hauteur_s);
			      	$banniere = $id.'-'.time().'.png';

			      	imagepng($destination,'./tutos_ban/'.$banniere);

	                break;

	                case "jpg":

	                $source = imagecreatefromjpeg($banniere);
			      	$destination = imagecreatetruecolor(300,150);

			      	$largeur_s = imagesx($source);
			      	$hauteur_s = imagesy($source);
			      	$largeur_d = imagesx($destination);
			      	$hauteur_d = imagesy($destination);

			      	imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_d, $hauteur_d, $largeur_s, $hauteur_s);
			      	$banniere = $id.'-'.time().'.jpg';

			      	imagepng($destination,'./tutos_ban/'.$banniere);

	                break;

	                case "gif":

	                $source = imagecreatefromgif($banniere);
			      	$destination = imagecreatetruecolor(300,150);

			      	$largeur_s = imagesx($source);
			      	$hauteur_s = imagesy($source);
			      	$largeur_d = imagesx($destination);
			      	$hauteur_d = imagesy($destination);

			      	imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_d, $hauteur_d, $largeur_s, $hauteur_s);
			      	$banniere = $id.'-'.time().'.gif';

			      	imagepng($destination,'./tutos_ban/'.$banniere);

	                break;


	                case "jpeg":

	                $source = imagecreatefromjpeg($banniere);
			      	$destination = imagecreatetruecolor(300,150);

			      	$largeur_s = imagesx($source);
			      	$hauteur_s = imagesy($source);
			      	$largeur_d = imagesx($destination);
			      	$hauteur_d = imagesy($destination);

			      	imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_d, $hauteur_d, $largeur_s, $hauteur_s);
			      	$banniere = $id.'-'.time().'.jpg';

			      	imagepng($destination,'./tutos_ban/'.$banniere);

	                break;




		      	}
	      }

       }



      if(!$i)
      {
      	$req = $bdd->prepare('INSERT INTO tutos (tutos_titre , tutos_intro, tutos_conc ,tutos_banniere ,tutos_date,tutos_cat,tutos_validation)
      		                  VALUES(:titre , :intro ,:conc ,:ban , NOW(), :cat , \'0\')');
      	$req->execute(array(

              'titre'  => $titre,
              'intro'  => $intro,
              'conc'   => $conc,
              'ban'    => $banniere,
              'cat'    => $cat,


      		));

      	$dernierid = $bdd->lastInsertId();

      	$req1 = $bdd->prepare('INSERT INTO tutos_par (membre_id,tutos_id)
      		                  VALUES(:membre , :tuto)');
      	$req1->bindParam(':membre',$id,PDO::PARAM_INT);
      	$req1->bindParam(':tuto',$dernierid, PDO::PARAM_INT);

      	$req1->execute();

      	$_SESSION['flash']['success'] = " Votre tuto a ien été creer , rendez vous dans la page edition pour l'editer et/ou l'achever";
      }
      else
      {
      	$_SESSION['flash']['danger'] = $mess1 ."</br>".$mess2 ."</br>" . $mess3 ; 
      	header('Location:debutertuto.php');
      }	
}







