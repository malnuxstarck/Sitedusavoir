<?php

  $balises = true;
  include '../includes/session.php';
  include("../includes/identifiants.php");
  include("../includes/debut.php");
  include("../includes/menu.php");

  $cat = htmlspecialchars($_GET['cat']); 

  //on récupère dans l'url la variable cat

  switch($cat)   //1er switch
  {
      case "config":

          echo'<h1>Configuration du forum</h1>';

          //On récupère les valeurs et le nom de chaque entrée de latable

          $managerConfiguration = new ManagerConfiguration($bdd);
          $donneesConfigs = $managerConfiguration->toutesLesConfigurations();

          //Avec cette boucle, on va pouvoir contrôler le résultat pourvoir s'il a changé

          foreach ($donneesConfigs as $donneesConfig ) {

                 $config = new Configuration($donneesConfig);

              if ($config->valeur() != $_POST[$config->nom()])
              {
                  //On met ensuite à jour
                  $config->setValeur($_POST[$config->nom()]);
                  $managerConfiguration->miseAjoursConfiguration($config);
          
              }
           }

      //Et le message !
      echo'<br /><br />Les nouvelles configurations ont été mises à jour !<br />
           Cliquez <a href="./index.php">ici</a> pour revenir à l\'administration';

      break;

    case "forum":

      //Ici forum

      $action = htmlspecialchars($_GET['action']); 

      //On récupère lavaleur de action

      $managerForum = new ManagerForum($bdd);
      $managerCategorie = new ManagerCategorie($bdd);

      switch($action) //2ème switch
      {
          case "creer":
                //On commence par les forums
                if ($_GET['c'] == "f")
                {
                    $forum = new Forum($_POST);
                    $managerForum->nouveauForum($forum);

                    echo'<br /><br />Le forum a été créé !<br />
                         Cliquez <a href="./index.php">ici</a> pour revenir à l\'administration';
                }
                //Puis par les catégories
                elseif ($_GET['c'] == "c")
                {
                    $cat = new Categorie($_POST);
                    $managerCategorie->nouvelleCategorie($cat);
                    echo'<p>La catégorie a été créée !<br /> Cliquez <a href="./index.php">ici</a pour revenir à l administration</p>';
                }
        
        break;
        
        case "edit":

          echo'<h1>Edition d un forum</h1>';

          if($_GET['e'] == "editf")
          {
              //Récupération d'informations

              $forumN = new Forum($_POST);
              //Vérification
              $donneesF = $managerForum->infosForum($forumN->id());

              if (empty($donneesF)) 
                  erreur(ERR_FOR_EXIST);

            //Mise à jour
              $managerForum->miseAjoursForum($forumN);
            //Message

            echo'<p>
                   Le forum a été modifié !<br />Cliquez <a href="./index.php">ici</a> pour revenir à l administration
                 </p>';

          }
          elseif($_GET['e'] == "editc")
          {
              //Récupération d'informations
              $cat = new Categorie($_POST);

            //Vérification
            $donneesCat = $managerCategorie->infosCategorie($cat->id());
            if (empty(($donneesCat))) 
                erreur(ERR_CAT_EXIST);

            //Mise à jour
              $managerCategorie->miseAjoursCategorie($cat);
            //Message
            echo'<p>
                   La catégorie a été modifiée !<br /> Cliquez <a href="./index.php">ici</a> pour revenir à l administration
                 </p>';
          }
          elseif($_GET['e'] == "ordref")
          {

            //On récupère les id et l'ordre de tous les forums
            $donneesForums = $managerForum->tousLesForums();
            //On boucle les résultats

            foreach ($donneesForums as $donneesForum) {
                
                $forum = new Forum($donneesForum);
                $ordre = (int)$_POST[(string)$forum->id()];
                //Si et seulement si l'ordre est différent de l'ancien, on le met à jour

                  if ($forum->ordre() != $ordre)
                  {
                      $forum->setOrdre($ordre);
                      $managerForum->modifierOrdre($forum);
                  }
            }

            echo'<p>
                   L\'ordre a été modifié !<br />
                   Cliquez <a href="./index.php">ici</a> pour revenir à l\'administration
                 </p>';

          }
          elseif($_GET['e'] == "ordrec")
          {

              //On récupère les id et les ordres de toutes les catégories
              $donneesCats = $managerCategorie->tousLesCategories();
              //On boucle le tout

              foreach ($donneesCats as $donneesCat) {
                  
                  $cat = new Categorie($donneesCat);
                  $ordre = (int) $_POST[$cat->id()];
                  //On met à jour si l'ordre a changé

                  if($cat->id() != $ordre)
                  {
                      $cat->setOrdre($ordre);
                      $managerCategorie->modifierOrdre($cat);
                  }
            }

            echo'<p>
                   L\'ordre a été modifié !<br />
                   Cliquez <a href="./index.php">ici</a> pour revenir à l\'administration
                 </p>';
          }

          break;

          case "droits":

            //Récupération d'informations

            $forumA = new Forum($_POST);

            //Mise à jour
            $managerForum->miseAjoursDroits($forumA);

            //Message
            echo'<p>
                   Les droits ont été modifiés !<br />
                   Cliquez <a href="./index.php">ici</a> pour revenir à l\'administration
                 </p>';

            break;
          }

        break;

        case "membres":

          $action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';
          $managerMembre = new ManagerMembre($bdd);

          switch($action)
          {

              case "droits":
                  
                  $membre = new Membre($_POST);
                  $managerMembre->promouvoirMembre($membre);
              
                echo'<p>
                       Le niveau du membre a été modifié !<br />
                       Cliquez <a href="./index.php">ici</a> pour revenir à l\'administration
                     </p>';
                
                break;

              case "ban":

              //Bannissement dans un premier temps
              //Si jamais on n'a pas laissé vide le champ pour le pseudo

              if (isset($_POST['membre']) AND !empty($_POST['membre']))
              {
                   $pseudo = $_POST['membre'];
                   $donneesMembre = $managerMembre->recupereLeMembre($pseudo);
                   //Si le membre existe

                  if(!empty($donneesMembre))
                  {
                      $membre = new Membre($donneesMembre);
                      $membre->setRang(0);
                      
                      $managerMembre->promouvoirMembre($membre);

                      echo'<br /><br />
                      Le membre '.stripslashes(htmlspecialchars($membre->pseudo())).' a bien étébanni !<br />
                      <p> 
                          Cliquez <a href="./index.php">ici</a> pour revenir à l\'administration
                      </p>';
                
                }
                else
                {
                  echo'<p>
                         Désolé, le membre '.stripslashes(htmlspecialchars($pseudo)).' n\'existe pas !<br />
                         Cliquez <a href="./index.php?cat=membres&action=ban">ici</a> pour réessayer
                       </p>';
                }
              }

              //Debannissement ici

              $donneesBannis = $managerMembre->tousLesBannis();
              //Si on veut débannir au moins un membre

              if (!empty($donneesBannis))
              {
                    $i=0;

                    foreach ($donneesBannis as $donneesBanni) {

                        $banni = new Membre($donneesBanni);
                  
                        if(isset($_POST[$banni->id()]))
                        {
                            $i++;

                          //On remet son rang à 2
                            $banni->setRang(2);
                            $managerMembre->promouvoirMembre($banni);
                        }
                  }

                    if ($i != 0)
                        echo'<p>Les membres ont été débannis<br /> Cliquez <a href="./index.php">ici</a> pour retourner à l administration</p>';
                }

                break;
    	  }

              break;

  }

