<?php
require "../includes/session.php";
require "../../modele/includes/identifiants.php";
require "../../modele/includes/fonctions.php";

$forum = (int) $_GET['f'];
$data = getForumInfos($forum , $bdd);

$titre = $data['forum_name'] .' | SiteduSavoir.com'; 


require "../includes/debut.php";
include("../includes/constantes.php");
include ("../../modele/includes/debut.php");
$balises=(isset($balises))?$balises:0;
if($balises)
{
    include('../../vue/includes/debut.php');
}
require "../../vue/includes/menu.php";
require "../includes/menu.php";
require "../includes/fonctions.php";


   if (!verif_auth($data['auth_view']))
	{
		erreur(ERR_AUTH_VIEW);
	}

	$totalDesMessages = $data['forum_topic'] + 1;
	$nombreDeMessagesParPage = 25;
	$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);


	echo '<p id="fildariane"><i>Vous êtes ici</i> : <a href="./index.php">Forum</a> --> <a href="./voirforum.php?f='.$forum.'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>';

	$page = (isset($_GET['page']))?intval($_GET['page']):1;
	//On affiche les pages 1-2-3, etc.
	echo '<p>Page : ';

	for ($i = 1 ; $i <= $nombreDePages ; $i++)
	{
		if ($i == $page) //On ne met pas de lien sur la page actuelle
		{
			echo $i;
		} 
		else 
		{
			echo '<a href="voirforum.php?f='.$forum.'&amp;page='.$i.'">'.$i.'</a>';
		}
	}
	echo '</p>';

	$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;
	//Le titre du forum
	echo '<h1 class="titre">'.stripslashes(htmlspecialchars($data['forum_name'])).'</h1><br/><br />';
	//Et le bouton pour poster


	if (verif_auth($data['auth_topic']))
	{
		//Et le bouton pour poster
		echo'<a href="./poster.php?action=nouveautopic&amp;f='.$forum.'"><img src="../images/nouveau.gif" alt="Nouveau topic" title="Poster un nouveau topic"></a>';
	}

	$query = selectForumByForum('=' , $bdd , $forum , $id);

	//On lance notre tableau seulement s'il y a des requêtes !
	
	if ($query->rowCount()>0)
	{
?>

<table>
<tr>
<th><img src="../images/annonce.png" alt="Annonce" /></th>
<th class="titre"><strong>Titre</strong></th>
<th class="nombremessages"><strong>Réponses</strong></th>
<th class="nombrevu"><strong>Vus</strong></th>
<th class="auteur"><strong>Auteur</strong></th>
<th class="derniermessage"><strong>Dernier message</strong></th>
</tr>
<?php
	while ($data=$query->fetch())
	{
		
	//Pour chaque topic :
	//Si le topic est une annonce on l'affiche en haut
	//mega echo de bourrain pour tout remplir

		$ico_mess = verifConnected($id,$data);

        echo'<tr><td><img src="../images/'.$ico_mess.'" alt="Annonce"
		/></td>
		<td id="titre"><strong>Annonce : </strong>
		<strong><a href="./voirtopic.php?t='.$data['topic_id'].'"
		title="Topic commencé à '.$data['topic_time'].'">
		'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a></strong></td>
		<td class="nombremessages">'.$data['topic_post'].'</td>
		<td class="nombrevu">'.$data['topic_vu'].'</td>
		<td><a href="./voirprofil.php?m='.$data['topic_createur'].'
		&amp;action=consulter">
		'.stripslashes(htmlspecialchars($data['membre_pseudo_createur'])).'</a></td>';
		//Selection dernier message
		$nombreDeMessagesParPage = 15;
		$nbr_post = $data['topic_post'] +1;
		$page = ceil($nbr_post / $nombreDeMessagesParPage);
		echo '<td class="derniermessage">Par
		<a href="./voirprofil.php?m='.$data['post_createur'].'
		&amp;action=consulter">
		'.stripslashes(htmlspecialchars($data['membre_pseudo_last_posteur'])).'</a><br/>
		A <a href="./voirtopic.php?t='.$data['topic_id'].'&amp;page='.$page.'#p_'.$data['post_id'].'">'.$data['post_time'].'</a></td></tr>';
	}
?>
</table>
<?php
	}
	$query->CloseCursor();


	$query = selectForumByForum('<>',$bdd,$forum , $id);

	if ($query->rowCount()>0)
	{

?>
<table>
<tr>
<th><img src="../images/sujet.png" alt="Message" /></th>
<th class="titre"><strong>Titre</strong></th>
<th class="nombremessages"><strong>Réponses</strong></th>
<th class="nombrevu"><strong>Vus</strong></th>
<th class="auteur"><strong>Auteur</strong></th>
<th class="derniermessage"><strong>Dernier message </strong></th>
</tr>

<?php
	//On lance la boucle
	while ($data = $query->fetch())
	{

  	    $ico_mess = verifConnected($id,$data);

	  //Ah bah tiens... re vla l'echo de fou

		echo'<tr><td><img src="../images/'.$ico_mess.'" alt="Message"/></td>
		<td class="titre">
		<strong><a href="./voirtopic.php?t='.$data['topic_id'].'" title="Topic commencé à
		'.$data['topic_time'].'">
		'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a></strong></td>
		<td class="nombremessages">'.$data['topic_post'].'</td>
		<td class="nombrevu">'.$data['topic_vu'].'</td>
		<td><a href="./voirprofil.php?m='.$data['topic_createur'].'
		&amp;action=consulter">
		'.stripslashes(htmlspecialchars($data['membre_pseudo_createur'])).'</a></td>';
		//Selection dernier message
		$nombreDeMessagesParPage = 15;
		$nbr_post = $data['topic_post'] +1;
		$page = ceil($nbr_post / $nombreDeMessagesParPage);
		echo '<td class="derniermessage">Par<a href="./voirprofil.php?m='.$data['post_createur'].'
		&amp;action=consulter">
		'.stripslashes(htmlspecialchars($data['membre_pseudo_last_posteur'])).'</a><br
		/>
		A <a href="./voirtopic.php?
		t='.$data['topic_id'].'&amp;page='.$page.'#p_'.$data['post_id'].'">'.$data['post_time'].'</a></td></tr>';
	}
?>

</table>

<?php

  }

  else
  {
    echo'<p>Ce forum ne contient aucun sujet actuellement</p>';
  }

  $query->CloseCursor();
?>

</div>
</body>
</html>
