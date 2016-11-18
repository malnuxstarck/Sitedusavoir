<?php

session_start();

$titre="Billet / sitedusavoir.com";
include("../includes/identifiants.php");
include("../includes/debut.php");
include("../includes/menu.php");




	 $action =(isset($_GET['action']))?$_GET['action']:'';



switch($action)
{
	case "creer":


if(verif_auth(MODO))
{

	?>
	   <h1>Nouveau Article</h1>
	     <form method="post" action="billetok.php?action=creer">
		<fieldset><legend>Titre</legend>
		<input type="text" size="80" id="titre" name="titre" /></fieldset>
		<fieldset><legend>Mise en forme</legend>
		<input type="button" id="gras" name="gras" value="Gras"
		onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
		<input type="button" id="italic" name="italic" value="Italic"
		onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
		<input type="button" id="souligné" name="souligné" value="Souligné"
		onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
		<input type="button" id="lien" name="lien" value="Lien"
		onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
		<br /><br />
		<img src="../images/smileys/heureux.gif" title="heureux"
		alt="heureux" onClick="javascript:smilies(':D');return(false)" />
		<img src="../images/smileys/lol.gif" title="lol" alt="lol"
		onClick="javascript:smilies(':lol:');return(false)" />
		<img src="../images/smileys/triste.gif" title="triste" alt="triste"
		onClick="javascript:smilies(':triste:');return(false)" />
		<img src="../images/smileys/cool.gif" title="cool" alt="cool"
		onClick="javascript:smilies(':frime:');return(false)" />
		<img src="../images/smileys/rire.gif" title="rire" alt="rire"
		onClick="javascript:smilies('XD');return(false)" />
		<img src="../images/smileys/confus.gif" title="confus" alt="confus"
		onClick="javascript:smilies(':s');return(false)" />
		<img src="../images/smileys/choc.gif" title="choc" alt="choc"
		onClick="javascript:smilies(':O');return(false)" />
		<img src="../images/smileys/question.gif" title="?" alt="?"
		onClick="javascript:smilies(':interrogation:');return(false)" />
		<img src="../images/smileys/exclamation.gif" title="!" alt="!"
		onClick="javascript:smilies(':exclamation:');return(false)"
		/></fieldset>
		<fieldset><legend>Message</legend>

		<textarea cols=80 rows=8 id="message" name="message"></textarea>
		<br />  
		<button type="submit">Creer</button>
	</form>
	<?php
}

else
{
	header('Loction:index.php');
}
 break;
		case "edit":

		if(verif_auth(MODO))
		{

			$billet = $_GET['billet'];

			$requete = $bdd->prepare('SELECT * FROM billets WHERE billet_id = :billet');
			$requete->bindValue(':billet',$billet,PDO::PARAM_INT);
			$requete->execute();

		    $resultat = $requete->fetch();

		    echo '<h1> Modification d\'un article </h1>

		     <form method="POST" action = "billetok.php?action=edit&billet='.$billet.'">
             <p>
                <input type="text" name="titre" value="'.$resultat['billet_titre'].'"/>
             </p>
             <p>
                <textarea rows="10" cols="70" name="contenu">'.$resultat['billet_contenu'].'</textarea>
             </p>
             <p>
                 <button type="submit">Envoyer </button>
             </p>
             </form>';


   


		}

		else
		{
			header('Location:index.php');
		}


        break;

		case "voir":
        


         $billet = htmlspecialchars($_GET['billet']);


         $requete = $bdd->prepare('SELECT datebillet,billets.billet_id,billet_titre,billet_contenu FROM billets WHERE billet_id = :billet');

         $requete->bindValue(':billet',$billet,PDO::PARAM_INT);
         $requete->execute();

         $billets = $requete->fetch();

         echo '<div class="billet">
                   <h1>'.$billets['billet_titre'].'</h1>

                    <div id="contenu">'.$billets['billet_contenu'].'</div>
                      
                    <time>'.$billets['datebillet'].'

              </div>';
           
           $req = $bdd->prepare('SELECT * FROM commentaires INNER JOIN billets ON billets.billet_id = commentaires.billet_id INNER JOIN membres ON commentaires.membre_id = membres.membre_id WHERE billets.billet_id= :billet  ');
           $req->bindParam(':billet',$billet,PDO::PARAM_INT);
           $req->execute();

           echo '<ul>';

           while($resultat = $req->fetch())
           {
              echo '<li> <span id="auteur"> <a href="../forum/voirprofil.php?action=consulter&m='.$resultat['membre_id'].'">'.$resultat['membre_pseudo'].'</a></span><p>'.$resultat['combillet'].'</p></li>';
               
           }

         echo '</ul>';



         break;

		 case "comment":

		 $billet  = htmlspecialchars($_GET['billet']);

		 echo '<form method="POST" action=billetok.php?action=comment&billet='.$billet.'>
                <p> <textarea name="commentaire" rows="10" cols="80"> Votre commentaire </textarea></p>

                <p> <input type="submit" value="Commenter"/></p>


		 ';



		break;

		default:

		header('Location:index.php');


	}


