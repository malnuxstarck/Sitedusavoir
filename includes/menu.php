<?php

echo '<body>

          <div id="page">

		      <div id="banniere">

		             <h1> Site Du Savoir </h1>
		             
		             <form method="GET" action="">

		                <div id="form">
		                <p>
		                   <input type="text" placeholder="Rechercher"/>
		                </p>
		               
		              </div>
		              </form>

					 <div class="element_menu">

					    <ul>
				            <li><a href="../forum">Forum</a></li>
				            <li><a href="#">Tutoriels</a></li>
							<li> <a href="#">Extras </a></li>
							<li> <a href="#">Generales </a>
							<li> <a href="#">Blog</a></li>
						</ul>

				</div>        

		     </div>';


		     if(isset($_SESSION['flash'])): ?>

		      <?php foreach($_SESSION['flash'] as $cle => $message): ?>

		        <div class="alert alert-<?=$cle ?>">
		           <?= $message; ?>
		           </div>

		        <?php endforeach; ?>

		        <?php unset($_SESSION['flash']); ?>
		        <?php endif; ?>   

		<div id="corps_forum">




