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
					<p id="fildariane"><i> Vous etes ici </i> : <a href="../index.php">Accueil </a> --> <a href="./index.php">Blog </a>-->Nouveau article
					   <h1>Nouveau Article</h1>
					     <form method="post" action="billetok.php?action=creer" enctype="multipart/form-data">
						<fieldset><legend>Titre</legend>
						
						<label for="titre"> Titre * </label> 
						<input type="text" size="80" id="titre" name="titre" />
						
						 </fieldset>
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
						<fieldset><legend>Contenu</legend>

						<textarea cols=80 rows=8 id="message" name="message"></textarea>
						<br />  
				        <p><label for="logo"> Un logo * </label>
				        <input type="file" name="logo" value="Logo"/>
				        </p>
				        </br>

				        <p> 
				          <label for="illustration">Illustration </label> <input type="file" name="illustration"/>
				         </p>
				         </fieldset>
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

				    echo' <p id="fildariane"><i> Vous etes ici </i> : <a href="../index.php">Accueil </a> --> <a href="./index.php">Blog </a>-->'.$resultat['billet_titre'];

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

		         echo' <p id="fildariane"><i> Vous etes ici </i> : <a href="../index.php">Accueil </a> --> <a href="./index.php">Blog </a>-->'.$billets['billet_titre'];

		         echo '<div class="billet">

		                   <h1>'.$billets['billet_titre'].'</h1>

		                    <div id="contenu">'.$billets['billet_contenu'].'</div>
		                      
		                    <time>'.$billets['datebillet'];



		               echo '<p> Articles par :';
		               
		               $auteur = $bdd->prepare('SELECT membre_pseudo,membres.membre_id AS membre_id FROM membres INNER JOIN auteurs ON auteurs.membre_id = membres.membre_id
		                 	WHERE auteurs.billet_id = :billet');
		                 $auteur->bindValue(':billet',$billet,PDO::PARAM_STR);
		                 $auteur->execute();

		                 $nbre = $auteur->rowCount();
		                 
		                 $i=0;

		                 while($auteurs = $auteur->fetch())
		                 {
		                        
		                     	echo '<a href="../forum/voirprofil.php?action=consulter&m='.$auteurs['membre_id'].'"><span id="auteur">'.' '.$auteurs['membre_pseudo'].'</span></a>';

		                      $i = $i+1;
		                      
		                      if($i < $nbre AND $nbre > 0)

		                           echo '  et';
		                     
		                 }
		                      

		              echo '</p></div>';
		           
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

				 echo' <p id="fildariane"><i> Vous etes ici </i> : <a href="../index.php">Accueil </a> --> <a href="./index.php">Blog </a>-->Commenter un article';

				 echo '<form method="POST" action=billetok.php?action=comment&billet='.$billet.'>
		                <p> <textarea name="commentaire" rows="10" cols="80"> Votre commentaire </textarea></p>

		                <p> <input type="submit" value="Commenter"/></p>


				 ';



				break;

		default:

				header('Location:index.php');


}


