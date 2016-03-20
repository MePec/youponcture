<?php
	class Engine {
		private $db;
		
	  	/**
	   	 * Constructeur
	   	 * @param db : objet contenant la connexion � la BDD
	   	 */
	  	public function __construct($db){
	       	$this->db = $db;		
	  	}

		// 1�re fonction de Test PDO pour requete SQL
		/* fonction getPathos()
		 Description : permet de r�cup�rer la liste de toutes les pathologies de la BDD
		 Param�tres : ...
		*/
		function getPathos() {
			$sql = "SELECT * FROM patho ;";

			$query = $this->db->prepare($sql);
			$query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;	
		}

		/* fonction getSymptoms()
		 Description : permet de r�cup�rer la liste de toutes les symptomes
		 Param�tres : ...
		*/
		function getSymptoms() {
			$sql = "SELECT * FROM symptome ;";

			$query = $this->db->prepare($sql);
			$query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;	
		}

		/* fonction getMeridiens()
		 Description : permet de r�cup�rer la liste de toutes les m�ridiens de la BDD
		 Param�tres : aucun
		*/
		function getMeridiens() {
			$sql = "SELECT nom FROM meridien ;";

			$query = $this->db->prepare($sql);
			$query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;	
		}

		/* fonction getSymptoms_Keywords()
		 Description : permet de r�cup�rer la liste des symptoms par mot-cl�
		 Param�tres : aucun
		*/
		function getSymptoms_Keywords($keyword) {
			$sql = "SELECT * FROM patho pat
					LEFT JOIN symptPatho sp ON pat.idP = sp.idP 
					LEFT JOIN symptome sy ON sp.idS = sy.idS
					LEFT JOIN keysympt ks ON sy.idS = ks.idS
					LEFT JOIN keywords kw ON kw.idK = ks.idK 
					WHERE kw.name LIKE '".$keyword."' ";

			$query = $this->db->prepare($sql);
			$query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;	
		}

		/* fonction getList_Patho()
		 Description : permet de r�cup�rer la liste des pathologie en fonction des 3 crit�res du premier formulaire
		 Param�tres : aucun
		*/
		function getList_Patho($patho,$meridien,$caracter) {
			$sql = "SELECT * FROM patho pat
					WHERE type LIKE '%".$meridien."%' ;";

			$query = $this->db->prepare($sql);
			$query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;	
		}
	}
?>