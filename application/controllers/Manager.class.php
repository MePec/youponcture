<?php

	require_once("../application/config/config.php");
	require_once(VIEW_DIR."Display.class.php");
	require_once ('Home.class.php');
	require_once ('Search.class.php');
	require_once ('Web_Service.class.php');
	require_once ('ErrorPage.class.php');
	require_once ('Member.class.php');

	class Manager {

		private $display;
		private $section;
		private $page;
		
		/**
		 * Constructeur
		 */
		public function __construct($page = null , $section = null){
			
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
				switch($this->section){
						case "1":
							Home::submitSignForm($msg);
							$this->display->displayMsg($msg);
							break;	

						case "2":
							Home::submitLoginForm($msg);
							$this->display->displayMsg($msg);
							break;	

						default:
							$this->display->displayHome(new Home());       
				}
				break;

				case "2":
					$search = new Search();
					switch($this->section){
						case "1":
							$this->display->displaySearch($search);
							break;	

						case "2":
							$search->submitForm_MainSearch();
							$this->display->displaySearch($search);
							break;

						case "3":
							$search->submitForm_KeywordSearch();
							$this->display->displaySearch($search);
							break;		

						default:
							$this->display->displaySearch($search);
					}	
				break;
				
				case "3":
					$this->display->displayCredits();
					break;

				case "4":
					if(!ErrorPage::isUrlExist())
						$this->display->display404();
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
							Web_Service::Web_Service_modify();
							break;

						default:
							Web_Service::Web_Service_modelView();
					}

				case "6":
					Web_Service::Web_Service_Calculatrice();
					break;

				case "7":
					Member::Member_connection();
					break;

				default:
					$this->display->displayHome(new Home());
			}


		}

	}
?>