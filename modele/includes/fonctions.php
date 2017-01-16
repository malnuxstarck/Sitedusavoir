<?php

//Est ce que le pseudo

function pseudoLibre($pseudo,$bdd)
{
  $query = $bdd->prepare('SELECT COUNT(*) AS nbr FROM membres WHERE membre_pseudo =:pseudo');
  $query->bindValue(':pseudo',$pseudo, PDO::PARAM_STR);
  $query->execute();
  $pseudo_free=($query->fetchColumn() == 0)?1:0;
  $query->CloseCursor();

  return $pseudo_free;
}

function mailLibre($email,$bdd)
{
  $query = $bdd->prepare('SELECT COUNT(*) AS nbr FROM membres WHERE membre_email =:mail');
  $query->bindValue(':mail',$email, PDO::PARAM_STR);

  $query->execute();
  $mail_free =($query->fetchColumn()==0)?1:0;

  $query->closeCursor();

  return $mail_free;
}

function getInfosUtilisateurs($pseudo , $bdd)
{

        $query = $bdd->prepare('SELECT membre_mdp, membre_id,membre_rang, membre_pseudo,membre_inscrit
                                FROM membres
                                WHERE membre_pseudo = :pseudo
                                AND membre_inscrit IS NOT NULL');

        $query->bindValue(':pseudo',$pseudo,PDO::PARAM_STR);

        $query->execute();

        $data = $query->fetch();

        return $data;

}


function setDerniereVisite($membre_id, $bdd)
{
  $requete = $bdd->prepare('UPDATE membres
                                      SET membre_derniere_visite = NOW()
                                      WHERE membre_id = :id');

  $requete->bindValue(':id',$membre_id,PDO::PARAM_INT);
  $requete->execute();
}

function cookietoken($cookie,$membre_id,$bdd)
{
   $req = $bdd->prepare('UPDATE membres
                                    SET cookie = :cookie
                                    WHERE membre_id = :id');
               $req->execute(array('cookie'=> $cookie, 'id' => $membre_id));
}
