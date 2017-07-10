<?php

/*
**@class ManagerWhoIsOnline 
**Gerer la class WhoIsOnline
*/


class ManagerWhoIsOnline
{
	protected $_db;
    
    public function __construct(PDO $bdd)
    {
    	$this->setDb($bdd);
    }

    public function setDb($bdd)
    {
         $this->_db = $bdd;
    }

    public function nombresDeVisiteursEnLigne()
    {
    	$donnees = $this->_db->query(' SELECT COUNT(*) AS nbr_visiteurs
                              FROM whoisonline 
                              WHERE online_id = 0');
    	$donnees = $donnees->fetch();

    	return $donnees["nbr_visiteurs"];

    }

    public function nombresDeMembresEnLigne()
    {
    	$query = $this->_db->prepare('SELECT id, pseudo
			                          FROM whoisonline
			                          LEFT JOIN membres 
			                          ON online_id = id
			                          WHERE online_time > SUBDATE(NOW(), INTERVAL 5 MINUTE) 
			                          AND online_id <> 0');

    	$query->execute();

        $count_membres=0;
        $texte_a_afficher='';

		while ($data = $query->fetch())
		{
		    $count_membres ++;

		    $texte_a_afficher .= '<a href="../forum/voirprofil.php?m='.$data['id'].'&amp;action=consulter">'.stripslashes(htmlspecialchars($data['pseudo'])).'</a> ,';
		}

		$texte_a_afficher = substr($texte_a_afficher, 0, -1);

		return array("nombre" => $count_membres , "texte_a_afficher" => $texte_a_afficher);

    }

    public function quiSontEnLigne()
    {
        $query = $this->_db->prepare('SELECT * FROM whoisonline WHERE online_id <> 0');
        $query->execute();
        $donnees = $query->fetchAll();
        if(!empty($donnees))
            return $donnees;
        else
            array();
    }

    public function updateWhoIsOnline(WhoIsOnline $membre)
    {
    	$query = $this->_db->prepare('INSERT INTO whoisonline VALUES(:id,NOW(),:ip) ON DUPLICATE KEY UPDATE online_time = NOW() , online_id = :id');
    	$query->bindValue(':id',$membre->online_id() , PDO::PARAM_INT);
    	$query->bindValue(':ip', $membre->online_ip(),PDO::PARAM_INT);
    	$query->execute();
        $query->CloseCursor();

    }
    

    public function deleteWhoIsOnline()
    {
    	$query = $this->_db->prepare('DELETE FROM whoisonline WHERE online_time < SUBDATE(NOW(),INTERVAL 5 MINUTE)');
        $query->execute();
        $query->CloseCursor();
    }

    public function deleteMembreOnline($id)
    {
    	$query = $this->_db->prepare("DELETE FROM whoisonline WHERE online_id  = :id ");
    	$query->bindValue(':id' ,$id ,PDO::PARAM_INT);
    	$query->execute();
    	$query->CloseCursor();
    }
}