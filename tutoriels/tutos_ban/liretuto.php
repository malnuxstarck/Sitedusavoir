<?php

$titre="Lecture | SiteduSavoir.com";
include ("../includes/session.php");
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");

$tuto_id = (!empty($_GET['tuto']))?(int)$_GET['tuto']:1;

$req = $bdd->prepare('SELECT * FROM tutos WHERE tutos_id = :tuto');

$req->bindParam(':tuto',$tutos_id , PDO::PARAM_INT);

$req->execute();

$tuto = $req->fetch();


echo $tuto['tutos_titre'];