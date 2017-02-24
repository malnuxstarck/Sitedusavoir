<?php
include "../includes/session.php";

$titre = $_SESSION['pseudo'];

include_once('../includes/identifiants.php');
include_once ('../includes/debut.php');
include_once('../includes/menu.php');


if( $id == 0)
{
   header('Location:../index.php');
}

echo '<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li>'.$titre.'</li>
         </ul>
  </div>';

$membre = (int)$_GET['id'];

if($membre != $id)
{
	
header('Location:./voirmonprofil.php?id='.$id);

}

$requete = $bdd->prepare('SELECT membre_pseudo ,membre_localisation, membre_email, DATE_FORMAT(membre_inscrit ,\'le %d - %m - %Y à %H h : %i min : %s secs\') AS  membre_inscrit,membre_siteweb,membre_post,
	                             membre_signature,DATE_FORMAT(membre_derniere_visite,\'le %d - %m - %Y à %H h : %i min : %s secs\') AS membre_derniere_visite,membre_avatar 
	                      FROM membres
	                      WHERE membre_id = :idmembre');
$requete->execute(array('idmembre' => $membre));

$memb = $requete->fetch();


echo ' <div class="page">
                         <div class="membre">


	                            <div class="entetemembre">

		                                <div class="avatar">
		                                     <img src="../images/avatars/'.$memb['membre_avatar'].'" alt="Pas d avatar"/>';

		                                     if($lvl == 3)
		                                        echo'<span class="badge"> Moderateur </span>';
                                              else if($lvl == 4)
                                              	echo '<span class="badge">Admin </span>';

		                                echo '</div>

		                                <div class="membre-ins">
		                                      <h4 class="nommembre">'.$memb['membre_pseudo'].'</h4>
		                                      <p>
		                                          Membre depuis le '.$memb['membre_inscrit'].'</br>
		                                          Email : <a href="mailto:'.$memb['membre_email'].'">'.$memb['membre_email'].'</a>
		                                      </p>
		                                      <span class="membre-edit">
		                                             <a href="./editerprofil.php">Editer </a>
		                                      </span>
		                                </div>
	                            </div>

	                          <div class="infosmembre">
	                                <ul>
	                                    <li> <span class="libele">Site Web </span>       :  <span class="infos-content"><a href="'.htmlspecialchars($memb['membre_siteweb']).'">'.htmlspecialchars($memb['membre_siteweb']).'</a></span>  </li>
	                                    <li> <span class="libele"> Localisation </span>  :  <span class="infos-content"> '.$memb['membre_localisation'].' </span> </li>
	                                    <li> <span class="libele"> Messages </span>      :  <span class="infos-content"> '.$memb['membre_post'].'  </li>
	                                    <li> <span class="libele">Derniere Visite </span>:  <span class="infos-content"> '.$memb['membre_derniere_visite'].' </span> </li>
	                                </ul>
	                          </div>

	                          <div class="signature">
	                               
	                                  '.$memb['membre_signature'].'
	                               
	                          </div>
                        </div>     
            </div>
	' ;

    

include "../includes/footer.php";

