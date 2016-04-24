<?php

	require_once('Smarty.class.php');
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
		private $db;
		private $engine;

		private $section;
		private $page;
		private $display;
		
		/**
		 * Constructeur
		 */
		public function __construct($page = null , $section = null){
			$classDB = new DB();
			
			$this->db = $classDB->getInstance();
	       	$this->engine = new Engine($this->db);
	       	$this->smarty = new Smarty();
			
			$this->page = $page;
			$this->section = $section;
			$this->display = new Display();

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

			$this->smarty->assign('logon',$_SESSION['logon_status']);

			switch($page){
				case "1":
				$home = new Home();
				switch($this->section){
						case "1":
							//Home::submitSignForm($this->engine,$this->smarty);
							$home->submitSignForm($this->engine);
							$this->display->displayMsg($home->getMsg());
							break;	

						case "2":
							//Home::submitLoginForm($this->engine);
							$home->submitLoginForm($this->engine);
							$this->display->displayMsg($home->getMsg());
							break;	

						default:
							//Home::displayHome($this->smarty);
							$list_rss = $home->getRss();
							$this->display->displayHome($list_rss);       
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
					//Home::displayHome($this->smarty);
					$home = new Home();
					$list_rss = $home->getRss();
					$this->display->displayHome($list_rss);
			}


		}

	}
?>