
<?php
   

    function chargerClass($class,$position ="./")
    {
      if(!file_exists($position.'class/'.$class.'.class.php'))
               $position = "../";

         require $position.'class/'.$class.'.class.php';
    }
     
   function erreur($err='')
   {
    
      $mess = ($err!='')?$err : 'Une erreur inconnue s\'est produite';
      exit('<div class="alert-danger">'. $mess .'</div><p>Cliquez <a href="../index.php">ici</a> pour revenir à la page d\'accueil</p> </div></body></html>');
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







