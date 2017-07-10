

<?php

class ManagerAmi
{
	protected $_db;
	protected $_errors = array();
	public $_iErrors = 0 ;
	
	public function __construct(PDO $db)
	{
		$this->setDb($db) ;
	}
	
	public function setDb($db)
	{
		$this->_db = $db ;
	}

	public function recupereLeMembre($pseudo)
	{
		$query = $this->_db->prepare('SELECT pseudo, COUNT(*) AS nbr 
			                          FROM membres 
			                          WHERE LOWER(pseudo) = :pseudo GROUP BY pseudo');

						$query->bindValue(':pseudo',strtolower($pseudo),PDO::PARAM_STR);
						$query->execute();
						$data = $query->fetch();

			if(!empty($data))	
			    return $data;
			else
			    return array();    		
	}

	public function addAmi($pseudoAmi,$idMoi, PDO $bdd)
	{
		$managerMembre = new managerMembre($bdd);
		$infosSurAmi = $this->recupereLeMembre($pseudoAmi);

		if(empty($infosSurAmi))
		{
			$this->_iErrors++;
			$this->_errors["existePas"] = "Ce membre ne semble pas exister </br> ";

		}
		else
		{
			$donnees = $managerMembre->infosMembre($infosSurAmi['pseudo']);
			$membreAmi = new Membre($donnees) ;

			if($membreAmi->id() == $idMoi)
			{
				$this->_iErrors++;
				$this->_errors["leMalin"] = "Voulez vous etre votre propre ami ? :joke: </br> ";
			}

			if($this->dejaAmi($membreAmi->id() , $idMoi) > 0)
			{
				$this->_iErrors++;
				$this->_errors["dejaAmi"] = "Vous etes deja ami avec cette personne </br>";
			}

        }

        if ($this->_iErrors == 0)
		{

				$donnees  = array('fromt' => $idMoi , 'toa' =>$membreAmi->id() ,'confirm' => '0');
				$amitier = new Ami($donnees);

				$query = $this->_db->prepare('INSERT INTO amis (fromt, toa,confirm, dateamitie)
									          VALUES(:idMoi, :idAmi, :conf,NOW())');

				$query->bindValue(':idMoi',$amitier->fromt(),PDO::PARAM_INT);
				$query->bindValue(':idAmi', $amitier->toa(), PDO::PARAM_INT);
				$query->bindValue(':conf',$amitier->confirm(),PDO::PARAM_STR);

				$query->execute();
				$query->CloseCursor();

								   echo '<p>
											   <a href="/voirprofil.php?m='.$membreAmi->id().'">'.stripslashes(htmlspecialchars($membreAmi->pseudo())).'</a>
												a bien été ajouté à vos amis, il faut toutefois qu il donne son accord.<br
												/>
												Cliquez <a href="../index.php">ici</a> pour retourner à la page d\'accueil<br
												/>
												Cliquez <a href="./amis.php">ici</a> pour retourner à la page de gestion
												des amis
									    </p>';
			}
			else
			{
				foreach ($this->errors() as $value) {

					echo $value;
				}

				echo 'Cliquez <a href="./amis.php?action=add">ici</a> pour réessayer</br>';

			}
	}

    
    public function errors()
    {
    	return $this->_errors;
    }

	public function dejaAmi($idAmi , $idMoi)
	{
		$query = $this->_db->prepare('SELECT COUNT(*) AS nbr 
			                          FROM amis 
			                          WHERE (fromt = :idMoi AND toa = :idAmi) 
			                          OR (fromt = :idAmi AND toa = :idMoi)');

		$query->bindValue(':idMoi',$idMoi,PDO::PARAM_INT);
		$query->bindValue(':idAmi', $idAmi, PDO::PARAM_INT);

		$query->execute();
		$deja_ami = $query->fetchColumn();

	    $query->CloseCursor();

	    return $deja_ami;

	}


	public function recupereTousMesAmis($monId)
	{
		$query = $this->_db->prepare('SELECT (fromt + toa - :id) AS idami 
			                          FROM amis 
			                          WHERE (fromt = :id OR toa = :id) AND confirm = :conf ORDER BY dateamitie');

		$query->bindValue(':id',$monId , PDO::PARAM_INT);
		$query->bindValue(':conf','1',PDO::PARAM_STR);
		$query->execute();

		$donnees = $query->fetchAll();
		if(!empty($donnees))
			return $donnees;
		else
			return array();


	}

	public function nombresDeDemandes($id)
	{
		$query = $this->_db->prepare('SELECT COUNT(*) 
			                          FROM amis
				                      WHERE toa = :id AND confirm = :conf');
		$query->bindValue(':id',$id,PDO::PARAM_INT);
		$query->bindValue(':conf','0', PDO::PARAM_STR);
		$query->execute();
		$demande_ami=$query->fetchColumn();

		return $demande_ami;
	}

	public function deleteAmiti($idMoi , $idAmi)
	{
		$query  = $this->_db->prepare('DELETE FROM amis 
			                           WHERE fromt = :idami AND toa = :idmoi');

				$query->bindValue(':idami',$idAmi,PDO::PARAM_INT);
				$query->bindValue(':idmoi',$idMoi,PDO::PARAM_INT);

				$query->execute();
				$query->closeCursor();
	}

	public function confirmerAmitie($idMoi , $idAmi)
	{
		$query = $this->_db->prepare('UPDATE amis SET ami_confirm = :conf WHERE fromt = :idami AND toa = :idmoi');
		$query->bindValue(':conf','1',PDO::PARAM_STR);
        $query->bindValue(':idami',$idAmi,PDO::PARAM_INT);
        $query->bindValue(':idmoi',$idMoi,PDO::PARAM_INT);
	    $query->execute();
		$query->closeCursor();
	}

	public function listesDesDemandes($idMoi)
	{
		$query = $this->_db->prepare('SELECT fromt, dateamitie, pseudo AS idami
			                          FROM amis
					                  LEFT JOIN membres ON id = fromt
					                  WHERE toa = :idmoi AND confirm = :conf
					                  ORDER BY dateamitie DESC');

		$query->bindValue(':idmoi',$idMoi,PDO::PARAM_INT);
		$query->bindValue(':conf','0',PDO::PARAM_STR);
		$query->execute();
		$donnees = $query->fetchAll();

		if(!empty($donnees))
			return $donnees;
		else
			return array();
	}
}