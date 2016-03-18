<?php
	class Engine {
		private $db;
		
	  	/**
	   	 * Constructeur
	   	 * @param db : objet contenant la connexion à la BDD
	   	 */
	  	public function __construct($db){
	       	$this->db = $db;		
	  	}

		// 1ère fonction de Test PDO pour requete SQL
		/* fonction getPathos()
		 Description : permet de récupérer la liste de toutes les pathologies de la BDD
		 Paramètres : ...
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
		 Description : permet de récupérer la liste de toutes les symptomes
		 Paramètres : ...
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
		 Description : permet de récupérer la liste de toutes les méridiens de la BDD
		 Paramètres : aucun
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
		 Description : permet de récupérer la liste des symptoms par mot-clé
		 Paramètres : aucun
		*/
		function getSymptoms_Keywords($keyword) {
			// $sql = "SELECT * FROM symptome 
			// 		WHERE desc LIKE '%".$keyword."%' 
			// 		UNION ALL 
			// 		SELECT * FROM patho
			// 		WHERE desc LIKE '%".$keyword."%' ;";

			$query = $this->db->prepare($sql);
			$query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;	
		}
	}
?>