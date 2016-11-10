
<?php

   
   function erreur($err='')
   {

    

   	$mess = ($err!='')? $err : 'Une erreur inconnue s\'est produite';

   	exit('<div class="alert-danger">'. $mess .'</div><p>Cliquez <a href="../index.php">ici</a> pour revenir à la page d\'accueil</p> </div></body></html>');
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


function str_random($nombre)
{

  $alphabet ="0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";

   return substr(str_shuffle(str_repeat($alphabet,$nombre)) ,0, $nombre);
}

  ?>



<?php
function verif_auth($auth_necessaire)
{
  $level=(isset($_SESSION['level']))?$_SESSION['level']:1;
  
  return ($auth_necessaire <= intval($level));
}

function get_list_page($page, $nb_page, $link, $nb = 2)
{

$list_page = array();
for ($i=1; $i <= $nb_page; $i++){
if (($i < $nb) OR ($i > $nb_page - $nb) OR (($i < $page + $nb) AND
($i > $page -$nb)))
$list_page[] = ($i==$page)?'<strong>'.$i.'</strong>':'<a
href="'.$link.'&amp;page='.$i.'">'.$i.'</a>';
else{
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
?>