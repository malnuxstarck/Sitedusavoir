<?php

echo '<body>

         <div id="page">
		      <div id="banniere">

		             <h1 id="titre"> Site Du Savoir </h1>

		             <form id ="form" method="GET" action="search.php">

			             <p>
			                 <input type="text" name="q" placeholder="Rechercher"/>
			             </p>

			             <p>
			                <input type="submit" value="Recherche"/>
			             </p>
		               
		             </form>

					 <div class="menu">

					    <ul>
					        <li><a href="#"><img src="../images/accueil.png" id="accueil" alt="Accueil"/> Accueil</a></li>
				            <li><a href="../forum">Forum</a></li>
				            <li><a href="#">Tutoriels</a></li>
							<li> <a href="#">Extras </a></li>
							<li> <a href="#">Social</a>
							<li> <a href="http://blog.sitedusavoir.com">Blog</a></li>
						</ul>

				</div>        

		     </div>';
		     ?>

            
          <div id="corps_forum">
          
          <?php if (session_status() == PHP_SESSION_NONE)
          session_start();
          ?>
           <?php if(isset($_SESSION['flash'])): ?>

      <?php foreach($_SESSION['flash'] as $cle => $message): ?>

        <div class="alert alert-<?=$cle ?>">
           <?= $message; ?>
           </div>

        <?php endforeach; ?>

        <?php unset($_SESSION['flash']); ?>

        <?php endif; ?>   

        





