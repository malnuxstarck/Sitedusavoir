<?php

$titre="Gestion | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

echo '<div class="fildariane">

                <ul>
                    <li>
                        <a href="../index.php">Accueil </a>
                    </li> <img src="../images/icones/fleche.png" class="fleche"/>

                    <li>
                        <a href="./index.php">Social </a>
                     </li>

                     <img src="../images/icones/fleche.png" class="fleche"/>
                     <li> <span style="color:black;">Gerer mes groupes</span></li>

                 </ul>      

         </div>

         <div class="page">';

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

	//  Creer un nouveau groupe formulaire
             echo '<h2 class="titre"> Creer un groupe </h2>';
	echo '<div class="formulaire">

	        <form action=gererok.php?action=creer method="POST" enctype="multipart/form-data">
              <div class="input">
                    <label for="nom"></label>
                    <input type="text" name="nom" placeholder="Le nom du groupe"  required/>
              </div>

              <div class="textarea">
                    <textarea name="description" required > La description du groupe </textarea>
              </div>

              <div class="input">
                    <label for="banniere"></label>
                    <input type="file" name="banniere" />
              </div>

              <div class="submit">
                    <input type="submit" value="Creer" />
              </div>
             
	    </form>
	    </div>';

	break;

	case "admin":

	$groupeid = (isset($_GET['g']))?$_GET['g']:"";

	if(!empty($groupeid))
	{
		//Si on envoie l'id du groupe et qu'on est l'admin

		$groupedemander = $bdd->prepare('SELECT * FROM social_groupes
				                   JOIN social_gs_admin 
				                   ON social_groupes.groupes_id = social_gs_admin.groupes_id
				                   JOIN membres
				                   ON social_gs_admin.membre_id = membres.membre_id
				                   WHERE social_gs_admin.membre_id = :membre 
				                   AND social_gs_admin.groupes_id = :groupe');
		$groupedemander->bindParam(':membre',$id,PDO::PARAM_INT);
		$groupedemander->bindParam(':groupe',$groupeid,PDO::PARAM_INT);
		$groupedemander->execute();
		$grouped = $groupedemander->fetch();

		echo '<div class="groupes">

		       <div class="ban_min">
        	            <img src="./photos/'.$grouped['groupes_banniere_min'].'" alt="ban_min"/>
        	       </div>
        	       <h3 class="nom"> <a href="./voirgroupe.php?g='.$grouped['groupes_id'].'">'.$grouped['groupes_nom'].'</a></h3>

        	       <div class="infos_groupes">';

        $nbresmembres = $bdd->prepare('SELECT COUNT(*) AS nbrm 
        	                             FROM social_gs_membres 
        	                             WHERE groupes_id = :groupe');
        $nbresmembres->bindParam(':groupe', $grouped['groupes_id'],PDO::PARAM_INT);
        $nbresmembres->execute();
        $nbresmembres = $nbresmembres->fetch();
        $nbresmembres = $nbresmembres['nbrm'];

        echo '<p> Il y a actuement '.$nbresmembres.' membres sur groupe </p>
              
              <h2> Administrer </h2>
              <ul class="adminlisteaction">
                  <li><a href="gerer.php?action=add&g='.$grouped['groupes_id'].'">Ajouter un membre </a></li>
                  <li><a href="gerer.php?action=suppm&g='.$grouped['groupes_id'].'">Supprimer un membres </a></li>
                  <li><a href="gerer.php?action=del&g='.$grouped['groupes_id'].'">Supprimer le groupe </a></li>
                  <li><a href="gerer.php?action=edit&g='.$grouped['groupes_id'].'">Editer les parametres du groupe </a></li>
              </ul>

        ';




	}  
	else
	{
		echo '<h3> Administrer vos groupes </h3>';

        //Tous les groupes pour les quelles on est administratuer

		$groupesinfo = $bdd->prepare('SELECT * FROM social_groupes
				                      JOIN social_gs_admin 
				                      ON social_groupes.groupes_id = social_gs_admin.groupes_id
				                      JOIN membres
				                      ON social_gs_admin.membre_id = membres.membre_id
				                      WHERE social_gs_admin.membre_id = :membre');
		$groupesinfo->bindParam(':membre',$id,PDO::PARAM_INT);
        $groupesinfo->execute();

        if($groupesinfo->rowCount() > 0)
        {

	        while($groupe = $groupesinfo->fetch())
	        {
	        	echo '<div class="tutos">
	        	       <div class="ban_min">
	        	            <img src="./photos/'.$groupe['groupes_banniere_min'].'" alt="ban_min"/>
	        	       </div>
	        	       <h3 class="nom"> <a href="./gerer.php?action=admin&g='.$groupe['groupes_id'].'">'.$groupe['groupes_nom'].'</a></h3>
	        	</div>';
	        }
	    }
	    else
	    {
	    	echo '<p> Vous n\'avez creer aucun groupe <a href="gerer.php?action=creer"> Voulez vous en creer </a> </p>';

	    }


		
	}

	break;

	case "del":
		$groupeid = (isset($_GET['g']))?$_GET['g']:"";

		if(empty($groupeid))
		{
			header('Location:mesgroupes.php');
		}
		else
		{
            $sur = isset($_GET['sur'])?$_GET['sur']:"";

             if(empty($sur))
			 {
               echo '<p> Vous etes sur le point de supprimer un groupe ? etes vous sur <a href="gererok.php?action=del&g='.$groupeid.'&sur=1">OUI</a>-
			   <a href="voirgroupe.php?g='.$groupeid.'">Non</a></p>';
			 } 

		}
	break;

	case "add":

	        $groupeid =(isset($_GET['g']))?$_GET['g']:"";

	          if(empty($groupeid))
	          {
	          	header('Location:mesgroupes.php');
	          }
	          
	        echo '<h2 class="titre"> Inscrivez le pseudo du membre </h2>
	           <div class="formulaire">
	             <form action="gererok.php?action=add&g='.$groupeid.'" method="POST">

	                  <div class="input">
                         <label for="nouveau"></label> 
                         <input type="text" name="nouveau" placeholder="Le pseudo du membre"  required/>
                      </div>

                      <div class="submit">
                          <input type="submit" value="Envoyer"  required/>
                      </div>

	             </form>
	           </div>';

	break;

	case "edit":
	          $groupeid =(isset($_GET['g']))?$_GET['g']:"";

	          if(empty($groupeid))
	          {
	          	header('Location:mesgroupes.php');
	          }

	        $groupeinfo = $bdd->prepare('SELECT * FROM social_groupes
	        	                         JOIN social_gs_admin
	        	                         ON social_groupes.groupes_id = social_gs_admin.groupes_id
	        	                         JOIN membres ON membres.membre_id = social_gs_admin.membre_id
	        	                         WHERE social_groupes.groupes_id = :groupe 
	        	                         AND social_gs_admin.membre_id = :membre');
	        $groupeinfo->bindParam(':membre',$id,PDO::PARAM_INT);
	        $groupeinfo->bindParam(':groupe',$groupeid,PDO::PARAM_INT);
	        $groupeinfo->execute();

	        $groupe = $groupeinfo->fetch();

	        echo ' <h2 class="titre"> Edition de groupe </h2>
                    <div class="formulaire">

	               <form method="POST action="gererok.php?action=edit&g='.$groupeid.'" enctype="multipart/form-data">
	                    <div class="input">
	                         <label for="nom"></label>
	                         <input type="text" name="nom" value="'.$groupe['groupes_nom'].'" required/>
	                    </div>

                         <div class="textarea">
                            <textarea name="description" required >'.$groupe['groupes_description'].'</textarea>
                         </div>

			              <div class="input">
			                    <label for="banniere"></label>
			                    <input type="file" name="banniere" />
			              </div>

			              <div class="submit">
			                    <input type="submit" value="Modifier" />
			              </div>


	              </form>
	              </div>';
	break;

	case "suppm":

	     $groupeid =(isset($_GET['g']))?$_GET['g']:"";

	     if(empty($groupeid))
	     {
	          	header('Location:mesgroupes.php');
	     }

		echo '<h2 class="titre"> Inscrivez le pseudo du membre </h2>
                    <div class="formulaire">

		             <form action="gererok.php?action=suppm&g='.$groupeid.'" method="POST">
		                  <div class="input">
	                          <label for="nouveau"></label><input type="text" name="nouveau" placeholder="Lepseudo du membre"  required/>
	                      </div>

	                      <div class="submit">
	                          <input type="submit" value="Envoyer"/>
	                      </div>

		             </form>
		           </div>';

	       
	break;

	default:
         header('Location:mesgroupes.php');
	break;

	
}
echo '</div>';
include "../includes/footer.php";
