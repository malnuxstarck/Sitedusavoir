<?php


/*


if($id)
{
   echo 
   '<p> <a href="../membre/voirmonprofil.php?id='.$id.'">Mon compte </a></p>';

   echo '<p><a href="../deconnexion.php"> Se deconnecter </a></p>';

   echo '</div>';
*/

echo '<body>

           <div id="page">

		      <div id="banniere">

		             <h1 id="titre"> Site Du Savoir </h1>

		             <div id ="infos">';

		             if($id)
		             {
		             	echo 

		             	'<ul>
			             	    <li> 
			             	       <a href="../membre/voirmonprofil.php?id='.$id.'">Mon compte </a>

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
                                                <a href="../deconnexion.php"> Se deconnecter </a>
                                          </li>
			             	       </ul>

	                           </li>
		             	 </ul>

		             	 ';

		             	

		             	echo '</div>';

		             }

		             else
		             {
		             	echo '</div>';
		             }

		             echo

					 '<div class="menu">

					    <ul>
					        <li>
					            <a href="../index.php"><img src="../images/accueil.png" id="accueil" alt="A"/> Accueil</a>
					        </li>

				            <li>
				                <a href="../forum">Forum</a>
				            </li>

				            <li>
				                 <a href="../tutoriels">Tutoriels</a>
				            </li>

							<li> 
							     <a href="http://social.sitedusavoir.com">Social</a>
							</li>    
							<li> 
							    <a href="http://blog.sitedusavoir.com">Blog</a>
							</li>
						</ul>

				</div>        

		     </div>';

		     ?>

            
        
          
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

        





