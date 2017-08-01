
<?php

class ManagerSearch
{
	protected $_db;

	public function __construct(PDO $db)
	{
		$this->setDb($db) ;
	}
	
	public function setDb($db)
	{
		$this->_db = $db ;
	}

	public function search($motRechercher)
	{
		$tables = array('topic' , 'post' ,'contenus');
		$champsTables = array('titre' , 'texte' ,'titre','introduction');

		$resultatsARetourner = array();

        for($i = 0 ; $i < 3 ; $i++)
        {

            $tousLesMots = explode(' ', $motRechercher);
            $requete = 'SELECT * FROM '.$tables[$i] ;
            $count = 0 ;

        	foreach($tousLesMots as $mot) 
        	{

                if(strlen($mot) > 3)
                { 	
	        		if($count == 0)
	        			$requete.=' WHERE ';
	        		else
	        			$requete.=' OR ';
	                
	                $requete.= $champsTables[$i] .' LIKE \'%'.$mot.'%\''; 
	        		$count++;
	            }

	        }

	     
	       $query =  $this->_db->query($requete);
	       $donnees = $query->fetchAll();

	       if(!empty($donnees))
	            $resultatsARetourner[$tables[$i]] = $donnees; 

	    }

	    return $resultatsARetourner ;

    }


    /**** les autres fonctions ***/

}