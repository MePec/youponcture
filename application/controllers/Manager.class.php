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

				case "4":
					$this->display404();
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
			//fonction Affichage flux RSS

			// Flux RSS source
			// $url_feed = "http://www.pcastuces.com/xml/pca_logitheque.xml";
			$url_feed = "http://www.medecine-globale.ch/feed/?post_type=listing_type";

			// Nombre d'éléments à afficher
			$nb_items = 2;

			// Lecture du flux distant
			$rss = fetch_rss($url_feed);

			// Lecture des éléments
			if (is_array($rss->items))
			{
				// On récupère les éléments les plus récents
				// fonction array_slice = permet d'extraire une portion de tableau/ ici 0 est l'indice de début
				$items = array_slice($rss->items, 0, $nb_items);
				$cpt = 0; $list_rss = Array();

				// Boucle sur tous les éléments
				foreach ($items as $val) {
					$list_rss[$cpt]['ITEM_TITLE'] = utf8_encode($val['title']);
					$list_rss[$cpt]['ITEM_LINK'] = utf8_encode($val['link']);					
					// $list_rss[$cpt]['ITEM_DATE'] = $val['pubDate'];
					$list_rss[$cpt]['ITEM_DESCRIPTION'] = utf8_encode($val['description']);
					
					$cpt++;
				}
			}

			// affecte les valeurs de RSS pour affichage sur la page
			$this->smarty->assign('rss',$list_rss);

			$this->smarty->display(TPL_DIR."content_accueil.tpl");	
		}	
		
		/**
		 * Fonction display404
		 * Permet d'afficher la page 404
		 */
		public function display404(){	
			$this->smarty->display(TPL_DIR."404.tpl");	
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
			if($nb_sympt > 0) {								
				for($i = 0; $i < $nb_sympt; $i++){	
					$list_sympt[$i]['SYMPT_DESC'] = $data_sympt[$i]['desc'];
				}
			}
			$this->smarty->assign('symptoms',$list_sympt);


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
					header('Location: index.php?p=4'); //prévoir message d'erreur
				}
				

				if($data_send == true){
					$key = $_POST['keywords'];

					// récupération des résultats de pathologie selon le mots clé
					$result_path_ky = $this->engine->getPathos_Keywords($key);
					$data_patho_ky = $result_path_ky['data'];
					$nb_patho_ky = $result_path_ky['nb'];

					$list_patho_ky = array();

					if($nb_patho_ky > 0) {								
						for($i = 0; $i < $nb_patho_ky; $i++){	
							$list_patho_ky[$i]['PATHOS'] = $data_patho_ky[$i]['Patho'];
							$list_patho_ky[$i]['SYMPT'] = $data_patho_ky[$i]['Symp'];
						}
					}
					if($list_patho_ky != null){
						$this->smarty->assign('patho_ky',$list_patho_ky);
					}
					else{
						$msg[0]['PATHOS'] = 'Pas de résultats trouvés pour cette recherche';
						$msg[0]['SYMPT'] = 'Pas de résultats trouvés pour cette recherche';
						$this->smarty->assign('patho_ky',$msg);		
					}

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
				else{
					$data_send = false;
					header('Location: index.php?p=4');
					//prévoir message d'erreur
				}

				if($data_send == true){
					$categorie_patho = $_POST['type_patho'];
					$caracter = $_POST['caracteristiques_meridien'];
					$cpt = 0;
					$list_patho = array();
					$list_sy = array();
					if($caracter == "default"){
						$data_type_mer = $this->engine->getType_Merid_Default($categorie_patho);
						$nb_data_type_mer = $data_type_mer['nb'];
						if($nb_data_type_mer > 0){
							for($cpt = 0;$cpt < $nb_data_type_mer;$cpt++){
								$type_mer[$cpt] = $data_type_mer['data'][$cpt]['type_mer'];
							}
						}
					}
					else {
						$data_type_mer = $this->engine->getType_Merid($categorie_patho,$caracter);
						$type_mer[0] = $data_type_mer['data'][0]['type_mer'];
						$cpt = 1;
					}

					// pour selection multiple Meridien
					$j = 0;
					foreach($_POST['type_meridien'] as $val){   
						$data_meridien = $this->engine->getCodeMeridien($val);
						$meridien[$j] = $data_meridien['data'][0]['code'] ;
						$j++;
					}

					// sans selection multiple
					// $meridien = $_POST['type_meridien'];
					// $meridien = implode($meridien);
					// $data_meridien = $this->engine->getCodeMeridien($meridien);
					// $meridien = $data_meridien['data'][0]['code'] ;

					// pour selection multiple Meridien
					$id = 0;
					$mark = 0;
					for($i = 0; $i < $cpt; $i++){
						for($l = 0;$l < $j;$l++){
							$result_path = $this->engine->getList_Patho($meridien[$l],$type_mer[$i]);
							// $data_path[$i][$l] = $result_path['data'];
							// $nb_path[$i][$l] = $result_path['nb'];							
							$data_path[$mark] = $result_path['data'];
							$nb_path[$mark]= $result_path['nb'];
							$mark++;
						}
					$id = $i+1;
					}

					// sans selection multiple
					// $id = 0;
					// for($i = 0; $i < $cpt; $i++){	
					// 	$result_path = $this->engine->getList_Patho($meridien,$type_mer[$i]);
					// 	$data_path[$i] = $result_path['data'];
					// 	$nb_path[$i] = $result_path['nb'];
					// 	$id = $i+1;
					// }

					$tmp = 0;
					// pour sélection de toutes les méridiens en paramètres - sans distinction intern,externe,... - sans prise en charge selection multiple des méridiens
					// for($k = 0;$k < $id;$k++) {
					// 	if($nb_path[$k] > 0)
					// 		for($p = 0; $p < $nb_path[$k]; $p++){	
					// 			$list_patho[$tmp]['RESULT_PATHO'] = $data_path[$k][$p]['desc'];
					// 			$tmp++;		
					// 		}
					// }

					$nb_results = sizeof($nb_path);
					// for($p = 0; $p < $nb_path[$kl]; $p++){
					// test pour selection multiple Meridien
					for($kl = 0;$kl < $nb_results;$kl++) {
						if($nb_path[$kl] > 0){
							for($p = 0; $p < $nb_path[$kl]; $p++){				
											$list_patho[$tmp]['RESULT_PATHO'] = $data_path[$kl][$p]['desc'];
											$tmp++;		
	
							}
						}
					}	

					if($list_patho != null){
						$this->smarty->assign('patho_res',$list_patho);
					}
					else{
						$msg[0]['RESULT_PATHO'] = "Pas de résultats trouvés pour cette recherche";
						$this->smarty->assign('patho_res',$msg);
					}


					// récupération données symptome + affichage 
					// $result_sy = $this->engine->getList_SymptomsByPatho($meridien[$l],$type_mer[$i]);
					// $data_sy[$l] = $result_sy['data'];
					// $nb_sy[$l] = $result_sy['nb'];

					
					// for($k = 0;$k < $l;$k++) {
					// 	if($nb_path[$k] > 0) {						
					// 		for($i = 0; $i < $nb_path[$k]; $i++){	
					// 			$list_sy[$k][$i]['RESULT_PATHO'] = $data_sy[$k][$i]['desc'];
					// 		}
					// 	}
					// }

					if($list_patho != null){
						$this->smarty->assign('sy_res',$list_sy);
					}
					else{
						$msg[0]['RESULT_SY'] = "Pas de résultats trouvés pour cette recherche";
						$this->smarty->assign('sy_res',$msg);
					}

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