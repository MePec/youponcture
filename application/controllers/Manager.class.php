<?php
	class Manager {
		private $db;
		private $engine;
		
		private $section;
		private $page;
		
		/**
		 * Constructeur
		 */
		public function __construct($page = null , $section = null){
			$classDB = new DB();
			
			$this->db = $classDB->getInstance();
	       	$this->engine = new Engine($this->db);
	       	$this->smarty = new Smarty();
	       	//$this->template = new Template(TPL_DIR);
			
			$this->page = $page;
			$this->section = $section;
			
			$logged = false;
				
			// Controle du Login et MDP lors de la connexion
			if (isset($_POST['login']) && isset($_POST['mdp'])) {
				//$mdp = md5($_POST['MDP']);
				$mdp = $_POST['mdp'];
				
				$logged = $this->engine->checkIdentity($_POST['login'], $mdp);
				
				if (isset($_POST['connexion']) && ($logged != true)) {
					$_SESSION['login'] = $_POST['login'];
					$_SESSION['mdp'] = $mdp;	
					$_SESSION['Logged'] = true;			
					$_SESSION['logon_status'] = "Connecté";
				}
				else{
					$_SESSION['Logged'] = false;
					$_SESSION['logon_status'] = "Non connecté";
				}
			}
				
			if(isset($_SESSION['Logged']) && $_SESSION['Logged'] != true){
				$logged = false; // faire avec $_SESSION['Logged'] sinon (si probleme de visibilité de la variable)
				$_SESSION['logon_status'] = "Non connecté";
			}
			//penser à faire : si pas loggé : pas afficher rechercher par mots-clés dans page Recherches


			// a voir si je le laisse ici ou à déplacer				
			//$this->smarty->assign('session',$logon_status);

			switch($page){
				case "1":
				switch($this->section){
						case "1":
							$this->submitSignForm();
							break;	

						case "2":
							$this->submitLoginForm();
							break;	

						default:
							$this->displayHome();
				}
			
				case "2":
					switch($this->section){
						case "1":
							$this->displayRecherches();
							break;	

						case "2":
							$this->submitForm_MainRecherches();
							break;

						case "3":
							$this->submitForm_KeywordRecherches();
							break;		

						default:
							$this->displayRecherches();
					}	

				case "3":
					$this->displayCredits();
					break;

				default:
					$this->displayHome();
			}
		}
		
		/**
		 * Fonction displayHome
		 * Permet d'afficher la page d'accueil
		 */
		public function displayHome(){	
			$this->smarty->display(TPL_DIR."content_accueil.tpl");	
		}	
		

		/**
		 * Fonction submitLoginForm
		 * Permet de soumettre le formulaire de connexion et de le valider/vérifier
		 */
		public function submitLoginForm(){
			// Controle du Login et MDP lors de la connexion
			if (isset($_POST['login']) && isset($_POST['mdp'])) {
				//$mdp = md5($_POST['MDP']);
				$mdp = $_POST['mdp'];
				
				$logged = $this->engine->checkIdentity($_POST['login'], $mdp);
				
				if (isset($_POST['connexion']) && ($logged != true)) {
					$_SESSION['login'] = $_POST['login'];
					$_SESSION['mdp'] = $mdp;	
					$_SESSION['Logged'] = true;	
					header('Location: index.php?p=2');		
				}
				else {
					$_SESSION['Logged'] = false;
					header('Location: index.php?p=1'); 
				}
			}
			else
				header('Location: index.php?p=1'); 
				//prévoir message d'erreur		
		}	

		/**
		 * Fonction submitSignForm
		 * Permet de soumettre le formulaire d'inscription et de le valider/vérifier
		 */
		public function submitSignForm(){
			if (isset($_POST['send_signup']) && !empty($_POST['send_signup']))
				$data_send = true;
			else {
				$data_send = false;
				header('Location: index.php?p=1'); 
				// prévoir fonction php de gestion de message d'erreur qui renvoit une alerte JS
			}

			if ($data_send == true) {
				//$mdp = md5($_POST['MDP']);
				$login = $_POST['login'];
				$mdp = $_POST['mdp'];
				$mdp_bis = $_POST['mdp_bis'];
				$nom = $_POST['nom'];
				$prenom = $_POST['prenom'];
				//$email = $_POST['email'];
				
				// vérification de la sdouble saisie du mdp
				if($mdp == $mdp_bis){

					$sign_in = $this->engine->signIn($login, $mdp, $nom , $prenom);
					// $this->engine->signIn($login, $mdp, $nom , $prenom);
				
					// message si erreur dans l'insertion en BDD
					if ($sign_in == false)  {
						header('Location: index.php?p=1');
					}
					else{
						// insertion s'est bien passé
					}

					// penser à prévoir messsage JS (ou insertion en PHP  en dur) pour confirmer l'inscription quand pas de problèmes
					header('Location: index.php?p=3');

				}
				else{
					header('Location: index.php?p=1');
					// message d'avertissement : MDP non égaux

				}
			}
	
		}

		/**
		 * Fonction displayRecherches
		 * Permet d'afficher la page principale de recherches (sans traitements)
		 */
		public function displayRecherches(){

			$resultat_patho = $this->engine->getPathos();
			$data_patho = $resultat_patho['data'];
			$nb_patho = $resultat_patho['nb'];

			$resultat_merid = $this->engine->getMeridiens();
			$data_merid = $resultat_merid['data'];
			$nb_merid = $resultat_merid['nb'];

			$resultat_sympt = $this->engine->getSymptoms();
			$data_sympt = $resultat_sympt['data'];
			$nb_sympt = $resultat_sympt['nb'];

			$list_patho = array();
			$list_merid = array();
			$list_sympt = array();

			if($nb_patho > 0) {								
				for($i = 0; $i < $nb_patho; $i++){	
					$list_patho[$i]['PATHO_DESC'] = $data_patho[$i]['desc'];
				}
			}
			// else {
			// 	$smarty->assign('pathology',array());
			// }
			$this->smarty->assign('pathology',$list_patho);

			// Assignation Méridiens
			if($nb_merid > 0) {								
				for($i = 0; $i < $nb_merid; $i++){	
					$list_merid[$i]['MERID_DESC'] = $data_merid[$i]['nom'];
				}
			}
			$this->smarty->assign('meridiens',$list_merid);


			// Assignation Symptomes
			// if($nb_sympt > 0) {								
			// 	for($i = 0; $i < $nb_sympt; $i++){	
			// 		$list_sympt[$i]['SYMPT_DESC'] = $data_sympt[$i]['desci'];
			// 	}
			// }
			// $this->smarty->assign('symptoms',$list_sympt);


			$this->smarty->display(TPL_DIR."content_recherche.tpl");
		}

		/**
		 * Fonction submitForm_KeywordRecherches
		 * Permet de calculer et d'afficher les résultats d'une requete de recherche par mot clés
		 */
		public function submitForm_KeywordRecherches(){
				if (isset($_POST['keywords']) && !empty($_POST['keywords']))
					$data_send = true;
				else{
					$data_send = false;
					header('Location: index.php?p=2'); //prévoir message d'erreur
				}
				

				if($data_send == true){
					$key = $_POST['keywords'];

					$result_sympt_ky = $this->engine->getSymptoms_Keywords($key);
					$data_sympt_ky = $result_sympt_ky['data'];
					$nb_sympt_ky = $result_sympt_ky['nb'];

					$list_sympt_ky = array();

					if($nb_sympt_ky > 0) {								
						for($i = 0; $i < $nb_sympt_ky; $i++){	
							$list_sympt_ky[$i]['SYMPTOMS'] = $data_sympt_ky[$i]['desc'];
						}
					}
					$this->smarty->assign('symptoms_ky',$list_sympt_ky);

				}		

			$this->displayRecherches();	

			$this->smarty->display(TPL_DIR."content_recherche.tpl");		
		}	

		/**
		 * Fonction submitForm_MainRecherches
		 * Permet de calculer et d'afficher les résultats d'une requete de recherche par critère
		 */
		public function submitForm_MainRecherches(){
				if (isset($_POST['type_patho']) && !empty($_POST['type_patho']) 
					&& isset($_POST['type_meridien']) && !empty($_POST['type_meridien']) 
					&& isset($_POST['caracteristiques_meridien']) && !empty($_POST['caracteristiques_meridien']) )
					$data_send = true;
				else
					$data_send = false;
					header('Location: index.php?p=2');
					//prévoir message d'erreur

				if($data_send == true){
					$patho = $_POST['type_patho'];
					$meridien = $_POST['type_meridien'];
					$caracter = $_POST['caracteristiques_meridien'];

					$result_path = $this->engine->getList_Patho($patho,$meridien,$caracter);
					$data_path = $result_path['data'];
					$nb_path = $result_path['nb'];

					$list_patho = array();

					if($nb_path > 0) {								
						for($i = 0; $i < $nb_path; $i++){	
							$list_patho[$i]['SYMPTOMS'] = $data_path[$i]['desci'];
						}
					}
					$this->smarty->assign('pathos',$list_patho);

				}

			$this->displayRecherches();	

			$this->smarty->display(TPL_DIR."content_recherche.tpl");		
		}	


		/**
		 * Fonction displayCredits
		 * Permet d'afficher la page principale d'infos
		 */
		public function displayCredits(){
			$this->smarty->display(TPL_DIR."content_infos.tpl");		
		}


	}
?>