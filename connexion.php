<?php
  include './includes/session.php';
  $titre="Connexion";
  include_once './includes/identifiants.php';
  include_once'./includes/debut.php';
  include_once './includes/menu.php';
  echo '<p id="fildariane"><i>Vous êtes ici</i> : <a href="../index.php">Accueil </a> --> Connexion';

  reconnected_from_cookie();
?>

<?php
	echo '<h1 class="titre">Connexion</h1>';
	if ($id != 0)
	{
?>
	<div class="alert-danger">
<?php
	erreur(ERR_IS_CO);
?>
  </div>

<?php
	}
	 $page='index.php';
?>

<?php
  if (!isset($_POST['pseudo'])) //On est dans la page de formulaire
  {
  	echo '<form method="post" action="connexion.php" id="formulaire">
  				  <p>
  					  <label for="pseudo">Pseudo </label><input name="pseudo" type="text" id="pseudo" />
  					</p>

  					<p>
  						<label for="password">Mot de Passe </label><input type="password" name="password" id="password" />
  					</p>

  					<p>
  						<input type="checkbox" name="souvenir"> <label for="souvenir"> Se souvenir de Moi </label>
  					</p>

  					<p>
  					  <input type="submit" value="Connexion"/>
  					</p>
  			  </form>
  			  <a href="./register.php">Pas encore inscrit ?</a>
  			  </div>
  			</div>
  		</body>
  	</html>';
  }

  else
  {
    $message='';
  	if (empty($_POST['pseudo']) || empty($_POST['password']) )
  	//Oublie d'un champ
  	{
  	   $message = '<p>
  	                 une erreur s\'est produite pendant votre identification. Vous devez remplir tous les champs.
  	               </p>
  	               <p>
  	                 Cliquez <a href="./connexion.php">ici</a> pour revenir.
  	               </p>';
  	}

  	else //On check le mot de passe
  	{
    	$query = $bdd->prepare('SELECT membre_mdp, membre_id,membre_rang, membre_pseudo,membre_inscrit
    	                        FROM membres
                              WHERE membre_pseudo = :pseudo
                              AND membre_inscrit IS NOT NULL');

    	$query->bindValue(':pseudo',$_POST['pseudo'],PDO::PARAM_STR);

    	$query->execute();

    	$data = $query->fetch();

    	if(PASSWORD_VERIFY($_POST['password'],$data['membre_mdp']))
      {
    		if ($data['membre_rang'] == 0) //Le membre est banni
    	  {
          $message="<p>Vous avez été banni, impossible de vous connecter sur ce site. </p>";
    	  }
    	  else
    	  {
    	    if(isset($_POST['souvenir']))
    	  	{
    	  		$cookie = str_random(250);

    	  		$req = $bdd->prepare('UPDATE membres
                                  SET cookie = :cookie
                                  WHERE membre_id = :id');
    	  		$req->execute(array('cookie'=> $cookie, 'id' => $data['membre_id']));

  	    		setcookie('souvenir',$data['membre_id'].'=='.$cookie.sha1($data['membre_id'].'MALNUX667'),time() + 60 * 60 *24 *7 );
  	    	}

  				$_SESSION['pseudo'] = $data['membre_pseudo'];
  				$_SESSION['level'] = $data['membre_rang'];
  				$_SESSION['id'] = $data['membre_id'];

  				$requete = $bdd->prepare('UPDATE membres
                                    SET membre_derniere_visite = NOW()
                                    WHERE membre_id = :id');

  				$requete->bindValue(':id',$data['membre_id'],PDO::PARAM_INT);
  				$requete->execute();

  		  $_SESSION['flash']['success'] ='Bienvenue '.$data['membre_pseudo'].', vous êtes maintenant connecté ! ';
        header('Location:index.php');
                     
  	    }
    	}

    	else // Acces pas OK !
    	{
    		$message ='<p>
    					       Une erreur s\'est produite pendant votre identification ou vous n\'avez pas confirmer votre compte. <p> Le mot de passe ou le pseudo entré n\'est pas correct.
    				       </p>

                   <p>
    				         Cliquez <a href="./connexion.php">ici</a> pour revenir à la page précédente, cliquez <a href="./index.php">ici</a> pour revenir à la page d\'accueil
    		           </p>';
    	}
      $query->CloseCursor();
  	}
  	echo $message.'</div></div></body></html>';
  }
?>

<input type="hidden" name="page" value="<?php
  if(isset($_SERVER['HTTP_REFERER'])) 
     echo $_SERVER['HTTP_REFERER']; ?>" />

<?php
  if(isset($_POST['page']))
   $page = htmlspecialchars($_POST['page']);
   echo 'Cliquez <a href="./'.$page.'">ici</a> pour revenir à la page précedente';
?>
