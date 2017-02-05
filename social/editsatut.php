<?php

$titre="Edit | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

echo '<p id="fildariane"><i><a href="./index.php">Accueil</a>--><ahref="./index.php">Social</a>-->Edition statut </i></p>';

    $statut = (!isset($_GET['s']))?$_GET['s']:"";

    if(empty($statut))
    {
      header("Location:index.php");  
    }

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