<!DOCTYPE html>
  <html>
       <head>
             <?php
                  if(!empty($titre)){ echo '<title>'.$titre.'</title>'; }

                  else{echo '<title> Sitedusavoir </title>';}
                  ?>
                  <meta charset="UTF-8"/>
                  <link rel="stylesheet" type="text/css" href="../../vue/css/style.css"/>
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
            

         
            

?>