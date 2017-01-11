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