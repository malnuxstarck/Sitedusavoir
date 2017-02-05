<?php

$titre="Gestion | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

echo '<p id="fildariane"><i><a href="../index.php">Accueil</a>--><a href="./index.php">Social</a>-->Gestion des groupes</i></p>';

$action = isset($_GET['action'])?$_GET['action']:"";

if(empty($action))
{
	header('Location:index.php');
}


switch($action)
{
	case "creer":

	echo '<form action=gererok.php?action=creer method="POST" enctype="multipart/form-data">
              <div class="input">
                    <input type="text" name="nom" placeholder="Le nom du groupe"  required/>
              </div>

              <div class="textarea">
                    <textarea name="description" required > La description du groupe </textarea>
              </div>

              <div class="input">
                    <input type="file" name="banniere" placeholder="Le nom du groupe"  required/>
              </div>

              <div class="input">
                    <input type="submit" value="Creer" />
              </div>

              </div>
             
	    </form>';

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
		$groupeademander->bindParam(':membre',$id,PDO::PARAM_INT);
		$groupeademander->bindParam(':groupe',$groupeid,PDO::PARAM_INT);
		$groupeademander->fetch();

		echo '<div class="groupes">

		       <div class="ban_min">
        	            <img src="./photos/'.$groupedemander['groupes_banniere_min'].'" alt="ban_min"/>
        	       </div>
        	       <h3 class="nom"> <a href="./gerer.php?action=admin&g='.$groupe['groupes_id'].'">'.$groupedemander['groupes_nom'].'</a></h3>

        	       <div class="infos_groupes">';

        $nbresmembres = $bdd->prepare('SELECT COUNT(*) AS nbrm 
        	                             FROM groupes_gs_membres 
        	                             WHERE groupes_id = :groupe');
        $nbresmembres->bindParam(':groupe', $groupeademander['groupes_id'],PDO::PARAM_INT);
        $nbresmembres->execute();
        $nbresmembres = $nbresmembres->fetch();
        $nbresmembres = $nbresmembres['nbrm'];

        echo '<p> Il y a actuement '.$nbresmembres.' membres sur groupe </p>
              
              <h2> Administrer </h2>
              <ul>
                  <li><a href="gerer.php?action=add&g='.$groupeademander['groupes_id'].'">Ajouter un membre </a></li>
                  <li><a href="gerer.php?action=suppm&g='.$groupeademander['groupes_id'].'">Supprimer des membres </a></li>
                  <li><ahref="gerer.php?action=del&g='.$groupeademander['groupes_id'].'">Supprimer le groupe </a></li>
                  <li><ahref="gerer.php?action=edit&g='.$groupeademander['groupes_id'].'">Supprimer le groupe </a></li>
              </ul>

        ';




	}  
	else
	{
		echo '<h3> Listes des groupes que vous pouvez administrer </h3>';

		$groupesinfo = $bdd->prepare('SELECT * FROM social_groupes
				                      JOIN social_gs_admin 
				                      ON social_groupes.groupes_id = social_gs_admin.groupes_id
				                      JOIN membres
				                      ON social_gs_admin.membre_id = membres.membre_id
				                      WHERE social_gs_admin.membre_id = :membre');
		$groupesinfo->bindParam(':membre',$id,PDO::PARAM_INT);
        $groupesinfo->execute();

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

	break;

	case "del":
	break;
	case "add":
	break;
	case "edit":
	break;
	case "suppm":
	break;

	
}
