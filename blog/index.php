<?php

session_start();

$balises=true;
$titre = 'Blog du Site du Savoir';
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");


?>
<p id="fildariane"><i> Vous etes ici </i> : <a href="../index.php">Accueil </a> --> <a href="./index.php">Blog </a>

<?php


if(verif_auth(MODO))
{
	echo '<p><a href="billet.php?action=creer">Voulez vous cree un billet</a></p>';
}

$requete = $bdd->query('SELECT datebillet,billets.billet_id,billet_titre,billet_contenu FROM billets ORDER BY billet_id DESC');

if($requete->rowcount() > 0)
{
   while ($billet = $requete->fetch())
   {

   	$article = $billet['billet_id'];

     echo '<div class="billet"> 
                <ul>';

                 if(verif_auth(INSCRIT))
                  {
                    echo'
                  <li> 
                     <a href="billet.php?action=comment&billet='.$billet['billet_id'].'"> Commenter</a>
                  <li>';
                  }

                  if(verif_auth(MODO))
                  {
                    echo'
                  <li> 
                     <a href="billet.php?action=edit&billet='.$billet['billet_id'].'"> Editer le billet</a>
                  <li>';
                  }
                   echo'
                  <li> 
                     <a href="billet.php?action=voir&billet='.$billet['billet_id'].'"> Voir l\'article complet</a>
                  <li>
                 </ul>
                 
                 <p id="contenu"> <span>'.$billet['billet_titre'].'</p><p id="contenu">'.$billet['billet_contenu'].'</p>
                 
                  ARTICLES PAR :';

                 $auteur = $bdd->prepare('SELECT membre_pseudo,membres.membre_id AS membre_id FROM membres INNER JOIN auteurs ON auteurs.membre_id = membres.membre_id
                 	WHERE auteurs.billet_id = :billet');
                 $auteur->bindValue(':billet',$article,PDO::PARAM_STR);
                 $auteur->execute();

                 $nbre = $auteur->rowCount();
                 
                 $i=0;

                 while($auteurs = $auteur->fetch())
                 {
                        
                     	echo '<a href="../forum/voirprofil.php?action=consulter&m='.$auteurs['membre_id'].'"><span id="auteur">'.' '.$auteurs['membre_pseudo'].'</span></a>';

                      $i = $i+1;
                      
                      if($i < $nbre AND $nbre > 0)

                           echo '  et';
                     
                 }
                 
            	echo '</div>';
   }

}

else
{
	echo '<p> Aucun billet trouver </p>';



	if(verif_auth(MODO))
	{
     echo '<a href="billet.php?action=creer"> Creer Le premier billet </a>';
	}
}
?>

</div>
</body>
</html>


