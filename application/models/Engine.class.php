<?php

	require_once(CONF_DIR."DB.class.php");

	class Engine {

		const PATHOLOGIES = 0;
		const MERIDIENS = 1;
		const SYMPTOMS =  2;
		private $db;
		
	  	/**
	   	 * Constructeur : /!\ Deprecated
	   	 * @param db : objet contenant la connexion à la BDD
	   	 */
	  	public function __construct(){
	       	$this->db = DB::getDBH();	
	  	}


	  	/*
	  	 * Fonction qui demande la préparation d'un objet PDO
	  	 *
	  	 */
	  	private static function prepareRequest($sql){
	  		$db = DB::getInstance();
	  		return $db->prepareRequestInDB($sql);
	  	}


	  	/*
	  	 * Fonction d'execution des requêtes avec gestion des erreurs
	  	 *
	  	 */
	    private static function executeResquest(PDOStatement &$sth){
	  		try{
				return $sth->execute();
			} catch( PDOException $exception ) {
				trigger_error("Echec de la requête : " . $exception->getMessage(), E_USER_WARNING);
			}
	  	} 

	  	/**
		 * checkIdentity
		 * Permet de vérifier si l'utilisateur est loggué 
		 * @param $login : Login envoyé par le formulaire $_POST
		 * @param $MDP : MDP envoyé par le formulaire $_POST
		 * @result le résultat de la requete
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
		 * Permet d'insérer les données pour l'inscription d'un membre
		 * @result le résultat de la requete
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
		 *Permet de récupérer la liste de toutes les pathologies, méridiens ou symptômes de la BDD
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

		/* fonction requestPathos_Keywords()
		 * Permet de récupérer la liste des pathologies par mot-clé
		 */
		public static function requestPathos_Keywords($keyword) {
			$sql = "SELECT DISTINCT pat.desc AS Patho,sy.desc AS Symp FROM patho pat
					LEFT JOIN symptPatho sp ON pat.idP = sp.idP 
					LEFT JOIN symptome sy ON sp.idS = sy.idS
					LEFT JOIN keySympt ks ON sy.idS = ks.idS
					LEFT JOIN keywords kw ON kw.idK = ks.idK 
					WHERE kw.name LIKE :KEYWORD ";
					
			$query = self::prepareRequest($sql);

			$query->bindValue(':KEYWORD', $keyword, PDO::PARAM_STR);

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


		/* fonction requestCodeMeridien()
		 * Permet de récupérer le code pour chaque méridiens
		 */
		public static function requestCodeMeridien(array $list_type) {
			$sql = "SELECT code FROM meridien
					WHERE nom LIKE :TYPE ";

			$query = self::prepareRequest($sql);

			$query->bindParam(':TYPE', $type, PDO::PARAM_STR);

			$i = 0;
			foreach($list_type as $type){
				self::executeResquest($query);
				$data_meridien = $query->fetchAll(PDO::FETCH_ASSOC);
				$meridien[$i] = $data_meridien[0]['code'] ;
				$i++;
			}
				
			return $meridien;	
		}


		/**
		 * requestType_Merid
		 * Permet de récupérer le type de meridien en rentrant en paramètre la catégorie de meridien et le caracteristique
		 * @result le résultat de la requete
		 */
		public static function requestType_Merid($categorie_patho,$caracter) {
			$sql = "SELECT type_mer FROM caracteristiques
					WHERE type_patho = :CATEGORIE_PATHO AND type_caracteristiques = :CARACTER ;";
			
			$query = self::prepareRequest($sql);

			$query->bindValue(':CATEGORIE_PATHO', $categorie_patho, PDO::PARAM_STR);
			$query->bindValue(':CARACTER', $caracter, PDO::PARAM_STR);	
			
			self::executeResquest($query);

			$data_type_mer = $query->fetchAll(PDO::FETCH_ASSOC);

			$type_mer[0] = $data_type_mer[0]['type_mer'];

			return $type_mer;	
		}

		/**
		 * requestType_Merid_Default
		 * Permet de récupérer le type de meridien 
		 */
		public static function requestType_Merid_Default($categorie_patho) {
			$sql = "SELECT type_mer FROM caracteristiques
					WHERE type_patho = :CATEGORIE_PATHO ;";
			
			$query = self::prepareRequest($sql);

			$query->bindValue(':CATEGORIE_PATHO', $categorie_patho, PDO::PARAM_STR);

			self::executeResquest($query);

			$data_type_mer = $query->fetchAll(PDO::FETCH_ASSOC);
			$nb_data_type_mer = $query->rowCount();

			if($nb_data_type_mer > 0){
				for($row = 0;$row < $nb_data_type_mer;$row++){
					$type_mer[$row] = $data_type_mer[$row]['type_mer'];
				}
			}
				
			return $type_mer;	
		}

		/* fonction requestList_Patho()
		 * Permet de récupérer la liste des pathologie en fonction des 3 critères du premier formulaire
		 */
		public static function requestList_Patho(array $list_meridien,array $list_type) {
			$sql = "SELECT p.desc FROM patho p
					WHERE p.mer = :MERIDIEN AND p.type = :TYPE ;";

			$query = self::prepareRequest($sql);
			
			$query->bindParam(':MERIDIEN', $meridien, PDO::PARAM_STR);
			$query->bindParam(':TYPE', $type, PDO::PARAM_STR);

			$i=0;
			foreach ($list_type as $type) {
				foreach ($list_meridien as $meridien) {
					self::executeResquest($query);
					$list_patho[$i] = $query->fetchAll(PDO::FETCH_ASSOC);		
					$i++;		
				}
			}
				
			return $list_patho;	
		}

		/* fonction requestList_SymptomsByPatho()
		 * Permet de récupérer la liste des psymptomes associés à chaque pathologie en fonction des 3 critères du premier formulaire
		 */
		public static function requestList_SymptomsByPatho(array $list_meridien,array $list_type) {
			$sql = "SELECT s.desc FROM symptome s
					LEFT JOIN symptPatho sp ON s.idS = sp.idS
					LEFT JOIN patho p ON sp.idP = p.idP
					WHERE p.mer = :MERIDIEN AND p.type = :TYPE ;";

			$query = self::prepareRequest($sql);
			
			$query->bindParam(':MERIDIEN', $meridien, PDO::PARAM_STR);
			$query->bindParam(':TYPE', $type, PDO::PARAM_STR);

			$i=0;
			foreach ($list_type as $type) {
				foreach ($list_meridien as $meridien) {
					self::executeResquest($query);
					$list_sympt[$i]= $query->fetchAll(PDO::FETCH_ASSOC);
					$i++;
				}
			}
				
			return $list_sympt;	
		}
	}
?>