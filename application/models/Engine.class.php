<?php
	class Engine {
		private $db;
		
	  	/**
	   	 * Constructeur
	   	 * @param db : objet contenant la connexion à la BDD
	   	 */
	  	public function __construct($db){
	       	$this->db = $db->getInstance();	
	  	}


	  	/**
		 * checkIdentity
		 * Permet de vérifier si l'utilisateur est loggué 
		 * @param $login : Login envoyé par le formulaire $_POST
		 * @param $MDP : MDP envoyé par le formulaire $_POST
		 * @result le résultat de la requete
		 */
		public function checkIdentity($login,$MDP){

			// $sql = "SELECT DISTINCT login, 
			// 						MDP
			// 		FROM  Users
			// 		WHERE login = :LOGIN AND 
			// 			  MDP = :MDP";

			$sql = "SELECT DISTINCT login, 
									MDP
					FROM  Users
					WHERE login = :LOGIN";
			
			$query = $this->db->prepare($sql);		
			
			$query->bindValue(':LOGIN', $login, PDO::PARAM_STR);
			// $query->bindValue(':MDP', $MDP, PDO::PARAM_STR);
			
			try{
				$query->execute();
			} catch( PDOException $exception ) {
				print "Echec de l'insertion : " . $exception->getMessage();
			}

			$hashed_pwd = $query->fetchColumn(1);

			if(password_verify($MDP, $hashed_pwd)){
				return $query->rowCount();		
			print "test";
			} else {
				return false;
			}
		}

		/**
		 * signIn
		 * Permet d'insérer les données pour l'inscription d'un membre
		 * @result le résultat de la requete
		 */
		public function signIn($login,$mdp, $nom ,$prenom){

			$sql = "INSERT INTO Users(name, first_name, MDP , login) VALUES (:NOM , :PRENOM , :MDP , :LOGIN)";
			
			$query = $this->db->prepare($sql);		
			$resultats = null;

			$query->bindValue(':LOGIN', $login, PDO::PARAM_STR);
			$query->bindValue(':MDP', password_hash($mdp, PASSWORD_DEFAULT), PDO::PARAM_STR);
			//$query->bindValue(':MDP', $mdp, PDO::PARAM_STR);
			$query->bindValue(':NOM', $nom, PDO::PARAM_STR);
			$query->bindValue(':PRENOM', $prenom, PDO::PARAM_STR);
			
			try{
				$resultats = $query->execute();
			} catch( PDOException $exception ) {
				print "Echec de l'insertion : " . $exception->getMessage();
			}

			//pour tester si l'insertion s'est bien faite
			if($resultats==FALSE){
				return false;
			}else{
				return true;
			}		
		}

		/* fonction getPathos()
		 * Permet de récupérer la liste de toutes les pathologies de la BDD
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
		 * Permet de récupérer la liste de toutes les symptomes
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
		 *Permet de récupérer la liste de toutes les méridiens de la BDD
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
		 * Permet de récupérer la liste des pathologies par mot-clé
		 */
		function getPathos_Keywords($keyword) {
			$sql = "SELECT DISTINCT pat.desc AS Patho,sy.desc AS Symp FROM patho pat
					LEFT JOIN symptPatho sp ON pat.idP = sp.idP 
					LEFT JOIN symptome sy ON sp.idS = sy.idS
					LEFT JOIN keySympt ks ON sy.idS = ks.idS
					LEFT JOIN keywords kw ON kw.idK = ks.idK 
					WHERE kw.name LIKE '".$keyword."' ";
					
			$query = $this->db->prepare($sql);
			$query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;	
		}


		/* fonction getCodeMeridien()
		 * Permet de récupérer le code pour chaque méridiens
		 */
		function getCodeMeridien($merid) {
			$sql = "SELECT code FROM meridien
					WHERE nom LIKE '".$merid."' ";

			$query = $this->db->prepare($sql);
			$query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;	
		}


		/**
		 * getType_Merid
		 * Permet de récupérer le type de meridien en rentrant en paramètre la catégorie de meridien et le caracteristique
		 * @result le résultat de la requete
		 */
		public function getType_Merid($categorie_patho,$caracter) {
			$sql = "SELECT type_mer FROM caracteristiques
					WHERE type_patho = '".$categorie_patho."' AND type_caracteristiques = '".$caracter."' ;";
			
			$query = $this->db->prepare($sql);		
			
			$resultats = $query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;	
		}

		/**
		 * getType_Merid_Default
		 * Permet de récupérer le type de meridien 
		 */
		public function getType_Merid_Default($categorie_patho) {
			$sql = "SELECT type_mer FROM caracteristiques
					WHERE type_patho = '".$categorie_patho."' ;";
			
			$query = $this->db->prepare($sql);		
			
			$resultats = $query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;	
		}

		/* fonction getList_Patho()
		 * Permet de récupérer la liste des pathologie en fonction des 3 critères du premier formulaire
		 */
		function getList_Patho($meridien,$type_mer) {
			$sql = "SELECT p.desc FROM patho p
					WHERE p.mer = :MER AND p.type = :TYPE ;";

			$query = $this->db->prepare($sql);
			
			$query->bindValue(':MER', $meridien);
			$query->bindValue(':TYPE', $type_mer);

			$query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;	
		}

		/* fonction getList_SymptomsByPatho()
		 * Permet de récupérer la liste des psymptomes associés à chaque pathologie en fonction des 3 critères du premier formulaire
		 */
		function getList_SymptomsByPatho($meridien,$type_mer) {
			$sql = "SELECT s.desc FROM symptome s
					LEFT JOIN symptPatho sp ON s.idS = sp.idS
					LEFT JOIN patho p ON sp.idP = p.idP
					WHERE p.mer = :MER AND p.type = :TYPE ;";

			$query = $this->db->prepare($sql);
			
			$query->bindValue(':MER', $meridien);
			$query->bindValue(':TYPE', $type_mer);

			$query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;	
		}
	}
?>