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



//On récupère la valeur de la variable action

$action =(isset($_GET['action']))?htmlspecialchars($_GET['action']):'';
// Si le membre n'est pas connecté, il est arrivé ici par erreur
if ($id == 0) 
	erreur(ERR_IS_CO);

require "../../modele/forum/postok.php";
?>
</div>
</body>
</html>
