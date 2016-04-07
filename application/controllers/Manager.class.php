<?php
	require_once ('Home.class.php');
	require_once ('Search.class.php');
	require_once ('Credits.class.php');
	require_once ('Web_Service.class.php');
	require_once ('ErrorPage.class.php');

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
				//$mdp = shz256($_POST['MDP']);
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
			if($logged == false){
				//Cacher class  recherche par mots clé via CSS => hidden ou none ou via AJAX
				$show_keyword_search = false;


			}
			else{
				// par défaut on cache le formulaire de recherche par mots-clés
				$show_keyword_search = true;
			}


			// a voir si je le laisse ici ou à déplacer				
			//$this->smarty->assign('session',$logon_status);

			switch($page){
				case "1":
				switch($this->section){
						case "1":
							Home::submitSignForm($this->engine);
							break;	

						case "2":
							Home::submitLoginForm($this->engine);
							break;	

						default:
							Home::displayHome($this->smarty);
				}
			
				case "2":
					switch($this->section){
						case "1":
							Search::displaySearch($this->engine, $this->smarty, $show_keyword_search);
							break;	

						case "2":
							Search::submitForm_MainSearch($this->engine, $this->smarty, $show_keyword_search);
							break;

						case "3":
							Search::submitForm_KeywordSearch($this->engine, $this->smarty, $show_keyword_search);
							break;		

						default:
							Search::displaySearch($this->engine, $this->smarty, $show_keyword_search);
					}	

				case "3":
					Credits::displayCredits($this->smarty);
					break;

				case "4":
					ErrorPage::display404($this->smarty);
					break;

				case "5":
					switch($this->section){
						case "1":
							Web_Service::Web_Service_modelView();
							break;	

						case "2":
							Web_Service::Web_Service_modelDownload();
							break;

						case "3":
							Web_Service::Web_Service_modify($this->smarty);
							break;

						default:
							Web_Service::Web_Service_modelView();
					}

				case "6":
					Web_Service::Web_Service_Calculatrice($this->smarty);
					break;

				default:
					Home::displayHome($this->smarty);
			}
		}

	}
?>