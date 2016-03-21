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


	  	/**
		 * checkIdentity
		 * Permet de vérifier si l'utilisateur est loggué 
		 * @param $login : Login envoyé par le formulaire $_POST
		 * @param $MDP : MDP envoyé par le formulaire $_POST
		 * @result le résultat de la requete
		 */
		public function checkIdentity($login,$MDP){
			$sql = "SELECT DISTINCT login, 
									MDP
					FROM  Users
					WHERE Login = :LOGIN AND 
						  MDP = :MDP";
			
			$query = $this->db->prepare($sql);		
			
			$query->bindValue(':LOGIN', $login);
			$query->bindValue(':MDP', $MDP);
			
			$query->execute();
				
			return $query->rowCount();			
		}

		/**
		 * signIn
		 * Permet d'insérer les données pour l'inscription d'un membre
		 * @result le résultat de la requete
		 */
		public function signIn($login,$mdp, $nom ,$prenom){
			$sql = "INSERT INTO Users(name, prenom, MDP , login) VALUES (:NOM , :PRENOM , :MDP , :LOGIN) ";
			
			$query = $this->db->prepare($sql);		
			
			$query->bindValue(':LOGIN', $login);
			$query->bindValue(':MDP', $mdp);
			$query->bindValue(':NOM', $nom);
			$query->bindValue(':PRENOM', $prenom);
			
			$resultats = $query->execute();

			//pour tester si l'insertion s'est bien faite
			if($resultats===FALSE){
				$data = false;
			}else{
				$data = true;
				return $data;
			}		
		}

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

		/* fonction getPathos_Keywords()
		 Description : permet de récupérer la liste des pathologies par mot-clé
		 Paramètres : aucun
		*/
		function getPathos_Keywords($keyword) {
			$sql = "SELECT * FROM patho pat
					LEFT JOIN symptPatho sp ON pat.idP = sp.idP 
					LEFT JOIN symptome sy ON sp.idS = sy.idS
					LEFT JOIN keysympt ks ON sy.idS = ks.idS
					LEFT JOIN keywords kw ON kw.idK = ks.idK 
					WHERE kw.name LIKE '".$keyword."' ";

			//$sql = "SELECT * FROM patho pat ";

			// $sql = "SELECT * FROM symptome
			// 		WHERE desc LIKE '".$keyword."' ";

			$query = $this->db->prepare($sql);
			$query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;	
		}

		/* fonction getSymptoms_Keywords()
		 Description : permet de récupérer la liste des symptoms comprenant le mot-clé  -  en association avec la fonction getPathos_Keywords
		 Paramètres : aucun
		*/
		function getSymptoms_Keywords($keyword) {
			$sql = "SELECT * FROM symptome sy
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
		 Description : permet de récupérer la liste des pathologie en fonction des 3 critères du premier formulaire
		 Paramètres : aucun
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