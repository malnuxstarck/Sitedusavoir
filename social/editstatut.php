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
      echo '<p id="fildariane"><i><a href="./index.php">Accueil</a>--><ahref="./index.php">Social</a>-->Edition statut </i></p>
             <h3> Edition de votre Statut </h3>';


           $selectionstatut = $bdd->prepare('SELECT * FROM social_statut 
                                            WHERE membre_id = :membre AND statut_id = :statut ');
           $selectionstatut->bindParam(':membre',$id, PDO::PARAM_INT);
          $selectionstatut->bindParam(':statut',$statut, PDO::PARAM_INT);
          $selectionstatut->execute();

          $statutinfos = $selectionstatut->fetch();

          echo '<form action="statutok.php?action=edit&s='.$statutinfos['statut_id'].'" method="POST" enctype="multipart/form-data">

                     <div class="textarea">
                          <textarea name="statut">'.htmlspecialchars($statutinfos['statut_contenu']).' </textarea>
                     </div>

                     <div class="fichier">';

                            if($statutinfos['statut_photo'])
                                echo '<img src=".photos/'.$statutinfos['statut_photo'].'" alt=""/>';

                           echo '<input type="file" name="photo"/>
                      </div>
                      <div>
                            <input type="submit" value="Editer"/>
                       </div>

          </form>';
          break;
       case "comment":

       echo '<p id="fildariane"><i><a href="./index.php">Accueil</a>--><ahref="./index.php">Social</a>-->commenter statut </i></p>
       <h3> Edition de votre Statut </h3>';

              
              echo '<form method="POST" action="statutok.php?action=comment&s='.$statut.'">

                      <div class="textarea">
                           <textarea name="text" placeholder="commentter">
                           </textarea>
                      </div>
                      <div>
                           <input type="submit" value="Commenter"/>
                      </div>
                    </form>';  

          break;

       case "editco":

       echo '<p id="fildariane"><i><a href="./index.php">Accueil</a>--><ahref="./index.php">Social</a>-->Edition commentaire </i></p>
       <h3> Edition de votre Statut </h3>';

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

                 echo '<form method="POST" action="statutok.php?action=editco&s='.$statut.'&c='.$com_id.'">

                      <div class="textarea">
                           <textarea name="text" placeholder="commentter">'.
                           $commentaire['commentaires_text'].'
                           </textarea>
                      </div>
                      <div>
                           <input type="submit" value="Commenter"/>
                      </div>
                    </form>';  


       break;
       default:
             header('Location:./index.php');
       break;   
     }