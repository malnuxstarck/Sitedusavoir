<?php

require "../includes/session.php";
require "../../modele/includes/identifiants.php";
require "../../modele/includes/fonctions.php";
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







//Qu'est ce qu'on veut faire ? poster, répondre ou éditer ?

$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';

//Il faut être connecté pour poster !

if ($id == 0) 
	erreur(ERR_IS_CO);

//Si on veut poster un nouveau topic, la variable f se trouve dans l'url,
//On récupère certaines valeurs

if (isset($_GET['f']))
{
	$forum = (int) $_GET['f'];

	$data = getForumInfos($forum, $bdd);

	echo '<p id="fildariane">
	            <i>Vous êtes ici</i> : <a href="./index.php">Forum</a> --> <a href="./voirforum.php?f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a> --> Nouveau topic
	     </p>';
}

   //Sinon c'est un nouveau message, on a la variable t et
   //On récupère f grâce à une requête

elseif (isset($_GET['t']))
{
	$topic = (int) $_GET['t'];
	
	$data = getTopicInfos($topic, $bdd);

	$forum = $data['forum_id'];

	echo '<p id="fildariane">
	            <i>Vous êtes ici</i> : <a href="./index.php">Forum</a> --><a href="./voirforum.php?f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a> --> <a href="./voirtopic.php?t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
	                --> Répondre
	     </p>';
}

    //Enfin sinon c'est au sujet de la modération(on verra plus tard en détail)
      //On ne connait que le post, il faut chercher le reste

elseif (isset ($_GET['p']))
{
	$post = (int) $_GET['p'];

	$data = getPostInfos($post , $bdd);
	$topic = $data['topic_id'];
	
	$forum = $data['forum_id'];

	echo '<p id="fildariane">
	           <i>Vous êtes ici</i> : <a href="./index.php">Forum</a> --> <a href="./voirforum.php?f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
	          --> <a href="./voirtopic.php?t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
	          --> Modérer un message
	    </p>';
}



  include "../../vue/forum/poster.php";


?>
</div>
</body>
</html>
