<?php

require "../includes/session.php";
require "../../modele/includes/identifiants.php";
require "../../modele/includes/fonctions.php";
include("../includes/constantes.php");

require "../includes/fonctions.php";
include("../includes/bbcode.php"); 

if(isset($_GET['t']))
    $topic = (int)$_GET['t'];
else
   $topic = 1 ;

$data = getTopicInfos($topic, $bdd);

$titre= $data['topic_titre'];

require "../includes/debut.php";
include ("../../modele/includes/debut.php");
$balises=(isset($balises))?$balises:0;
if($balises)
{
    include('../../vue/includes/debut.php');
}
require "../../vue/includes/menu.php";
require "../includes/menu.php";

if (!verif_auth($data['auth_view']))
{
  erreur(ERR_AUTH_VIEW);
}

    $forum = $data['forum_id'];
    $totalDesMessages = $data['topic_post'] + 1 ;
    $nombreDeMessagesParPage = 15;
    $nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);

    require "../../modele/forum/voirtopic.php";


  echo '<p id="fildariane"><i>Vous êtes ici</i> : <a href="./index.php">Forum</a> -->
  <a href="./voirforum.php?f='.$forum.'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
  --> <a href="./voirtopic.php?t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>';
  echo '<h1 class="titre">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</h1><br/><br />';

  //Nombre de pages
  $page = (isset($_GET['page']))?intval($_GET['page']):1;

  //On affiche les pages 1-2-3 etc...

  echo '<p>Page : ';
  for ($i = 1 ; $i <= $nombreDePages ; $i++)
  {
    if ($i == $page) //On affiche pas la page actuelle en lien
    {
      echo $i;
    }
    else
    {
      echo '<a href="voirtopic.php?t='.$topic.'&page='.$i.'">' . $i . '</a> ';
    }
  }

  echo'</p>';

  if(verif_auth($data['auth_modo']))
  {
    $query = getAllForumsExceptHe($bdd,$forum);
    //$forum a été définie tout en haut de la page !
    echo'<p>Déplacer vers :</p>
         <form method="post" action=postok.php?action=deplacer&amp;t='.$topic.'>
         <select name="dest">';
    while($data=$query->fetch())
    {
      echo'<option value='.$data['forum_id'].' id='.$data['forum_id'].'>
          '.$data['forum_name'].'
          </option>';
    }
    $query->CloseCursor();

    echo'</select>
         <input type="hidden" name="from" value='.$forum.'>
         <input type="submit" name="submit" value="Envoyer" />
         </form>';

  	$datas = isTopicLock($bdd,$topic);

  	if ($datas['topic_locked'] == 1) // Topic verrouillé !
  	{
    	echo'<a href="./postok.php?action=unlock&t='.$topic.'">deverrouiller
    	     <img src="../images/unlock.png" alt="deverrouiller"
    	     title="Déverrouiller ce sujet" /></a>';
  	}

  	else //Sinon le topic est déverrouillé !
  	{
    	echo'<a href="./postok.php?action=lock&amp;t='.$topic.'">verrouiller
    	<img src="../images/lock.png" alt="verrouiller" title="Verrouiller ce sujet" /></a>';
  	}

  	$query->CloseCursor();

    echo'<form method="post" action=postok.php?action=autorep&amp;t='.$topic.'><select name="rep">';

    $query = getAllAutoMess($bdd);

    while ($data = $query->fetch())
    {
      echo '<option value="'.$data['automess_id'].'">
           '.$data['automess_titre'].'</option>';
    }
    echo '</select><input type="submit" name="submit" value="Envoyer" /></form>';
    $query->CloseCursor();
  }
  $premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

  //On affiche l'image répondre

  if (verif_auth($data['auth_post']))
  {//On affiche l'image répondre
    echo'<a href="./poster.php?action=repondre&amp;t='.$topic.'">
    <img src="../images/repondre.gif" alt="Répondre" title="Répondre à ce topic"></a>';
  }

  if (verif_auth($data['auth_topic']))
  {
    //On affiche l'image nouveau topic
    echo'<a href="./poster.php?action=nouveautopic&amp;f='.$data['forum_id'].'">
    <img src="../images/nouveau.gif" alt="Nouveau topic" title="Poster un nouveau topic"></a>';
  }

  $query->CloseCursor();
  //Enfin on commence la boucle !

  $query= getAllpost($topic,$premierMessageAafficher,$nombreDeMessagesParPage, $bdd);
  
  if ($query->rowCount()<1)
  {
    echo'<p>Il n y a aucun post sur ce topic, vérifiez l url et reessayez</p>';
  }

  else
  {

    require "../../vue/forum/voirtopic.php";
  }
  //Si tout roule on affiche notre tableau puis on remplit avec une boucle

