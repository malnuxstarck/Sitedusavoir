<?php

   function debug ($variable)
   {

   	echo '<pre>'. print_r($variable , true) .'</pre>';

   }

   function str_random($nombre)
   {

   	$alphabet ="0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";

   	return substr(str_shuffle(str_repeat($alphabet,$nombre)) ,0, $nombre);
   }


function logged_only()
{

  if(session_status() == PHP_SESSION_NONE)
{
  session_start();
}


if(!isset($_SESSION['auth']))
{

	$_SESSION['flash']['danger'] = "Acces non autorise a la page";
	
}


}


function reconnected_from_cookie()
{

  if(session_status() == PHP_SESSION_NONE)
{
  session_start();
}


  if(isset($_COOKIE['souvenir']) && !isset($_SESSION['auth']))
     {

      require '../db.php';

      $cookie = $_COOKIE['souvenir'];
      $parts =  explode('==',$cookie);
      $user_id = $parts[0];

      $req = $bdd->prepare('SELECT * FROM users WHERE id = :id')->execute(array('id' => $user_id));

      $user = $req->fetch();

      if($user)
      {
        $expected = $user_id.'=='.$cookie.sha1($user['id'].'moi');

        if($expected == $cookie)
        {
          session_start();
          $_SESSION['auth'] = $user;

          setcookie('souvenir',$cookie,time()+60*60*24*7);
         
        }

        else
        {

          setcookie('souvenir',NULL,-1);
        }


      }

      else
      {
        setcookie('souvenir',NULL,-1);
      }



     }




}
?>