<?php

	require_once(CONF_DIR."DB.class.php");

	class Engine {
		private $db;
		
	  	/**
	   	 * Constructeur : /!\ Deprecated
	   	 * @param db : objet contenant la connexion � la BDD
	   	 */
	  	public function __construct(){
	       	$this->db = DB::getDBH();	
	  	}


	  	/*
	  	 * Fonction qui demande la pr�paration d'un objet PDO
	  	 *
	  	 */
	  	private static function prepareRequest($sql){
	  		$db = DB::getInstance();
	  		return $db->prepareRequestInDB($sql);
	  	}


	  	/*
	  	 * Fonction d'execution des requ�tes avec gestion des erreurs
	  	 *
	  	 */
	    private static function executeResquest(PDOStatement &$sth){
	  		try{
				$sth->execute();
			} catch( PDOException $exception ) {
				trigger_error("Echec de l'insertion : " . $exception->getMessage(), E_USER_WARNING);
			}
	  	} 

	  	/**
		 * checkIdentity
		 * Permet de v�rifier si l'utilisateur est loggu� 
		 * @param $login : Login envoy� par le formulaire $_POST
		 * @param $MDP : MDP envoy� par le formulaire $_POST
		 * @result le r�sultat de la requete
		 */
		public static function checkIdentity($login,$password){

			// $sql = "SELECT DISTINCT login, 
			// 						MDP
			// 		FROM  Users
			// 		WHERE login = :LOGIN AND 
			// 			  MDP = :MDP";

			$sql = "SELECT DISTINCT login, 
									MDP
					FROM  Users
					WHERE login = :LOGIN";
			
			$sth = Self::prepareRequest($sql);		
			
			$sth->bindValue(':LOGIN', $login, PDO::PARAM_STR);
			// $query->bindValue(':PASSWORD', $password, PDO::PARAM_STR);
			
			Self::executeResquest($sth);

			$hashed_pwd = $sth->fetchColumn(1);

			if(password_verify($password, $hashed_pwd)){
				return $sth->rowCount();		
			} else {
				return false;
			}
		}

		/**
		 * signIn
		 * Permet d'ins�rer les donn�es pour l'inscription d'un membre
		 * @result le r�sultat de la requete
		 */
		public static function signIn($login,$password, $nom ,$prenom){

			$sql = "INSERT INTO Users(name, first_name, MDP , login) VALUES (:NOM , :PRENOM , :PASSWORD , :LOGIN)";
			
			$sth = Self::prepareRequest($sql);		

			$sth->bindValue(':LOGIN', $login, PDO::PARAM_STR);
			$sth->bindValue(':PASSWORD', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
			//$query->bindValue(':MDP', $mdp, PDO::PARAM_STR);
			$sth->bindValue(':NOM', $nom, PDO::PARAM_STR);
			$sth->bindValue(':PRENOM', $prenom, PDO::PARAM_STR);
			
			Self::executeResquest($sth);

			//pour tester si l'insertion s'est bien faite
			if($sth==FALSE){
				return false;
			}else{
				return true;
			}		
		}

		/* fonction getPathos()
		 * Permet de r�cup�rer la liste de toutes les pathologies de la BDD
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
		 * Permet de r�cup�rer la liste de toutes les symptomes
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
		 *Permet de r�cup�rer la liste de toutes les m�ridiens de la BDD
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
		 * Permet de r�cup�rer la liste des pathologies par mot-cl�
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
		 * Permet de r�cup�rer le code pour chaque m�ridiens
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
		 * Permet de r�cup�rer le type de meridien en rentrant en param�tre la cat�gorie de meridien et le caracteristique
		 * @result le r�sultat de la requete
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
		 * Permet de r�cup�rer le type de meridien 
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
		 * Permet de r�cup�rer la liste des pathologie en fonction des 3 crit�res du premier formulaire
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
		 * Permet de r�cup�rer la liste des psymptomes associ�s � chaque pathologie en fonction des 3 crit�res du premier formulaire
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