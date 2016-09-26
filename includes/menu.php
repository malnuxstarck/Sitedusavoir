<?php

echo '<body>

       <div id="banniere">

		     <span> je suis une baniiere vide </span>

	  </div>

<div id="menu">        

	<div class="element_menu">

		<h3>Menu</h3>

		<ul>

		<li><a href="../connexion.php"> Se connecter </a></li>

		<li><a href="../register.php"> S\'inscrire</a></li>

		<li> <a href="../deconnexion.php"> Se de connecter </a></li>


		</ul>

   </div>       

    <div class="element_menu">

		<h3>Navigation</h3>

		<ul>

		<li><a href="../forum">Forum</a></li>

		<li><a href="">Tutoriels</a></li>
		<li> <a href="">Extras </a></li>

		</ul>

   </div>        

</div>

';


     if(isset($_SESSION['flash'])): ?>

      <?php foreach($_SESSION['flash'] as $cle => $message): ?>

        <div class="alert alert-<?=$cle ?>">
           <?= $message; ?>
           </div>

        <?php endforeach; ?>

        <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>   

<div id="corps_forum">




