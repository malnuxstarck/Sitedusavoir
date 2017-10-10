<?php

include '../includes/session.php';
require_once '../includes/identifiants.php';

$titre = $_SESSION['pseudo'] . ' | SiteduSavoir.com ';

require_once '../includes/debut.php';
require_once '../includes/menu.php';


echo '<ul class="fildariane">
  <li><a href="../index.php">Accueil</a></li>
  <li><span>'.$pseudo.'</span></li>
</ul>';

$identifiant = (isset($_GET['id']))?$_GET['id'] :0;

if ($id == 0)
{

   header('Location:../index.php');
}

if ($id != $identifiant)
{

   header('Location:./editerprofil.php?id='.$id);
}

$managerMembre = new managerMembre($bdd);
$donnees = $managerMembre->infosMembre($id);
$membre = new Membre($donnees);

if (empty($_POST)) // Si on la variable est vide, on peut considérer qu'on est sur la page de formulaire
{
  
        echo '<div class="page">
                  <h1 class="titre">Modification Infos : '.$pseudo .'</h1>';


       echo '<div class="formulaire">

	               <form method="post" action="editerprofil.php?id='.$id.'" '.'id="formulaire">
		               <div class="input">
			                <label for="pseudo"><img src="../images/icones/person.png"></label>
			                <input name="pseudo" value="'.$membre->pseudo().'" type="text" placeholder="(doit contenir entre 3 et 15 caractères, sans espace) " />
		              </div>

		              <div class="input">  
		                   <label for="password"><img src="../images/icones/mdp.png"></label>
		                   <input type="password" name="password" placeholder="Entrez le mot de passe" required /> 
		              </div>

		              <div class="input">
		                  <label for="confirmPassword"><img src="../images/icones/mdp.png"></label>
		                  <input type="password" name="confirmPassword" placeholder="Confirmer le mot de passe" required />
		              </div>

			            <div class="input">
			                  <label for="localisation"><img src="../images/icones/place.png"></label>
			                  <input type="text" value ="'.$membre->localisation().'" name="localisation" required/>
			            </div>

			              <div class="input">
			                  <label for="email"><img src="../images/icones/mail.png"></label>
			                  <input type="email" name="email" value="'.$membre->email().'" placeholder="Votre email" required/>
			              </div>

			              <div class="input">
			                  <label for="siteweb"></label>
			                  <input type="text" value="'.$membre->siteweb().'" name="siteweb" placeholder="siteweb"/>
			              </div>

		              <div class="textarea">
		                 
		                  <textarea name="signature" value="'.$membre->signature().'" >'.$membre->signature().'
		                  </textarea>
		              </div>


		              <p style="text-align:center;"><img src="../images/avatars/'.$membre->avatar().'" alt="pas davatar"/></p>

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
	$managerMembre = new managerMembre($bdd);
	$donnees = $managerMembre->infosMembre($id);

	$membreEnCours = new Membre($donnees);
	$membreAjour   = new Membre($_POST);
	if(isset($_FILES['avatar']))
	      $membreAjour->setAvatar($_FILES['avatar']);

	$membreAjour->setId($membreEnCours->id());

	if($membreEnCours->pseudo() != $membreAjour->pseudo()){
		$managerMembre->pseudoLibre($membreAjour->pseudo());
		$managerMembre->pseudoValide($membreAjour->pseudo());
	}
    else
		$membreAjour->setPseudo($membreEnCours->pseudo());

	if($membreEnCours->email() != $membreEnCours->email())
		$managerMembre->emailValide($membreAjour->email());
	else
		$membreAjour->setEmail($membreEnCours->email());
	
	if(empty($membreAjour->signature())){
		$membreAjour->setSignature($membreEnCours->signature());
	}

	if(!empty($membreAjour->password())){
		$managerMembre->verifyPassword($membreAjour);
	}
	else
		$membreAjour->setPassword($membreEnCours->password());


	if(!empty($membreAjour->avatar())){

	    if ($managerMembre->verifAvatar($membreAjour->avatar()))
        {   
            $nomavatar = $membreAjour->moveAvatar($membreAjour->avatar());
            $membreAjour->setAvatar($nomavatar);
        }
    }
    else
    	$membreAjour->setAvatar($membreEnCours->avatar());

    
    if($managerMembre->nombresErreurs == 0){
        $managerMembre->miseAjoursMembre($membreAjour);
        $_SESSION['flash']['success'] = "Mise à jours des infos reussie .";
    }
    else{
            $messageFinal = '';

	    	foreach ($managerMembre->errors() as $value) {
	    		$value.='</br>';
	    		$messageFinal.=$value;
	    	}
	    	$_SESSION['flash']['danger'] = $messageFinal ;
    }
               
    header('Location:editerprofil.php?id='.$membreEnCours->id());
	

}

echo '</div>';
include "../includes/footer.php";

?>

</body>
</html>



