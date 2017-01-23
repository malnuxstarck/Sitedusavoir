<?php

require "../includes/session.php";
$titre="Profil | SiteduSavoir.com";
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

$membre = isset($_GET['m'])?(int) $_GET['m']:'';

if(!$membre OR $id = 0)
{
	$membre = 1 ;
}
elseif (!$membre AND $id ) {
   $membre = $id ;
}

require "../../modele/forum/voirprofil.php";
//On affiche les infos sur le membre

require "../../vue/forum/voirprofil.php";
$query->closeCursor();
    
  
?>
</div>
</body>
</html>


