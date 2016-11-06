<!DOCTYPE html>
  <html>
       <head>
             <?php
                  if(!empty($titre))
                  {
                  echo '<title>'.$titre.'</title>';
                  }
                  else
                  {
                  echo '<title>Forum</title>';
                  }
                  ?>
                  <meta charset="UTF-8"/>
                  <link rel="stylesheet" type="text/css" href="../css/style.css"/>
                  <meta name="author" content="MalnuxStarck"/>
                  <meta name="viewport" content="width=device-width,initial-scale=0"/>
            </head>

            <?php

            if(isset($_SESSION['level'],$_SESSION['id'],$_SESSION['pseudo']))
            {
                  
            $lvl = (int)$_SESSION['level'];
            $id = (int)$_SESSION['id'];
            $pseudo = $_SESSION['pseudo'];
            }
            else
            {
            $lvl = 1;
            $id = 0;
            $pseudo = '';
            }
            
            include("fonctions.php");
            include("constantes.php");




             $ip = ip2long($_SERVER['REMOTE_ADDR']);
//Requête
$query=$bdd->prepare('INSERT INTO forum_whosonline VALUES(:id,NOW(),:ip)
ON DUPLICATE KEY UPDATE
online_time = NOW() , online_id = :id');
$query->bindValue(':id',$id,PDO::PARAM_INT);
$query->bindValue(':ip', $ip, PDO::PARAM_INT);
$query->execute();
$query->CloseCursor();




$query=$bdd->prepare('DELETE FROM forum_whosonline WHERE online_time
< SUBDATE(NOW(),INTERVAL 5 MINUTE)');

$query->execute();
$query->CloseCursor();

            $balises=(isset($balises))?$balises:0;
if($balises)
{

?>

<script>

function bbcode(bbdebut, bbfin)
{
var input = window.document.formulaire.message;
input.focus();
if(typeof document.selection != 'undefined')
{
      var range = document.selection.createRange();
var insText = range.text;
range.text = bbdebut + insText + bbfin;
range = document.selection.createRange();
if (insText.length == 0)
{
      range.move('character', -bbfin.length);
}else
{
range.moveStart('character', bbdebut.length +
insText.length +
bbfin.length);
}
range.select();
}
else if(typeof input.selectionStart != 'undefined')
            
 {     
var start = input.selectionStart;
var end = input.selectionEnd;
var insText = input.value.substring(start, end);
input.value = input.value.substr(0, start) + bbdebut + insText +
bbfin + input.value.substr(end);
var pos;
if (insText.length == 0)
{
      pos = start + bbdebut.length;
}
else
{
      pos = start + bbdebut.length + insText.length + bbfin.length;
}
input.selectionStart = pos;
input.selectionEnd = pos;
}
else
{
var pos;
var re = new RegExp('^[0-9]{0,3}$');
while(!re.test(pos))
{
      pos = prompt("insertion (0.." + input.value.length + "):", "0");
}

if(pos > input.value.length)
{
      pos = input.value.length;
}

var insText = prompt("Veuillez taper le texte");
input.value = input.value.substr(0, pos) + bbdebut + insText + bbfin + input.value.substr(pos);
}
}

function smilies(img)
{
   window.document.formulaire.message.value += '' + img + '';
}
</script>

<?php

}

?>