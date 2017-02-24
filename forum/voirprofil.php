<?php
  include '../includes/session.php';
  
  $titre="Profil | SiteduSavoir.com";
  include("../includes/identifiants.php");
  include("../includes/debut.php");
  include("../includes/menu.php");
  //On récupère la valeur de nos variables passées par URL
  $action =isset($_GET['action'])?htmlspecialchars($_GET['action']):'consulter';
  $membre = isset($_GET['m'])?(int) $_GET['m']:'';
?>


<?php
  //On regarde la valeur de la variable $action
  switch($action)
  {
    //Si c'est "consulter"
    case "consulter":
      //On récupère les infos du membre
      $query = $bdd->prepare('SELECT membre_pseudo, membre_avatar,membre_email ,membre_rang,membre_derniere_visite, membre_signature, membre_siteweb, membre_post,DATE_FORMAT(membre_inscrit,\'%d/%m/%Y %h:%i:%s\') 
                              AS membre_inscrit,membre_localisation
                              FROM membres 
                              WHERE membre_id = :membre');

      $query->bindValue(':membre',$membre, PDO::PARAM_INT);
      $query->execute();
      $data = $query->fetch();

      //On affiche les infos sur le membre

      echo '<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li><span style="color:black;">'.htmlspecialchars($data['membre_pseudo']).'</span></li>
         </ul>
      </div>';

     echo ' <div class="page">
                         <div class="membre">
                                  <div class="entetemembre">

                                        <div class="avatar">
                                             <img src="../images/avatars/'.$data['membre_avatar'].'" alt="Pas d avatar"/>';

                                             if($data['membre_rang']== 3)
                                                echo'<span class="badge"> Moderateur </span>';
                                                  else if($data['membre_rang'] == 4)
                                                    echo '<span class="badge">Admin </span>';

                                        echo '</div>

                                        <div class="membre-ins">
                                            <h4 class="nommembre">'.$data['membre_pseudo'].'</h4>
                                            <p>
                                                Membre depuis le '.$data['membre_inscrit'].'<br/>
                                                Email : <a href="mailto:'.$data['membre_email'].'">'.htmlspecialchars($data['membre_email']).'</a>
                                            </p>
                                            
                                       </div>
                                 </div>

                              <div class="infosmembre">
                                  <ul>
                                      <li> <span class="libele">Site Web </span>       :  <span class="infos-content"><a href="'.htmlspecialchars($data['membre_siteweb']).'">'.htmlspecialchars($data['membre_siteweb']).'</a></span>  </li>
                                      <li> <span class="libele"> Localisation </span>  :  <span class="infos-content"> '.$data['membre_localisation'].' </span> </li>
                                      <li> <span class="libele"> Messages </span>      :  <span class="infos-content"> '.$data['membre_post'].'</span> </li>
                                      <li> <span class="libele">Derniere Visite </span>:  <span class="infos-content"> '.$data['membre_derniere_visite'].' </span> </li>
                                  </ul>
                            </div>

                            <div class="signature">
                                 
                                    '.$data['membre_signature'].'
                                 
                            </div>
                  </div>';    
    break;

    default: //Si jamais c'est aucun de ceux-là c'est qu'il y a eu un problème :o
         echo'<p>Cette action est impossible</p>';
    break;
  }//Fin du switch
  echo '</div>';
  include "../includes/footer.php";
?>


</body>
</html>
