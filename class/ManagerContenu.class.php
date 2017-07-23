
<?php

class ManagerContenu
{
	protected $_db;   // PDO objet pour les oprations vers la bd
	protected $_errors = array();  // contient les messages d'erreurs
	public $_iErrors = 0 ;   // nombres d'erreurs
	
	public function __construct(PDO $db)
	{
		$this->setDb($db) ;
	}
	
	public function setDb($db)
	{
		$this->_db = $db ;
	}

	public function errors()
	{
		return $this->_errors;
	}
   
   public function verifierContenuChamps(Contenu $contenuInfos)
   {
   	
   	    if(empty($contenuInfos->titre()))
	    {
            $this->_iErrors++;
            $this->_errors['titre'] = 'Le titre ne peut pas etre vide .';
        }

        if(empty($contenuInfos->introduction()) || empty($contenuInfos->conclusion()))
        {
      	    $this->_iErrors++;
            $this->_errors["intro-conc"] = "Votre introduction ou votre conclusion est vide .";
        }

        if(empty($contenuInfos->cat()))
        {
      	    $this->_iErrors++;
      	    $this->_errors['categorie'] = "Une categorie doit etre selectionner .";
        }
      
   }

   public function verifierBanniere(Contenu $contenuInfos)
   {
   	   if($contenuInfos->banniere()['error'] == 0)
       {
           $extensions_valides  = array('png','jpg','jpeg','gif');
           $extension = substr(strchr($contenuInfos->banniere()['name'],'.'),1);

           if(in_array($extension,$extensions_valides))
           {

	           	$nomBanniere = substr($contenuInfos->titre(), 0,20).'.'.$extension;
	           	move_uploaded_file($contenuInfos->banniere()['tmp_name'], './bannieres/'.$nomBanniere);
           }
           else
           {
           	   $this->_iErrors++;
           	   $this->_errors["avatar"] = "Fichier non valides , le fichier doit etre un png, jpeg , gif ,jpg .";
           }
       }
       else
       {
           $nomBanniere = 'default.jpg';
	
       }

       return $nomBanniere;
   }

   public function ajouterNouveauContenu(Contenu $contenuInfos , $auteur)
   {
   	    $query = $this->_db->prepare('INSERT INTO contenus(titre , introduction,conclusion ,banniere ,publication,cat,validation,confirmation,type)
      		                  VALUES(:titre , :intro ,:conc ,:ban , NOW(), :cat , :valid , :conf , :type)');
      	$query->execute(array(

				              'titre'  => $contenuInfos->titre(),
				              'intro'  => $contenuInfos->introduction(),
				              'conc'   => $contenuInfos->conclusion(),
				              'ban'    => $contenuInfos->banniere(),
				              'cat'    => $contenuInfos->cat(),
				              'valid'  => $contenuInfos->validation(),
				              'conf'   => $contenuInfos->confirmation(),
				              'type'   => $contenuInfos->type()
                           )
      	                );

      	$query->closeCursor();

      	$idContenu = $this->_db->lastInsertId();
      	$managerAuteur = new ManagerAuteur($this->_db);
      	$managerAuteur->ajouterAuteur($auteur , $idContenu);

      	return $idContenu ;
      

   }

   public function donneLeContenu($contenu , $auteur = NULL)
   {
   	   if($auteur != NULL){

   	        $query= $this->_db->prepare('SELECT contenus.id as id , membres.id as auteur ,titre,introduction,conclusion, type ,banniere,validation,confirmation,cat,publication
	                                FROM contenus
	                                LEFT JOIN auteurs
	                                ON contenus.id = auteurs.idcontenu
	                                LEFT JOIN membres
	                                ON membres.id = auteurs.membre
	                                WHERE contenus.id = :cont
	                                AND membres.id = :memb');

		    $query->bindParam(':cont', $contenu , PDO::PARAM_INT);
		    $query->bindParam(':memb', $auteur , PDO::PARAM_INT);
		    $query->execute();
	    }
	    else
	    {
	    	$query = $this->_db->prepare('SELECT *
	                                      FROM contenus 
	                                      WHERE id = :cont');
	    	$query->bindValue(':cont' , $contenu , PDO::PARAM_INT);
	    	$query->execute();
	    }

       

       $infosContenu = $query->fetch();

       if(!empty($infosContenu))
       	    return $infosContenu;
       	else
       		return array();
   }


   public function miseAjourContenu(Contenu $infoContenu)
   {
   	    $query = $this->_db->prepare('UPDATE contenus 
   		                              SET introduction = :intro ,conclusion = :conc , titre = :titre , validation = :valid 
   		                              WHERE id = :contenu');

   	    $query->execute(array('intro'    => $infoContenu->introduction(), 
   		                  'titre'    => $infoContenu->titre() , 
   		                  'conc'     => $infoContenu->conclusion(),
   		                  'contenu'  => $infoContenu->id(),
   		                  'valid'    => $infoContenu->validation()));
   	    $query->closeCursor();

   	    $_SESSION['flash']['success'] = "Votre contenu a été  mis a jour avec succes :) ";
   	    header('Location:./editioncontenu.php?contenu='.$infoContenu->id());
   }

   public function tousLesContenus($type , $debut , $nombreTotal = 20)
   {
   	    $query = $this->_db->prepare('SELECT * FROM contenus WHERE type = :type ORDER BY publication LIMIT :debut , :nombre');
   	    $query->bindValue(':type' , $type , PDO::PARAM_STR);
   	    $query->bindValue(':debut' , $debut , PDO::PARAM_INT);
   	    $query->bindValue(':nombre' , $nombreTotal , PDO::PARAM_INT);

   	    $query->execute();
   	    $donnees = $query->fetchAll();

   	    if(!empty($donnees))
   	    	return $donnees;
   	    else
   	    	return array();
   }

   public function totalDeContenu($type)
   {
   	   $query = $this->_db->prepare('SELECT COUNT(*) AS nbr FROM contenus WHERE type = :type');
   	   $query->bindValue(':type' , $type ,PDO::PARAM_STR);
   	   $query->execute();
   	   $total = $query->fetchColumn();

   	   return $total;

   }

}