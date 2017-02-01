<?php

$titre="Lecture | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

$tuto_id = (!empty($_GET['tuto']))?(int)$_GET['tuto']:1;


$req = $bdd->prepare('SELECT tutos_titre FROM tutos WHERE tutos_id = :tuto');

$req->execute(array('tuto' => $tuto_id));

$tuto = $req->fetch();


echo $tuto['tutos_titre'];

?>