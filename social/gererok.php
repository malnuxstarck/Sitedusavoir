
<?php


include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

$action = isset($_GET['action'])?$_GET['action']:"";

if(empty($action))
{
	header('Location:index.php');
}

if(!$id)
{
	header('Location:../connexion.php');
}


switch($action)
{
	case "creer":

	// Creer un nouveau groupe

	   $nom = (isset($_POST['nom']))?$_POST['nom']:"";
	   $description = isset($_POST['description'])?$_POST['description']:"";
	   $banniere =   isset($_FILES['banniere'])?$_FILES['banniere']:"";

	   if(empty($nom) || empty($description))
	   {
	   	$_SESSION['flash']['danger'] = "Un nom et une description doivent etre fournis .";
	   	header('Location:gerer.php?action=creer');
	   }

	  if($banniere['error'] == 0)
	  {
	  	//Erreur d'envoie ou pas d'images envoyer

		  	$tailleban = getimagesize($banniere['tmp_name']);

		  	/*

		  	if($tailleban['0'] > 1000 || $tailleban['1'] > 100)
		  	{
		  		$_SESSION['flash']['danger'] = " Image tres grosse , Largeur-max = 1000 et hauteur-max =100";
		  		header('Location:gerer.php?action=creer');

		  	}
		  	*/

		  	$extension = substr(strchr($banniere['name'],'.'),1);
		  	$extensions_valides = array('png','jpeg','jpg','gif');

		  	if(in_array($extension,$extensions_valides))
		  	{
		  		//Un format valides envoyees

		  		$nom_ban = $id."-".time().".".$extension;

		  		move_uploaded_file($banniere['tmp_name'], "./photos/".$nom_ban);

		  		switch($extension)
		  		{
		  			case "png":

		  			      $source =imagecreatefrompng("./photos/".$nom_ban) ;

		  			      $largeur_s = imagesx($source);
		  			      $hauteur_s = imagesy($source);

		  			      $destination = imagecreatetruecolor(100, 100);
                          $miniban = $id."-min-".time().".".$extension;
		  			      imagecopyresampled($destination, $source, 0, 0, 0, 0, 100, 100, $largeur_s, $hauteur_s);
		  			      imagepng($destination , "./photos/".$miniban);


		  			break;
		  			case "jpg":

		  	                $source =imagecreatefromjpeg("./photos/".$nom_ban) ;

		  			      $largeur_s = imagesx($source);
		  			      $hauteur_s = imagesy($source);

		  			      $destination = imagecreatetruecolor(100, 100);
                          $miniban = $id."-min-".time().".".$extension;
		  			      imagecopyresampled($destination, $source, 0, 0, 0, 0, 100, 100, $largeur_s, $hauteur_s);
		  			      imagejpeg($destination , "./photos/".$miniban);

		  			break;

		  			case "jpeg":

		  			       $source =imagecreatefromjpeg("./photos/".$nom_ban) ;

		  			      $largeur_s = imagesx($source);
		  			      $hauteur_s = imagesy($source);

		  			      $destination = imagecreatetruecolor(100, 100);
                          $miniban = $id."-min-".time().".".$extension;
		  			      imagecopyresampled($destination, $source, 0, 0, 0, 0, 100, 100, $largeur_s, $hauteur_s);
		  			      imagejpeg($destination , "./photos/".$miniban);

		  			break;
		  			case "gif":
		  			      $source =imagecreatefromgif("./photos/".$nom_ban) ;

		  			      $largeur_s = imagesx($source);
		  			      $hauteur_s = imagesy($source);

		  			      $destination = imagecreatetruecolor(100, 100);
                          $miniban = $id."-min-".time().".".$extension;
		  			      imagecopyresampled($destination, $source, 0, 0, 0, 0, 100, 100, $largeur_s, $hauteur_s);
		  			      imagegif($destination , "./photos/".$miniban);

		  			break;
		  			

		  		}
		  	}
		  	else
		  	{
		  	   $nom_ban = "default.png";
		  	   $source = imagecreatefrompng("./photos/default.png");
               $largeur_s = imagesx($source);
		  	   $hauteur_s = imagesy($source);
               $destination = imagecreatetruecolor(100, 100);
               $miniban = $id."-min-".time()."."."png";
		  	   imagecopyresampled($destination, $source, 0, 0, 0, 0, 100, 100, $largeur_s, $hauteur_s);
		  	   imagepng($destination , "./photos/".$miniban);
            }

	  }
	  else
	  {
	  	       $nom_ban = "default.png";
               $source = imagecreatefrompng("./photos/default.png");
               $largeur_s = imagesx($source);
		  	   $hauteur_s = imagesy($source);
               $destination = imagecreatetruecolor(100, 100);
               $miniban = $id."-min-".time().".png";
		  	   imagecopyresampled($destination, $source, 0, 0, 0, 0, 100, 100, $largeur_s, $hauteur_s);
		  	   imagepng($destination , "./photos/".$miniban);
      }

	  $nouveau = $bdd->prepare('INSERT INTO social_groupes (groupes_nom , groupes_description, groupes_createur,groupes_banniere, groupes_banniere_min,groupes_date)
	  	                        VALUES (:nom, :description ,:createur, :banniere , :banmin, NOW())');
	  $nouveau->execute(array(
	  	                        'createur' => $id ,
	  	                        'nom' => $nom,
	  	                        'description' => $description,
	  	                        'banniere' => $nom_ban,
	  	                        'banmin' => $miniban
	  	                       ));

	  $derniergroupe = $bdd->lastInsertId();

	  $admin = $bdd->prepare('INSERT INTO social_gs_admin (membre_id , groupes_id)
	  	                       VALUES( :membre , :groupe )');
	  $admin->bindParam(':membre', $id , PDO::PARAM_INT);
	  $admin->bindParam(':groupe',$derniergroupe , PDO::PARAM_INT);

	  $admin->execute();

	  $admin->closeCursor();

	  $membre = $bdd->prepare('INSERT INTO social_gs_membres (groupes_id , membre_id)
	  	                       VALUES (:groupe , :membre)');
	  $membre->bindParam(':membre', $id , PDO::PARAM_INT);
	  $membre->bindParam(':groupe',$derniergroupe , PDO::PARAM_INT);

	   $membre->execute();

	  $membre->closeCursor();

      $_SESSION['flash']['success'] = " Le groupe est creer avec succes";
	  header('Location:mesgroupes.php');
          

	break;

	case "del":
	    $groupeid = (isset($_GET['g']))?$_GET['g']:"";
	    $sur = (isset($_GET['sur']))?$_GET['sur']:""; 

	    if(empty($sur) || empty($groupeid))
	    {
	    	header('Location:./index.php');
	    }

	    $groupea = $bdd->prepare('SELECT social_groupes.groupes_id 
	    	                      FROM social_groupes 
	    	                      JOIN social_gs_admin 
	    	                      ON social_groupes.groupes_id = social_gs_admin.groupes_id
	    	                      WHERE social_gs_admin.groupes_id = :groupe AND social_gs_admin.membre_id = :membre');
	    $groupea->bindParam(':groupe',$groupeid, PDO::PARAM_INT);
	    $groupea->bindParam(':membre', $id,PDO::PARAM_INT);
	    $groupea->execute();

	    if($groupeasup = $groupea->fetch())
	    {
	    	$groupesupprimer = $groupeasup['groupes_id'];

            $suppression = $bdd->prepare('DELETE FROM social_groupes WHERE groupes_id = :groupe');
            $suppression->execute(array('groupe' => $groupesupprimer));
            

            $membressupp = $bdd->prepare('DELETE FROM social_gs_membres WHERE groupes_id = :groupe');

             $membressupp->execute(array('groupe' => $groupesupprimer));
           

             $adminsupp = $bdd->prepare('DELETE FROM social_gs_admin WHERE groupes_id = :groupe');

             $adminsupp->execute(array('groupe' => $groupesupprimer));

             $_SESSION['flash']['danger'] = "Groupe Supprimer avec succes";
	    		header('Location:./mesgroupes.php');
           
	    }
	    else
	    	{
	    		$_SESSION['flash']['danger'] = "Ce groupe semble ne pas exister";
	    		header('Location:./index.php');
	    	}
		
	break;

	case "add":
	       $groupeid = (isset($_GET['g']))?$_GET['g']:"";
	        $pseudo = (isset($_POST['nouveau']))?$_POST['nouveau']:"";

	       if(empty($groupeid) || empty($pseudo))
	       {
	       	header('Location:mesgroupes.php');
	       }
         $addm = $bdd->prepare('SELECT membre_id FROM membres WHERE membre_pseudo = LOWER(:pseudo)');
         $addm->bindParam(':pseudo',$pseudo, PDO::PARAM_INT);
         $addm->execute();

         if($membre = $addm->fetch())
         {
         	 $membre = $membre['membre_id'];

         	 $ajoutmembre = $bdd->prepare('INSERT INTO social_gs_membres (groupes_id , membre_id)
         	 	                           VALUES(:groupe , :membre)');
         	 $ajoutmembre->bindParam(':groupe',$groupeid,PDO::PARAM_INT);
         	 $ajoutmembre->bindParam(':membre',$membre,PDO::PARAM_INT);
         	 $ajoutmembre->execute();
         	 $ajoutmembre->closeCursor();

         	 $_SESSION['flash']['success'] = "Membre ajouter avec succes";
         	 header('Location:voirgroupe.php?g='.$groupeid);
         }

             
	break;
	case "edit":
	  $groupeid = (isset($_GET['g']))?$_GET['g']:"";
	  $nom = (isset($_GET['nom']))?$_GET['nom']:"";
	  $description = (isset($_GET['description']))?$_GET['description']:"";
	  $banniere = isset($_FILES['banniere'])?$_FILES['banniere']:"";

	  if(empty($groupeid) || empty($nom) || empty($description))
	  {
	  	header('Location:mesgroupes.php');
	  }

	  if(!empty($banniere) || $banniere['error'] == 0)
	  {
	  	//Erreur d'envoie ou pas d'images envoyer

		  	$tailleban = getimagesize($banniere);

		  	if($tailleban['0'] > 1000 || $tailleban['1'] > 100)
		  	{
		  		$_SESSION['flash']['danger'] = " Image tres grosse , Largeur-max = 1000 et hauteur-max =100";
		  		header('Location:gerer.php?action=creer');

		  	}

		  	$extension = substr(strchr($banniere['name'],'.'),1);
		  	$extensions_valides = array('png','jpeg','jpg','gif');

		  	if(in_array($extension,$extensions_valides))
		  	{
		  		//Un format valides envoyees

		  		$nom_ban = $id."-".time().".".$extension;

		  		move_uploaded_file($banniere['tmp_name'], "./photos/".$nom_ban);

		  		switch($extension)
		  		{
		  			case "png":

		  			      $source = "./photos/".$nom_ban ;

		  			      $largeur_s = imagesx($source);
		  			      $hauteur_s = imagesy($source);

		  			      $destination = imagecreatetruecolor(100, 100);
                          $miniban = $id."-min-".time().".".$extension;
		  			       imagecopyresampled($destination, $source, 0, 0, 0, 0, 100, 100, $largeur_s, $hauteur_s);
		  			      imagepng($destination , "./photos/".$miniban);


		  			break;
		  			case "jpg":

		  	               $source = "./photos/".$nom_ban ;

		  			      $largeur_s = imagesx($source);
		  			      $hauteur_s = imagesy($source);

		  			      $destination = imagecreatetruecolor(100, 100);
                          $miniban = $id."-min-".time().".".$extension;
		  			      imagecopyresampled($destination, $source, 0, 0, 0, 0, 100, 100, $largeur_s, $hauteur_s);
		  			      imagejpeg($destination , "./photos/".$miniban);

		  			break;

		  			case "jpeg":

		  			       $source = "./photos/".$nom_ban ;

		  			      $largeur_s = imagesx($source);
		  			      $hauteur_s = imagesy($source);

		  			      $destination = imagecreatetruecolor(100, 100);
                          $miniban = $id."-min-".time().".".$extension;
		  			      imagecopyresampled($destination, $source, 0, 0, 0, 0, 100, 100, $largeur_s, $hauteur_s);
		  			      imagejpeg($destination , "./photos/".$miniban);

		  			break;
		  			case "gif":
		  			      $source = "./photos/".$nom_ban ;

		  			      $largeur_s = imagesx($source);
		  			      $hauteur_s = imagesy($source);

		  			      $destination = imagecreatetruecolor(100, 100);
                          $miniban = $id."-min-".time().".".$extension;
		  			      imagecopyresampled($destination, $source, 0, 0, 0, 0, 100, 100, $largeur_s, $hauteur_s);
		  			      imagegif($destination , "./photos/".$miniban);

		  			break;
		  	    }

		  	     $update = $bdd->prepare('UPDATE social_groupes SET groupes_nom = :nom ,groupes_description = :description ,groupes_banniere = :banniere,
		  	                               groupes_banniere_min = :banmin WHERE groupes_id = :groupe');
		  	     $update->execute(array('nom' => $nom,
		  	     	                    'description' => $description,
		  	     	                     'banniere' => $banniere,
		  	     	                     'banmin' => $miniban,
		  	     	                     'groupe' => $groupeid));
		  	     $update->closeCursor();
		  	}
		  	else
		  	{
		  		$update = $bdd->prepare('UPDATE social_groupes SET groupes_nom = :nom ,groupes_description = :description 
		  	                             WHERE groupes_id = :groupe');
		  	     $update->execute(array('nom' => $nom,
		  	     	                    'description' => $description,
		  	     	                     'groupe' => $groupeid
		  	     	                     ));
		  	     $update->closeCursor();
		  	}

		  	
		}
		else
		{
			$update = $bdd->prepare('UPDATE social_groupes SET groupes_nom = :nom ,groupes_description = :description 
		  	                             WHERE groupes_id = :groupe');
		  	     $update->execute(array('nom' => $nom,
		  	     	                    'description' => $description,
		  	     	                     'groupe' => $groupeid
		  	     	                     ));
		  	     $update->closeCursor();

		}


	 
	          
	break;

	case "suppm":

	    $groupeid = (isset($_GET['g']))?$_GET['g']:"";
	    $pseudo = isset($_POST['nouveau'])?$_POST['nouveau']:"";

	       if(empty($groupeid) || empty($pseudo))
	       {
	       	header('Location:mesgroupes.php');
	       }
         $suppm = $bdd->prepare('SELECT membre_id FROM membres WHERE membre_pseudo = LOWER(:pseudo)');
         $suppm->bindParam(':pseudo',$pseudo, PDO::PARAM_INT);
         $suppm->execute();

         if($membre = $suppm->fetch())
         {
         	 $membre = $membre['membre_id'];

         	 $suppmembre = $bdd->prepare('DELETE FROM social_gs_membres 
         	 	                           WHERE groupes_id = :groupe 
         	 	                           AND membre_id = :membre
         	 	                           ');
         	 $suppmembre->bindParam(':groupe',$groupeid,PDO::PARAM_INT);
         	 $suppmembre->bindParam(':membre',$membre,PDO::PARAM_INT);
         	 $suppmembre->execute();
         	 $suppmembre->closeCursor();

         	 $_SESSION['flash']['success'] = "Membre Supprimmer avec succes";
         	 header('Location:voirgroupe.php?g='.$groupeid);
         }



    break;

	default:
         header('Location:mesgroupes.php');
	break;

	
}
