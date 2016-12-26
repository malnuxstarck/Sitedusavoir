<?php
  include '../includes/session.php';
  
  $titre="Profil";
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
    $query = $bdd->prepare('SELECT membre_pseudo, membre_avatar,
    membre_email , membre_signature, membre_siteweb, membre_post,
    DATE_FORMAT(membre_inscrit,\'%d/%m/%Y %h:%i:%s\') AS membre_inscrit,membre_localisation
    FROM membres WHERE membre_id = :membre');
    $query->bindValue(':membre',$membre, PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch();
    //On affiche les infos sur le membre
    echo '<p id="fildariane"><i>Vous êtes ici</i> : <a href="./index.php">Forum</a> --> Profil de '.stripslashes(htmlspecialchars($data['membre_pseudo']));
    echo'<h1>Profil de '.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</h1>';
    echo'<img src="../images/avatars/'.$data['membre_avatar'].'" alt="Ce membre n\'a pas d avatar" />';
    echo'<p><strong>Adresse E-Mail : </strong><a href="mailto:'.stripslashes($data['membre_email']).'">'.stripslashes(htmlspecialchars($data['membre_email'])).'</a><br />';
    echo'Ce membre est inscrit depuis le <strong>'.$data['membre_inscrit'].'</strong> et a posté <strong>'.$data['membre_post'].'</strong> messages<br /><br />';
    $query->closeCursor();
    break;
    default: //Si jamais c'est aucun de ceux-là c'est qu'il y a eu un problème :o
    echo'<p>Cette action est impossible</p>';
    break;
  }//Fin du switch
?>
</div>
</body>
</html>
