<?php

    $req = $bdd->prepare('SELECT * FROM membres WHERE membre_email = :email AND membre_inscrit IS NOT NULL');

     $req->execute(array('email' => $_POST['email']));

    $user = $req->fetch();

  