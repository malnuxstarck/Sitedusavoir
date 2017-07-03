<?php

include "../includes/session.php";
$titre = $_SESSION['pseudo'];

include_once '../includes/identifiants.php' ;
include_once '../includes/debut.php' ;
include_once '../includes/menu.php' ;


if( $id == 0)
{
   header('Location:../index.php');
}

echo '<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li>'.$titre.'</li>
         </ul>
  </div>';


$idGet = (int)$_GET['id'];

if($idGet != $id)
{
	header('Location:./voirmonprofil.php?id='.$id);
}


$managerMembre = new ManagerMembre($bdd);
$donnees = $managerMembre->infosMembre($id);

$membre = new Membre($donnees);

echo ' <div class="page">
                         <div class="membre">


	                            <div class="entetemembre">

		                                <div class="avatar">
		                                     <img src="../images/avatars/'.$membre->avatar().'" alt="Pas d avatar"/>';

		                                     if($lvl == 3)
		                                        echo'<span class="badge"> Moderateur </span>';
                                              else if($lvl == 4)
                                              	echo '<span class="badge">Admin </span>';

		                                echo '</div>

		                                <div class="membre-ins">
		                                      <h4 class="nommembre">'.$membre->pseudo().'</h4>
		                                      <p>
		                                          Membre depuis le '.$membre->inscrit().'</br>
		                                          Email : <a href="mailto:'.$membre->email().'">'.$membre->email().'</a>
		                                      </p>
		                                      <span class="membre-edit">
		                                             <a href="./editerprofil.php">Editer </a>
		                                      </span>
		                                </div>
	                            </div>

	                          <div class="infosmembre">
	                                <ul>
	                                    <li> <span class="libele">Site Web </span>       :  <span class="infos-content"><a href="'.htmlspecialchars($membre->siteweb()).'">'.htmlspecialchars($membre->siteweb()).'</a></span>  </li>
	                                    <li> <span class="libele"> Localisation </span>  :  <span class="infos-content"> '.$membre->localisation().' </span> </li>
	                                    <li> <span class="libele"> Messages </span>      :  <span class="infos-content"> '.$membre->posts().'  </li>
	                                    <li> <span class="libele">Derniere Visite </span>:  <span class="infos-content"> '.$membre->visite().'</span> </li>
	                                </ul>
	                          </div>

	                          <div class="signature">
	                               
	                                  '.$membre->signature().'
	                               
	                          </div>
                        </div>     
            </div>
	' ;


include "../includes/footer.php";

