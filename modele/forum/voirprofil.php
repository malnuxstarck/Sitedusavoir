<?php

      $query = $bdd->prepare('SELECT membre_pseudo, membre_avatar,membre_email , membre_signature, membre_siteweb, membre_post,DATE_FORMAT(membre_inscrit,\'%d/%m/%Y %h:%i:%s\') 
                              AS membre_inscrit,membre_localisation
                              FROM membres 
                              WHERE membre_id = :membre');

      $query->bindValue(':membre',$membre, PDO::PARAM_INT);
      $query->execute();
      $data = $query->fetch();