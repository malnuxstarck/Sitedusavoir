<?php
$query = $bdd->prepare('INSERT INTO membres (membre_pseudo,membre_mdp, membre_email, membre_avatar, membre_inscrit,token) 
                            VALUES (:pseudo, :pass, :email , :nomavatar, NULL, :token)');

    $query->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
    $query->bindValue(':pass', $pass, PDO::PARAM_INT);
    $query->bindValue(':email', $email, PDO::PARAM_STR);
    $query->bindValue(':nomavatar', $nomavatar, PDO::PARAM_STR);
    $query->bindValue(':token',$token,PDO::PARAM_STR) ;
				
    $query->execute();
				
    $id = $bdd->lastInsertId(); ;
				