<?php

     echo '<p id="fildariane"><i>Vous êtes ici</i> : <a href="./index.php">Forum</a> --> Profil de '.stripslashes(htmlspecialchars($data['membre_pseudo']));
      echo'<h1>Profil de '.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</h1>';
      echo'<img src="../../vue/images/avatars/'.$data['membre_avatar'].'" alt="Ce membre n\'a pas d avatar" />';
      echo'<p><strong>Adresse E-Mail : </strong><a href="mailto:'.stripslashes($data['membre_email']).'">'.stripslashes(htmlspecialchars($data['membre_email'])).'</a><br />';
      echo'Ce membre est inscrit depuis le <strong>'.$data['membre_inscrit'].'</strong> et a posté <strong>'.$data['membre_post'].'</strong> messages<br /><br />';
      