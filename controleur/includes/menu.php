<?php
     

    if (session_status() == PHP_SESSION_NONE)
          session_start();

    if(isset($_SESSION['flash'])): 

      foreach($_SESSION['flash'] as $cle => $message): ?>

        <div class="alert alert-<?=$cle ?>">
           <?= $message; ?>
       </div>

        <?php endforeach; ?>

        <?php unset($_SESSION['flash']); ?>

        <?php endif; ?>   

        





