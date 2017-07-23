<?php
include "./includes/session.php";
$titre="Liste des membres | SiteduSavoir.com";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");

  //A partir d'ici, on va compter le nombre de members
  //pour n'afficher que les 25 premiers


  $convert_order  =   array('pseudo', 'inscrit','posts', 'visite');
  $convert_tri    =   array('ASC', 'DESC');

  //On récupère la valeur de s

  if (isset($_POST['s'])) 
    $sort = $convert_order[$_POST['s']];

  else 
    $sort = $convert_order[0];

  //On récupère la valeur de t
  if (isset($_POST['t'])) 
    $tri = $convert_tri[$_POST['t']];

  else 
    $tri = $convert_tri[0];

  $managerMembre = new ManagerMembre($bdd);
  $total = $managerMembre->totalDesMembres() + 1;

  $MembreParPage = 25;
  $NombreDePages = ceil($total / $MembreParPage);

  
echo '<div class="fildariane">
              <ul>
               <li> 
                   <a href="../index.php">Accueil</a>
               </li>
                   <img class="fleche" src="../images/icones/fleche.png"/>

                  <li>
                     <a href="./memberlist.php">Listes des membres</a>
                  <li>
            </ul>

         </div>

        <div class="page">';

  //Nombre de pages
  $page = (isset($_GET['page']))?intval($_GET['page']):1;
  
  //On affiche les pages 1-2-3, etc.
  echo '<p class="pagination">';

  for ($i = 1 ; $i <= $NombreDePages ; $i++)
  {
    if ($i == $page) //On ne met pas de lien sur la page actuelle
    {
      echo '<strong>'.$i.'</strong>';
    }
    else
    {
      echo'<a href="memberlist.php?page='.$i.'">'.$i.'</a>';
    }
  }

  echo '</p>';

  $premier = ($page - 1) * $MembreParPage;

  //Le titre de la page

  echo '<h1>Liste des membres</h1><br /><br />';
?>

<form action="memberlist.php" method="post">
  <p>
    <label for="s">Trier par : </label>

    <select name="s" id="s">
      <option value="0" name="0">Pseudo</option>
      <option value="1" name="1">Inscription</option>
      <option value="2" name="2">Messages</option>
      <option value="3" name="3">Dernière visite</option>
    </select>

    <select name="t" id="t">
      <option  value="0" name="0">Croissant</option>
      <option value="1" name="1">Décroissant</option>
    </select>

    <input type="submit" value="Trier" />
  </p>
</form>

<?php

  //Requête
  $donneesMembres = $managerMembre->listeDesMembres($premier , $MembreParPage , $sort , $tri);

  if (!empty($donneesMembres))
  {
?>

<table>
  <tr>
    <th class="pseudo">
      <strong>Pseudo</strong>
    </th>

    <th class="posts">
      <strong>Messages</strong>
    </th>

    <th class="inscrit">
      <strong>Inscrit depuis le</strong>
    </th>

    <th class="derniere_visite">
      <strong>Dernière visite</strong>
    </th>

    <th>
      <strong>Connecté</strong>
    </th>
  </tr>

<?php
  //On lance la boucle
  foreach ($donneesMembres as $donneesMembre) {

        $membre = new Membre($donneesMembre);
        $enLigne = new WhoIsOnline($donneesMembre);

    echo '
    <tr>
      <td>
        <a href="./forum/voirprofil.php?m='.$membre->id().'&amp;action=consulter">'.stripslashes(htmlspecialchars($membre->pseudo())).'</a>
      </td>

      <td>'.$membre->posts().'</td>
      <td>'.$membre->inscrit().'</td>
      <td>'.$membre->visite().'</td>';

    if (!$enLigne->online_id()) 
      echo '<td>non</td>';

    else 
      echo '<td>oui</td>';

    echo '</tr>';
  }
?>

</table>

<?php
  }
  else //S'il n'y a pas de message
  {
    echo'
    <p>
      Le site et le forum ne contient aucun membre actuellement
    </p>';
  }
?>

</div>

<?php include "includes/footer.php"; ?>
</body>
</html>
