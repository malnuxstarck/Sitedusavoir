
<?php

   function chargerClass($class)
   {
      require '../class/'.$class.'.class.php';
   }
   
   function erreur($err='')
   {
    
      $mess = ($err!='')?$err : 'Une erreur inconnue s\'est produite';
      exit('<div class="alert-danger">'. $mess .'</div><p>Cliquez <a href="../index.php">ici</a> pour revenir à la page d\'accueil</p> </div></body></html>');
   }

 function createAvatar($chaine , $blocks = 5 , $size = 100)
 {
     
       $togenerate  = ceil($blocks / 2);

       $hashsize = $togenerate * $blocks ; 

       $hash = md5($chaine); 

       $hash = str_pad($hash , $hashsize , $hash);

       $blockssize = $size / $blocks ;

       $color = substr($hash , 0, 6);

       $image = imagecreate($size,$size);

       $background = imagecolorallocate($image ,255,255,255);

       $color = imagecolorallocate($image , hexdec(substr($color,0,2)),hexdec(substr($color,2,2)),hexdec(substr($color,4,2)));


       for ($x = 0 ; $x < $blocks ; $x++)
       {
          for ($y = 0 ; $y < $blocks ; $y++)
          {
            if( $x < $togenerate)

               $pixel =  hexdec($hash[$x * $blocks + $y]) % 2 == 0;
            else
              $pixel =  hexdec($hash[($blocks - 1 - $x) *$blocks  + $y]) % 2 == 0;

            $pixelcolor = $background;

           if($pixel)
           {
            $pixelcolor = $color;

            }

              imagefilledrectangle($image,$x * $blockssize , $y*$blockssize, ($x+1)*$blockssize, ($y+1)*$blockssize, $pixelcolor);
           }
       }
        
        $name = time();

        $nomavatar = str_replace(' ','',$name).'.'.'png';
       
       imagepng($image , './images/avatars/'.$nomavatar);
       
      return $nomavatar;

}  


function move_avatar($avatar)
{

      $extension_upload = strtolower(substr( strrchr($avatar['name'],'.') ,1));

      $name = time();

      $nomavatar = str_replace(' ','',$name).".".$extension_upload;

      $name = "./images/avatars/".str_replace('','',$name).".".$extension_upload;

      move_uploaded_file($avatar['tmp_name'],$name);

        return $nomavatar;

}


function move_logo($logo)
{

      $extension_upload = strtolower(substr( strrchr($logo['name'],'.') ,1));

      $name = time();

      $nomlogo = str_replace(' ','',$name).".".$extension_upload;

      $name = "./logo/".str_replace('','',$name).".".$extension_upload;

      move_uploaded_file($logo['tmp_name'],$name);

        return $nomlogo;

}




function str_random($nombre)
{

  $alphabet ="0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";

   return substr(str_shuffle(str_repeat($alphabet,$nombre)) ,0, $nombre);
}



function verif_auth($auth_necessaire)
{
  $level=(isset($_SESSION['level']))?$_SESSION['level']:1;
  
  return ($auth_necessaire <= intval($level));
}

function get_list_page($page, $nb_page, $link, $nb = 2)
{

        $list_page = array();

        for ($i=1; $i <= $nb_page; $i++)
        {
                if (($i < $nb) OR ($i > $nb_page - $nb) OR (($i < $page + $nb) AND ($i > $page -$nb)))

                $list_page[] = ($i==$page)?'<strong>'.$i.'</strong>':'<a href="'.$link.'&amp;page='.$i.'">'.$i.'</a>';

                else
                {
                    if ($i >= $nb AND $i <= $page - $nb)
                    $i = $page - $nb;
                    elseif ($i >= $page + $nb AND $i <= $nb_page - $nb)
                    $i = $nb_page - $nb;
                    $list_page[] = '...';

                }
        }

        $print= implode('-', $list_page);
        
        return $print;
}



function reconnected_from_cookie()
{

  if(session_status() == PHP_SESSION_NONE)
  {
    session_start();
  }


  if(isset($_COOKIE['souvenir']) && !isset($_SESSION['membre_id']))
  {
      include('identifiants.php');

       $cookie = $_COOKIE['souvenir'];
       $parts =  explode('==',$cookie);
       $user_id = $parts[0];

        
       $req = $bdd->prepare('SELECT * FROM membres WHERE membre_id = :id');
       $req->execute(array('id' => $user_id));

       $user = $req->fetch();

       if($user)
       {
              $expected = $user_id.'=='.$user['cookie'].sha1($user['membre_id'].'MALNUX667');
              
              if($expected == $cookie)
              {
               
                setcookie('souvenir',$cookie,time()+60*60*24*7);

                $_SESSION['pseudo'] = $user['membre_pseudo'];
                $_SESSION['level'] = $user['membre_rang'];
                $_SESSION['id'] = $user['membre_id'];

        
                $requete = $bdd->prepare('UPDATE membres SET membre_derniere_visite = NOW() WHERE membre_id = :id');
                $requete->bindValue(':id',$user['membre_id'],PDO::PARAM_INT);
                $requete->execute();


               
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







