<?php

	require_once("../application/config/config.php");
	require_once(CONF_DIR."DB.class.php");
	require_once(MDL_DIR."Engine.class.php");
	require_once ('Home.class.php');
	require_once ('Search.class.php');
	require_once ('Credits.class.php');
	require_once ('Web_Service.class.php');
	require_once ('ErrorPage.class.php');
	require_once ('Member.class.php');
	require_once(VIEW_DIR."Display.class.php");

	class Manager {

		private $classDB;
		private $display;

		private $section;
		private $page;
		
		/**
		 * Constructeur
		 */
		public function __construct($page = null , $section = null){
			
			$this->classDB = new DB();
			$this->display = new Display();
			
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

			$this->display->logonStatus($_SESSION['logon_status']);

			switch($page){
				case "1":
				$home = new Home();
				switch($this->section){
						case "1":
							$home->submitSignForm($this->classDB);
							$this->display->displayMsg($home->getMsg());
							break;	

						case "2":
							$home->submitLoginForm($this->classDB);
							$this->display->displayMsg($home->getMsg());
							break;	

						default:
							$list_rss = $home->getRss();
							$this->display->displayHome($list_rss);       
				}
				break;

				case "2":
					$this->db = $classDB->getInstance();
	       			$this->engine = new Engine($this->db);
	       			$this->smarty = new Smarty();
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
					$this->smarty = new Smarty();
					Credits::displayCredits($this->smarty);
					break;

				case "4":
					$this->smarty = new Smarty();
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
							$this->smarty = new Smarty();
							Web_Service::Web_Service_modify($this->smarty);
							break;

						default:
							Web_Service::Web_Service_modelView();
					}

				case "6":
					$this->smarty = new Smarty();
					Web_Service::Web_Service_Calculatrice($this->smarty);
					break;

				case "7":
					Member::Member_connection();
					break;

				default:
					$home = new Home();
					$list_rss = $home->getRss();
					$this->display->displayHome($list_rss);
			}


		}

	}
?>