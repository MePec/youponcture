<?php

	require_once(CONF_DIR."DB.class.php");

	class Engine {

		const PATHOLOGIES = 0;
		const MERIDIENS = 1;
		const SYMPTOMS =  2;
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
				return $sth->execute();
			} catch( PDOException $exception ) {
				trigger_error("Echec de la requ�te : " . $exception->getMessage(), E_USER_WARNING);
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
			// 						password
			// 		FROM  Users
			// 		WHERE login = :LOGIN AND 
			// 			  MDP = :MDP";

			$sql = "SELECT password
					FROM  Users
					WHERE login = :LOGIN";
			
			$sth = self::prepareRequest($sql);		
			
			$sth->bindValue(':LOGIN', $login, PDO::PARAM_STR);
			// $query->bindValue(':PASSWORD', $password, PDO::PARAM_STR);
			
			self::executeResquest($sth);

			$hashed_pwd = $sth->fetchColumn();

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
		public static function signIn($login, $password, $name ,$first_name){

			$sql = "INSERT INTO Users(name, first_name, password , login) VALUES (:NAME , :FIRST_NAME , :PASSWORD , :LOGIN)";
			
			$sth = self::prepareRequest($sql);
			
			$hashed_pwd = password_hash($password, PASSWORD_DEFAULT);	

			$sth->bindValue(':LOGIN', $login, PDO::PARAM_STR);
			$sth->bindValue(':PASSWORD', $hashed_pwd, PDO::PARAM_STR);
			//$query->bindValue(':PASSWORD', $mdp, PDO::PARAM_STR);
			$sth->bindValue(':NAME', $name, PDO::PARAM_STR);
			$sth->bindValue(':FIRST_NAME', $name, PDO::PARAM_STR);
			
			$status = self::executeResquest($sth);

			//pour tester si l'insertion s'est bien faite
			return $status;		
		}

		/* fonction requestList()
		 *Permet de r�cup�rer la liste de toutes les pathologies, m�ridiens ou sympt�mes de la BDD
		 */
		public static function requestList($list_name) {

			switch($list_name){
				case self::PATHOLOGIES:
					$sql = "SELECT `desc` FROM patho ;";
					$column = "desc";
					break;
				case self::MERIDIENS:
					$sql = "SELECT nom FROM meridien ;";
					$column = "nom";
					break;
				case self::SYMPTOMS:
					$sql = "SELECT `desc` FROM symptome ;";
					$column = "desc";
					break;
				default:
					trigger_error("Unknown list_name: ". $list_name, E_USER_ERROR);
			}

			$query = self::prepareRequest($sql);
			self::executeResquest($query);

			$data = $query->fetchAll(PDO::FETCH_ASSOC);
			$nb = $query->rowCount();
				
			$list = array();

			// Assignation
			if($nb > 0) {								
				for($row = 0; $row < $nb; $row++){
					$list[$row]['DESC'] = $data[$row][$column];
				}
			}

			return $list;
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

		/* fonction requestPathos_Keywords()
		 * Permet de r�cup�rer la liste des pathologies par mot-cl�
		 */
		public static function requestPathos_Keywords($keyword) {
			$sql = "SELECT DISTINCT pat.desc AS Patho,sy.desc AS Symp FROM patho pat
					LEFT JOIN symptPatho sp ON pat.idP = sp.idP 
					LEFT JOIN symptome sy ON sp.idS = sy.idS
					LEFT JOIN keySympt ks ON sy.idS = ks.idS
					LEFT JOIN keywords kw ON kw.idK = ks.idK 
					WHERE kw.name LIKE '".$keyword."' ";
					
			$query = self::prepareRequest($sql);
			self::executeResquest($query);

			$data_patho_ky = $query->fetchAll(PDO::FETCH_ASSOC);
			$nb_patho_ky = $query->rowCount();

			$list_patho_ky = array();

			if($nb_patho_ky > 0) {								
				for($row = 0; $row < $nb_patho_ky; $row++){	
					$list_patho_ky[$row]['PATHOS'] = $data_patho_ky[$row]['Patho'];
					$list_patho_ky[$row]['SYMPT'] = $data_patho_ky[$row]['Symp'];
				}
			}
				
			return $list_patho_ky;	
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