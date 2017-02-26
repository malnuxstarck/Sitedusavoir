<?php

$titre="Edit | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

   $action = (isset($_GET['action']))?$_GET['action']:"";

    $statut = (isset($_GET['s']))?$_GET['s']:"";

    if(empty($statut) || empty($action))
    {
      header("Location:index.php");  
    }

    switch($action)
    {
      case "edit":

          echo '<div class="fildariane">

                <ul>
                    <li>
                        <a href="../index.php">Accueil </a>
                    </li> <img src="../images/icones/fleche.png" class="fleche"/>

                    <li>
                        <a href="./index.php">Social </a>
                     </li>

                     <img src="../images/icones/fleche.png" class="fleche"/>
                     <li> <span style="color:black;">Editer statut </span></li>

                 </ul>      

         </div>

         <div class="page">

             <h3 class="titre"> Edition de votre Statut </h3>';


           $selectionstatut = $bdd->prepare('SELECT * FROM social_statut 
                                            WHERE membre_id = :membre AND statut_id = :statut ');
           $selectionstatut->bindParam(':membre',$id, PDO::PARAM_INT);
          $selectionstatut->bindParam(':statut',$statut, PDO::PARAM_INT);
          $selectionstatut->execute();

          $statutinfos = $selectionstatut->fetch();

          echo '<div class="formulaire">

                     <form action="statutok.php?action=edit&s='.$statutinfos['statut_id'].'" method="POST" enctype="multipart/form-data">

                     <div class="textarea">
                          <textarea name="statut">'.htmlspecialchars($statutinfos['statut_contenu']).' </textarea>
                     </div>

                     <div class="input">';

                            if($statutinfos['statut_photo'])
                                echo '<img src=".photos/'.$statutinfos['statut_photo'].'" alt=""/>';

                           echo '<label></label>
                                 <input type="file" name="photo"/>
                      </div>
                      <div class="submit">
                            <input type="submit" value="Editer"/>
                       </div>

          </form>
          </div>';
          break;
       case "comment":

       echo '<div class="fildariane">

                <ul>
                    <li>
                        <a href="../index.php">Accueil </a>
                    </li> <img src="../images/icones/fleche.png" class="fleche"/>

                    <li>
                        <a href="./index.php">Social </a>
                     </li>

                     <img src="../images/icones/fleche.png" class="fleche"/>
                     <li> <span style="color:black;">Commenter statut </span></li>

                 </ul>      

         </div>

         <div class="page">

       <h3 class="titre"> Commenter un statut </h3>';

              
              echo '<div class="formulaire">

                        <form method="POST" action="statutok.php?action=comment&s='.$statut.'">
                 
                         <div class="textarea">
                           <textarea name="text" placeholder="commenter">
                           </textarea>
                      </div>
                      <div class="submit">
                           <input type="submit" value="Commenter"/>
                      </div>
                    </form>
                </div>';  

          break;

       case "editco":

        echo '<div class="fildariane">

                <ul>
                    <li>
                        <a href="../index.php">Accueil </a>
                    </li> <img src="../images/icones/fleche.png" class="fleche"/>

                    <li>
                        <a href="./index.php">Social </a>
                     </li> 
                     <img src="../images/icones/fleche.png" class="fleche"/>
                     <li>
                       <span style="color:black;">Editer commentaire </span></li>

                 </ul>      

         </div>

         <div class="page">';

                $com_id = (isset($_GET['c']))?$_GET['c']:"";

                if(empty($com_id))
                {
                  header('Location:./index.php');
                }

                $selectcom = $bdd->prepare('SELECT * FROM social_st_comment 
                                            WHERE membre_id = :id AND commentaires_id = :com_id');
                $selectcom->bindParam(':id',$id,PDO::PARAM_INT);
                $selectcom->bindParam(':com_id',$com_id,PDO::PARAM_INT);
                $selectcom->execute();

                $commentaire = $selectcom->fetch();

                echo '<h2 class="titre"> Modifier commentaire </h2>';

                 echo '<div class="formulaire">

                    <form method="POST" action="statutok.php?action=editco&s='.$statut.'&c='.$com_id.'">

                      <div class="textarea">
                           <textarea name="text" placeholder="commentter">'.
                           $commentaire['commentaires_text'].'
                           </textarea>
                      </div>
                      <div class="submit">
                           <input type="submit" value="Commenter"/>
                      </div>
                    </form>
                    </div>';  


       break;
       default:
             header('Location:./index.php');
     }

     echo '</div>';

     include "../includes/footer.php";