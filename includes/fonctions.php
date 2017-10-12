
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
        $lienComplet ="";
        for ($i = 1; $i <= $nb_page; $i++)
        {
                if (($i < $nb) OR ($i > $nb_page - $nb) OR (($i < $page + $nb) AND ($i > $page - $nb)))
                 $lienComplet = ($i == $page)?'<strong>'.$i.'</strong>':'<a href="'.$link.'page='.$i.'">'.$i.'</a>';
                else
                {
                    if ($i >= $nb AND $i <= $page - $nb)
                        $i = $page - $nb;
                    elseif ($i >= $page + $nb AND $i <= $nb_page - $nb)
                        $i = $nb_page - $nb;
                    $lienComplet = '...';
                }
                
                echo $lienComplet;
                
        }
}


function afficherDate($date)
{
    $texteFinal = "";
    $dateEnSecondes = strtotime($date);
    $difference = time() - $dateEnSecondes;
    
    $années = (int)( $difference / (3600 * 24 *365));
    $resteAnnée = ( $difference % (3600*24*365));
    $mois = (int) ($resteAnnée / (3600*24*30));
    $resteMois = ($resteAnnée % (3600*24*30));
    
    $jours = (int)($resteMois / (3600*24));
    $resteJours = ($resteMois % (3600*24));
    $heures = (int)($resteJours /3600);
    if($années > 0)
        $texteFinal.= $années .'années';
    else if($années > 0 AND $mois > 0)
          $texteFinal.= $années .'années et '. $mois .' mois';
    else if($mois > 0 AND $jours > 0)
            $texteFinal.= $mois .'mois et '. $jours .' jours';
    else if($jours > 0 AND $heures > 0)
          $texteFinal.= $jours .'jours et '. $heures .' heures';
    else
        $texteFinal.= "Quelques Instants";
        return $texteFinal;    
}
