<table>
<tr>
<th class="vt_auteur"><strong>Auteurs</strong></th>
<th class="vt_mess"><strong>Messages</strong></th>
</tr>

<?php
  while ($data = $query->fetch())
  {

  	//On commence à afficher le pseudo du créateur du message :
  	//On vérifie les droits du membre

  	
  	echo'<tr><td><strong>
  	     <a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">
  	     '.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a></strong></td>';

  	/* Si on est l'auteur du message, on affiche des liens pour
  	Modérer celui-ci.
  	Les modérateurs pourront aussi le faire, il faudra donc revenir sur
  	ce code un peu plus tard ! */

  	if ($id == $data['post_createur'])
  	{
    	echo'<td id=p_'.$data['post_id'].'>Posté à '.$data['post_time'].'
        	 <a href="./poster.php?p='.$data['post_id'].'&amp;action=delete">
        	 <img src="../images/supprimer.gif" alt="Supprimer"
        	 title="Supprimer ce message" /></a>
        	 <a href="./poster.php?p='.$data['post_id'].'&amp;action=edit">
        	 <img src="../images/editer.gif" alt="Editer"
        	 title="Editer ce message" /></a></td></tr>';
  	}
  	else
  	{
  	   echo'<td>Posté à '.$data['post_time'].'</td></tr>';
  	}

  	echo'<tr><td>
  	     <img src="../../vue/images/avatars/'.$data['membre_avatar'].'" alt="" />
  	     <br />Membre inscrit le '.$data['membre_inscrit'].'
  	     <br />Messages : '.$data['membre_post'].'<br />
  	     Localisation : '.stripslashes(htmlspecialchars($data['membre_localisation'])).'</td>';

  	echo'<td>'.code(nl2br(stripslashes(htmlspecialchars($data['post_texte'])))).'<br /><hr/>'.code(nl2br(stripslashes(htmlspecialchars($data['membre_signature'])))).'</td></tr>';
  }
  //Fin de la boucle ! \o/
  $query->CloseCursor();
?>
</table>

<?php

  echo '<p>Page : ';
  for ($i = 1 ; $i <= $nombreDePages ; $i++)
  {
    if ($i == $page) //On affiche pas la page actuelle en lien
    {
      echo $i;
    }
    else
    {
      echo '<a href="voirtopic.php?
            t='.$topic.'&amp;page='.$i.'">
            '.$i.'</a> ';
    }
  }
  echo'</p>';
  //On ajoute 1 au nombre de visites de ce topic
  $query=$bdd->prepare('UPDATE forum_topic 
                        SET topic_vu = topic_vu + 1 
                        WHERE topic_id = :topic');

  $query->bindValue(':topic',$topic,PDO::PARAM_INT);
  $query->execute();
  $query->CloseCursor();

   //Fin du if qui vérifiait si le topic contenait au moins un message

