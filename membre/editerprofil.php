<?php

include "../includes/session.php";
require_once("../includes/identifiants.php");

$titre = $_SESSION['pseudo'] . ' | SiteduSavoir.com ';

require_once("../includes/debut.php");

require_once("../includes/menu.php");


echo '<div class="fildariane">
         <ul>
            <li><a href="../index.php">Accueil</a></li><img class="fleche" src="../images/icones/fleche.png"/><li>'.$pseudo.'</li>
         </ul>
  </div>';

$identifiant = (isset($_GET['id']))?$_GET['id'] :0;

if ($id == 0)
{ 
	
   header('Location:../index.php');
}



if ($id != $identifiant)
{ 
	
   header('Location:./editerprofil.php?id='.$id);
}


$req = $bdd->prepare('SELECT * FROM membres WHERE membre_id = :id');

$req->bindParam(':id', $id, PDO::PARAM_INT);

$req->execute();

$membre = $req->fetch();

if (empty($_POST)) // Si on la variable est vide, on peutconsidérer qu'on est sur la page de formulaire
{
  
        echo '<div class="page">
                  <h1 class="titre">Modification Infos : '.$pseudo .'</h1>';


       echo '<div class="formulaire">

	               <form method="post" action="editerprofil.php?action=1" id="formulaire">
		               <div class="input">
			                <label for="pseudo"><img src="../images/icones/person.png"></label>
			                <input name="pseudo" value="'.$membre['membre_pseudo'].'" type="text" placeholder="(doit contenir entre 3 et 15 caractères, sans espace) " />
		              </div>

		              <div class="input">  
		                   <label for="password"><img src="../images/icones/mdp.png"></label>
		                   <input type="password" name="password" placeholder="Entrez le mot de passe" required /> 
		              </div>

		              <div class="input">
		                  <label for="confirm"><img src="../images/icones/mdp.png"></label>
		                  <input type="password" name="confirm" placeholder="Confirmer le mot de passe" required />
		              </div>

                       <div class="submit">
		                    <input type="submit" value="Modifier"/>
                       </div>
	              </form>
             </div>
	             </br>
                <div class="formulaire">

		             <form method="post" action="editerprofil.php?action=2" >

			              <div class="input">
			                  <label for="localisation"><img src="../images/icones/place.png"></label>
			                  <input type="text" value ="'.$membre['membre_localisation'].'" name="localisation" required/>
			              </div>

			              <div class="input">
			                  <label for="email"><img src="../images/icones/mail.png"></label>
			                  <input type="email" name="email" value="'.$membre['membre_email'].'" placeholder="Votre email" required/>
			              </div>

			              <div class="input">
			                  <label for="siteweb"></label>
			                  <input type="text" value="'.$membre['membre_siteweb'].'" name="siteweb" placeholder="siteweb"/>
			              </div>

			              <div class="submit">
			                 <input type="submit" value="Modifier"/>
			              </div>
		            </form>
		        </div>

	            </br>
                <div class="formulaire">

		               <form method="post" action="editerprofil.php?action=3">

		              <div class="textarea">
		                 
		                  <textarea name="signature" value="'.$membre['membre_signature'].'" >'.$membre['membre_signature'].'
		                  </textarea>
		              </div>


		              <p style="text-align:center;"><img src="../images/avatars/'.$membre['membre_avatar'].'" alt="pas davatar"/></p>

		               <div class="input">
		                  <label for="avatar"></label>

		                  <input type="file" name="avatar" id="avatar"/>(Taille max : 1mo)
		              </div>

		      
		      <div class="submit"><input type="submit" value="Modifier"/></div>

		      </form>
		    </div> ';
 

}



else
{
	

	$action = (int)htmlspecialchars($_GET["action"]);

	switch($action):

	case 1:

	// Le cas ou on veut juste modifier son pseudo soit mot de passe ou les deux

		$pseudo = (isset($_POST["pseudo"]))?$_POST["pseudo"]:"";

		$pass = (isset($_POST["password"]))?$_POST["password"]: "";

		$confirm = (isset($_POST["confirm"]))?$_POST["confirm"]:"";

		if(empty($pseudo) AND empty($pass))
		{
			$_SESSION["flash"]["danger"] = "Au moins un des champs doit etre remplis . Vous pouvez reessayer";
			header('Location:editerprofil.php?id='.$id);
		}

		if(!empty($confirm) && $pass == $confirm )
	    {
	    	

	      $pass =  PASSWORD_HASH($pass,PASSWORD_BCRYPT);
	      $req1 = $bdd->prepare('UPDATE membres SET membre_mdp = :pass WHERE membre_id =:id');
	      $req1->execute(array('id'=> $id , 'pass' => $pass));
		  
		 }

	   

		$req = $bdd->prepare('UPDATE membres SET membre_pseudo = :pseudo WHERE membre_id =:id');
	    $req->execute(array('pseudo'=> $pseudo , 'id' => $id));
	    $req->closeCursor();

		$_SESSION["flash"]["success"] = "Mot de passe et/ou pseudo mis a jours";
		header('Location:editerprofil.php?id='.$id);
		
    break;

    case 2:

	    $mail = $bdd->query("SELECT membre_email FROM membres WHERE membre_id = $id");
        $mail = $mail->fetch();
        $mail = $mail['membre_email'];

	   
	  
	    //Changer son email, siteweb , localisation

	    $localisation = (!empty($_POST['localisation']))?$_POST['localisation']:"Non localisé";
	    $siteweb      = (!empty($_POST['siteweb']))?$_POST['siteweb']:"Aucun siteweb";
	    $email        = (!empty($_POST['email']))?$_POST['email']:$mail;



	    $modification = $bdd->prepare('UPDATE membres SET membre_email  = :email, membre_siteweb = :siteweb,membre_localisation = :localisation WHERE membre_id =:id');

	    $modification->bindParam(':email',$email,PDO::PARAM_STR);
	    $modification->bindParam(':siteweb',$siteweb,PDO::PARAM_STR);
	    $modification->bindParam(':localisation',$localisation,PDO::PARAM_STR);
	    $modification->bindParam(':id',$id,PDO::PARAM_INT);

	    $modification->execute();
	    $modification->closeCursor();

	    $_SESSION['flash']['success'] = "Les modifications ont ete apportées .";

	    header('Location:editerprofil.php?id='.$id);

	

	break;

	case 3:

	// dernier des cas si on veut modifier sa signature ou son avatar;

	
	break;

	default:
	$_SESSION["flash"]["danger"] = "Action invalide ";
	header('Location:editerprofil.php?id='.$id);
	

	endswitch;


}
echo '</div>';
include "../includes/footer.php";

?>

</body>
</html>



