<?php
	require_once ('Home.class.php');
	require_once ('Search.class.php');
	require_once ('Credits.class.php');
	require_once ('Web_Service.class.php');
	require_once ('ErrorPage.class.php');
	require_once ('Member.class.php');

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
			
			// Vérification si on est déjà logué
			if(!isset($_SESSION['Logged']) && empty($_SESSION['Logged'])){
				$logged = false;
				$_SESSION['Logged'] = false;
				$_SESSION['logon_status'] = "Non connecté";
			}
			elseif(isset($_SESSION['Logged']) && $_SESSION['Logged'] != true){
				$_SESSION['logon_status'] = "Non connecté";
			}
			elseif(isset($_SESSION['Logged']) && $_SESSION['Logged'] == true){
				$_SESSION['logon_status'] = "Connecté";
			}
			
			// var_dump($_SESSION['Logged']);

			// a voir si je le laisse ici ou à déplacer	: pour indiquer sur toutes les pages si on est connectés			
			// normalement $_SESSION['logon_status'] toujours définie mais vérifier avant
			$this->smarty->assign('logon',$_SESSION['logon_status']);

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
				break;

				case "2":
					switch($this->section){
						case "1":
							Search::displaySearch($this->engine, $this->smarty);
							break;	

						case "2":
							Search::submitForm_MainSearch($this->engine, $this->smarty);
							break;

						case "3":
							Search::submitForm_KeywordSearch($this->engine, $this->smarty);
							break;		

						default:
							Search::displaySearch($this->engine, $this->smarty);
					}	
				break;
				
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

				case "7":
					Member::Member_connection();
					break;

				default:
					Home::displayHome($this->smarty);
			}
		}

	}
?>