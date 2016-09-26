<?php
    
    try
     {
       $bdd = new PDO('mysql:host=localhost;dbname=forum;charset=utf8','root','',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

     }
     
     catch(Exeption $erreur)
     {
       
       die("(SQL) Erreur :".$erreur->getMessage());
     
     }







?>
