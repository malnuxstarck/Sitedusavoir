<?php

echo '<body>
           <header>
		        <div class="header-top">
		             <h1 class="nomSite"><a href="../index.php"> Site du Savoir </a> </h1>
		       </div>
		       <div class="menu">
		             <ul class="menu-content">
		                 <li><a href="../forum/index.php"> Forum </a></li>
		                 <li><a href="../tutoriels/index.php"> Tutoriels </a></li>
		                 <li><a href="../blog/index.php"> Blog </a></li>
		             </ul>
		       </div>

		       <div class="search">
		             <form method="GET" action="../search.php">
		                   <div class="form-content">
		                        <div class="input-search">
		                            <input type="text" name="q" value="Rechercher">
		                        </div>
		                        <div class="button-search">
		                           <button type="submit">
		                                 <img src="../images/icones/search.png" alt="Q"/>
		                          </button>   
		                      </div>
		                   </div>
		             </form>
		       </div>

		       <div class="suscribe">';

		          $managerMembre = new ManagerMembre($bdd);

		          if(!$id)
		          {
			           echo  '<ul class="suscribe-content">
				                  <li><a  href="../register.php"> S\'inscrire </a></li>
				                  <li><a href="../connexion.php"> Se connecter </a></li>
			                 </ul>';
			      }
			      else
			      {

			      	
			      	$donnees = $managerMembre->infosMembre($id);
			      	$membre  = new Membre($donnees);

			      	$avatar = $membre->avatar();

                      echo '<div class="cercle">

                                 <ul>

                                   <li class="first">
                                       <a href="../membre/voirmonprofil.php?id='.$id.'"><img src="../images/avatars/'.$avatar.'" alt="avatar"/></a>

		                                 <ul> 
		                                           <li>
		                                               <a href="../membre/editerprofil.php?id='.$id.'">Parametres</a>
		                                          </li>

		                                          <li>
		                                              <a href="../membre/amis.php">Amis</a>
		                                          </li>

		                                          <li>
		                                               <a href="../membre/messagesprives.php">Messages</a>
		                                          </li>

		                                          <li>
		                                                <a href="../membre/notifications.php">Notiffications </a>
		                                          </li>

		                                          <li>
		                                                <a href="../membre/mescontenus.php"> Mes Contenus </a>
		                                          </li>

		                                          <li>
		                                                <a href="../deconnexion.php"> Se deconnecter </a>
		                                          </li>
		                                 </ul>
		                            </li>
		                            </ul>     
                           </div>';
			      }

		       echo '</div>
           </header>';

         if (session_status() == PHP_SESSION_NONE)
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

        





