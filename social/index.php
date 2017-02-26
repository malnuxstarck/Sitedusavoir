<?php

$titre="Social | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

 echo '<div class="fildariane">

                <ul>
                    <li>
                        <a href="../index.php">Accueil </a>
                    </li> <img src="../images/icones/fleche.png" class="fleche"/>

                    <li>
                        <a href="./index.php">Social </a>
                     </li> 

                 </ul>      

         </div>

         <div class="page">';


if(!$id)
{
	$_SESSION['flash']['danger'] = " Vous devez etre connecté pour voir cette partie";
	header('Location:../connexion.php');
}

$membre = $bdd->prepare('SELECT membre_pseudo , membre_id ,membre_avatar
	                     FROM membres WHERE membre_id = :id');
$membre->bindParam(':id',$id,PDO::PARAM_INT);
$membre->execute();

$membre = $membre->fetch();

// On listes les groupes du membres

echo '<div class="gestionac">

           <p class="pseudoso">
                 <a href="../forum/voirprofil.php?action=consulter&m='.$membre['membre_id'].'">'.$membre['membre_pseudo'].'</a>
           </p>

         <section class="actual">

               <h3> Actualités </h3>
           <ul>
              <li> 
                  <a href="./index.php">News</a>
              </li>

              <li>
                   <a href="../membre/amis.php">Amis </a>
              </li>

              <li>
                  <a href="./notifications.php"> Notifications </a>
              </li>
           </ul>
        </section>

           <section class="lgroupes">
               <h3><a href="mesgroupes.php"> Groupes</a> </h3>
               <ul>';

$listgroupes = $bdd->prepare('SELECT groupes_nom , social_groupes.groupes_id 
	                          FROM social_groupes
	                          JOIN social_gs_membres 
	                          ON social_groupes.groupes_id  = social_gs_membres.groupes_id
	                          WHERE membre_id = :id');
$listgroupes->bindParam(':id',$id ,PDO::PARAM_INT);
$listgroupes->execute();

if($listgroupes->rowCount() > 0)
{
	$i = 0 ;

   while($groupe = $listgroupes->fetch())
   {
	   	 $i++;
	   	 echo '<li><a href="voirgroupe.php?g='.$groupe['groupes_id'].'">'.$groupe['groupes_nom'].'</a></li>';
	   	 if($i > 10)
	   	 {
	   	 	echo '<li><a href="mesgroupes.php">Tous mes groupes </a></li>';
	   	 	$groupe = NULL ;
	   	 }
   }
}
else
   {
    echo '<li>Aucun groupes </li>';
   }
   echo'
    </ul>
    </section>

</div>

<div class="fil">
         <section class="top">
                 <form action="statutok.php?action=new" method="POST" enctype="multipart/form-data">
                 <div class="avatarEtstatut">
                     <span class="avatarsocial">
                          <img src="../images/avatars/'.$membre['membre_avatar'].'" alt="pas davatar"/>
                    </span>

                     <div class="textarea textarea-soc">
                        <textarea name="statut">Votre statut </textarea>
                     </div>
                </div>
                   
                   <div class="fichierEtsubmit">

                     <div class="fichier">
                           <input type="file" name="photo"/>
                     </div>
                     <div class="submit-statut">
                          <input type="submit" value="Statuer"/>
                     </div>
                  </div>   
             </form>    
                    
         </section>


         <section class="actu">
         ';
         // Les statusts dans social_statut, qui peuvent etre des pulications aussi 

         $actu = $bdd->prepare('SELECT statut_id,statut_contenu, statut_date,statut_photo,social_statut.membre_id,membre_pseudo ,membre_avatar
                                FROM social_statut 
                                JOIN membres
                                ON membres.membre_id = social_statut.membre_id 
                                WHERE social_statut.membre_id = :id
                                UNION 
                                SELECT statut_id,statut_contenu, statut_date,statut_photo,social_statut.membre_id,membre_pseudo ,membre_avatar
                                FROM social_statut 
                                JOIN membres
                                ON membres.membre_id = social_statut.membre_id 
                                WHERE social_statut.membre_id IN (SELECT ami_from 
                                                                  FROM amis 
                                                                  WHERE ami_to = :id) 
                                UNION 
                                SELECT statut_id,statut_contenu , statut_date,statut_photo,social_statut.membre_id,membre_pseudo ,membre_avatar
                                FROM social_statut 
                                JOIN membres
                                ON membres.membre_id = social_statut.membre_id 
                                WHERE social_statut.membre_id IN (SELECT ami_to 
                                                                  FROM amis 
                                                                  WHERE ami_from = :id) ORDER BY statut_date
                                
                                ');

         $actu->bindParam(':id',$id,PDO::PARAM_INT);
         $actu->execute();

         while($statut = $actu->fetch())
         {

          $statutid = $statut['statut_id'];

          echo '<div class="satut">
                       <div class="membre">
                            <span class="avatar_mini">'.$statut['membre_avatar'].'</span>
                            </span class="pseudo">'.$statut['membre_pseudo'].'</span>
                       </div>';

                       if($statut['statut_photo'])
                       {
                          echo '<div class="photo">
                                       '.$statut['statut_photo'].'
                                </div>';
                       }

                echo '<div class="content">
                       '.$statut['statut_contenu'].'
                </div>
                        <ul>
                         <li> <a href="./editstatut.php?action=comment&s='.$statut['statut_id'].'">Commenter</a></li>';
                         if($id == $statut['membre_id'])
                         echo '<li> <a href="./editstatut.php?action=edit&s='.$statut['statut_id'].'">Editer</a></li>';
                      echo '</ul>
                </div>
                <div class="commentaire">';
               $com = $bdd->prepare('SELECT commentaires_id,commentaires_text,membres.membre_id, membre_pseudo,membre_avatar 
                                     FROM social_st_comment
                                     JOIN membres
                                     ON membres.membre_id = social_st_comment.membre_id
                                     WHERE statut_id = :statutid ORDER BY statut_id');
               $com->bindParam(':statutid',$statutid,PDO::PARAM_INT);
               $com->execute();

               echo '<ul>';
                           while($commentaire = $com->fetch())
                           {
                            echo '<li>
                                      <div class="avatar_mini">'.$commentaire['membre_avatar'].'</div>
                                      <h3>'.$commentaire['membre_pseudo'].'</h3>
                                    
                                      <p>'.htmlspecialchars($commentaire['commentaires_text']).'</p>
                                      <div class="editco">
                                            <a href="./editstatut.php?action=editco&s='.$statutid.'&c='.$commentaire['commentaires_id'].'">Modifier</a>
                                      </div>
                            </li>';
                           }
                echo '</div>';

         }
         echo '</section>

         <section class="gerer">
                  <ul>
                      <li><a href="gerer.php?action=creer">Creer un groupe </a></li>
                      <li><a href="gerer.php?action=admin">Gerer Vos groupes</a>
                  </ul>
         </secton>
</div>';	

echo'</div>';

include "../includes/footer.php";                     