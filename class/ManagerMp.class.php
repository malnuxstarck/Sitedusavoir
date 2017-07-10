
<?php

class ManagerMp
{
	protected $_db;
	protected $_errors = array();
	public $nombreErreurs = 0 ;
    
    public function __construct(PDO $bdd)
    {
    	$this->setDb($bdd);
    }

    public function setDb($bdd)
    {
         $this->_db = $bdd;
    }

    public function envoyerMp(Mp $infoMessage)
    {

    	if(empty($infoMessage->titre()) || empty($infoMessage->texte()))
    	{
    		$this->nombreErreurs++;
    		$this->_errors['champsVides'] = 'Vous n \'avez rempli le titre ou le message a envoyer ';

    	}

    	if(empty($infoMessage->receveur()))
    	{
    		$this->nombreErreurs++;
    		$this->_errors['pasdereceveur'] = 'Vous devez specifiez un destinateur ';
    	}

    	if($this->nombreErreurs == 0)
    	{
    		$query = $this->_db->prepare('INSERT INTO mp (expediteur, receveur, titre, texte, mptime, lu) VALUES(:id, :dest, :titre, :txt, NOW(),\'0\')');

    		$query->bindValue(':id',$infoMessage->expediteur(),PDO::PARAM_INT);
			$query->bindValue(':dest',$infoMessage->receveur(),PDO::PARAM_INT);

			$query->bindValue(':titre',$infoMessage->titre(),PDO::PARAM_STR);
			$query->bindValue(':txt',$infoMessage->texte(),PDO::PARAM_STR);
			$query->execute();

			$query->CloseCursor();

			echo'<p>
						Votre message a bien été envoyé!<br />
						<br />Cliquez <a href="../index.php">ici</a> pour revenir à l index
						du
						site <br />
						<br />Cliquez <a href="./messagesprives.php">ici</a> pour retourner
						à la messagerie
				</p>';


    	}
    	else
    	{
    		foreach ($this->_errors as  $error) {
    			echo '<p>'.$error.'</p>';
    		}
    		echo '<br />Cliquez <a href="./messagesprives.php?action=nouveau">ici</a> pour retourner
						à la messagerie';
    	}

    }


    public function recupereLeMembre($pseudo)
	{
		$query = $this->_db->prepare('SELECT id,pseudo, COUNT(*) AS nbr 
			                          FROM membres 
			                          WHERE LOWER(pseudo) = :pseudo GROUP BY id,pseudo');

						$query->bindValue(':pseudo',strtolower($pseudo),PDO::PARAM_STR);
						$query->execute();
						$data = $query->fetch();

			if(!empty($data))	
			    return $data;
			else
			    return array(); 


	}

	public function deleteMp($idMp)
	{
		$query = $this->_db->prepare('DELETE FROM mp WHERE id = : id');
		$query->bindValue(':id' ,$idMp , PDO::PARAM_INT);
		$query->execute();

	} 

	public function infosMessage($idMp)
	{
		$query = $this->_db->prepare('SELECT * FROM mp WHERE id = :id');
		$query->bindValue(':id' ,$idMp , PDO::PARAM_INT);
		$query->execute();
		$donnees = $query->fetch();

		if(!empty($donnees))
			return $donnees;
		else
			return array();
	}

	public function messageLu($idMessage)
	{
		$query = $this->_db->prepare('UPDATE mp SET lu = :lu WHERE id= :id');
		$query->bindValue(':id',$idMessage, PDO::PARAM_INT);

		$query->bindValue(':lu','1', PDO::PARAM_STR);
		$query->execute();
		$query->CloseCursor();
	}

	public function tousLesMessages($idMoi)
	{
		$query = $this->_db->prepare('SELECT *
					                  FROM mp
					                  WHERE receveur = :id ORDER BY id DESC');

	    $query->bindValue(':id',$idMoi,PDO::PARAM_INT);
		$query->execute();

		$donnees = $query->fetchAll();
		if(!empty($donnees))
			return $donnees;
		else
			return array();

	}




}