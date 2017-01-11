
<?

$req = $bdd->prepare('SELECT membre_pseudo,membre_avatar,membre_email,membre_rang 
	                  FROM membres 
	                  WHERE membre_id = :id');

      $req->execute(array('id' => $id));
      
      $data = $req->fetch();