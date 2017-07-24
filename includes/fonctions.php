
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

 
function paginationListe($page, $nb_page, $link, $nb = 2)
{
       if($link == 'index.php')
            $link = $link.'?';
       else
           $link = $link.'&';  

        $list_page = array();

        for ($i = 1; $i <= $nb_page; $i++)
        {
                if (($i < $nb) OR ($i > $nb_page - $nb) OR (($i < $page + $nb) AND ($i > $page -$nb)))

                $list_page[] = ($i == $page)?'<strong>'.$i.'</strong>':'<a href="'.$link.'page='.$i.'">'.$i.'</a>';

                else
                {
                    if ($i >= $nb AND $i <= $page - $nb)
                        $i = $page - $nb;
                    elseif ($i >= $page + $nb AND $i <= $nb_page - $nb)
                        $i = $nb_page - $nb;
                    $list_page[] = '...';
                }
                
                echo $list_page[$i-1];
        }
}
