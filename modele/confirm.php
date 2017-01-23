<?php


   $req = $bdd->prepare('UPDATE membres SET token = NULL, membre_inscrit = NOW() WHERE membre_id = :id ');

    $req->execute(array('id' => $id));
    $req->closeCursor();



?>