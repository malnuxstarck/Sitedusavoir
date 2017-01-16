  <?php
  
  $query=$bdd->prepare('DELETE FROM forum_whosonline WHERE online_id = :id');
  $query->bindValue(':id',$id,PDO::PARAM_INT);
  $query->execute();
  $query->CloseCursor();

  $req = $bdd->prepare('UPDATE membres SET cookie = NULL WHERE membre_id=:id');
  $req->execute(array('id'=>$id));
  $req->CloseCursor();