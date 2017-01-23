<?php

$req = $bdd->prepare('SELECT * FROM membres WHERE membre_id = :id AND reset = :reset AND reset_at IS NOT NULL AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');

    $req->execute(array('id' => $_GET['id'], 'reset' => $_GET['token']));

    $user = $req->fetch() ;