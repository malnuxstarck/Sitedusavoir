<?php
  include '../includes/session.php';
  
  $titre="Profil | SiteduSavoir.com";
  include("../includes/identifiants.php");
  include("../includes/debut.php");
  include("../includes/menu.php");


  $idMembre = isset($_GET['m'])?(int)$_GET['m']:1;
  $managerMembre = new ManagerMembre($bdd);

  $dateIns = ' IS NOT NULL OR (id = :info AND inscrit IS NULL)';
  
  $donnees = $managerMembre->infosMembre($idMembre , $dateIns);

  
  if(empty($donnees))
  {
      $_SESSION['flash']['success'] = "Le membre demander n'existe pas ";
      header('Location:voirprofil.php?m=1');
  }

  $membre = new Membre($donnees);

  //On affiche les infos sur le membre

  echo '<div class="fildariane">
           <ul>
              <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><span style="color:black;">'.htmlspecialchars($membre->pseudo()).'</span></li>
           </ul>
      </div>';

     echo ' <div class="page">

                         <div class="membre">
                                  <div class="entetemembre">

                                        <div class="avatar">
                                             <img src="../images/avatars/'.$membre->avatar().'" alt="Pas d avatar"/>';

                                             if($membre->rang()== 3)
                                                echo'<span class="badge"> Moderateur </span>';
                                                  else if($membre->rang()== 4)
                                                    echo '<span class="badge">Admin </span>';

                                        echo '</div>

                                        <div class="membre-ins">
                                            <h4 class="nommembre">'.$membre->pseudo().'</h4>
                                            <p>
                                                Membre depuis le '.$membre->inscrit().'<br/>
                                                Email : <a href="mailto:'.$membre->email().'">'.htmlspecialchars($membre->email()).'</a>
                                            </p>
                                            
                                       </div>
                                 </div>

                              <div class="infosmembre">
                                  <ul>
                                      <li> <span class="libele">Site Web </span>       :  <span class="infos-content"><a href="'.htmlspecialchars($membre->siteweb()).'">'.htmlspecialchars($membre->siteweb()).'</a></span>  </li>
                                      <li> <span class="libele"> Localisation </span>  :  <span class="infos-content"> '.$membre->localisation().' </span> </li>
                                      <li> <span class="libele"> Messages </span>      :  <span class="infos-content"> '.$membre->posts().'</span> </li>
                                      <li> <span class="libele">Derniere Visite </span>:  <span class="infos-content"> '.$membre->visite().'</span> </li>
                                  </ul>
                            </div>

                            <div class="signature">
                                 
                                    '.$membre->signature().'
                                 
                            </div>
                  </div>';    
  echo '</div>';
  include "../includes/footer.php";
?>


</body>
</html>
