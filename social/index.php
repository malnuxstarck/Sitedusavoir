<?php

$titre="Social | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

if(!$id)
{
	$_SESSION['flash']['danger'] = " Vous devez etre connecté pour voir cette partie";
	header('Location:../connexion.php');
}

$membre = $bdd->prepare('SELECT membre_pseudo , membre_avatar_mini
	                     FROM membres WHERE membre_id = :id');
$membre->bindParam(':id',$id,PDO::PARAM_INT);
$membre->execute();

$membre = $membre->fetch();

echo '<aside class="aside-s">
           <p>'.$membre['membre_pseudo'].'
           </p>
           <section>
               <h3> Actualités </h3>
           <ul>
              <li> 
                  <a href="./index.php">News</a>
              <li>
              <li>
                   <a href="../membre/amis.php">Amis </a>
              </li>
              <li>
                  <ahref="./notifications.php"> Notifications </a>
              </li>
           </ul>
           </section>

           <section class="groupes">
               <h3> Groupes </h3>
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
   echo'
    </ul>
    </section>

</aside>
<div class="fil">
         <section class="top">
                 <form action =filok.php" method="POST" enctype="multipart/form-data">

                 <span class="avatar"><img src="../images/avatar_min/'.$membre['membre_avatar_mini'].'" alt="pas davatar"/></span>
                 <div class="textarea">
                    <textarea>Votre statut </textarea>
                 </div>
                 <div class="fichier">
                       <input type="file" name="photo"/>
                 </div>
                 <div>
                      <input type="submit" value="Statuer"/>
                 </div>
                    
         </section>


         <section class="actu">

         </section>
</div>';	                     