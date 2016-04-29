<?php
	require_once('Smarty.class.php');
	require_once (CTL_DIR.'Home.class.php');
	require_once (CTL_DIR.'Search.class.php');

	class Display {

		private $smarty;

		public function __construct(){
			$this->smarty = new Smarty();
		}

		public function logonStatus($logonStatus){
			$this->smarty->assign('logon',$logonStatus);
		}

		public function displayHome(Home $home){
			$this->smarty->assign('rss',$home->getRss());
			$this->smarty->display(TPL_DIR."content_home.tpl");
		}

		public function displayMsg($msg){
			$this->smarty->assign('contenu_msg',$msg);
			$this->smarty->display(TPL_DIR."display_msg.tpl");
		}


		public function displaySearch(Search $search){
			$this->smarty->assign('pathology',$search->getPathos());
			$this->smarty->assign('meridiens',$search->getMeridiens());
			$this->smarty->assign('symptoms',$search->getSymptoms());
			if($list_patho_ky = $search->getPathosForKeywords()){
				$this->smarty->assign('patho_ky',$list_patho_ky);
			}
			if($list_patho_main = $search->getPathosForMain()){
				$this->smarty->assign('patho_res',$list_patho_main);
			}
			if($list_sympt_main = $search->getSymptsForMain()){
				$this->smarty->assign('sy_res',$list_sympt_main);
				$this->smarty->assign('nb_sy',sizeof($list_sympt_main));
			}
			$this->smarty->display(TPL_DIR."content_search.tpl");
		}

		/**
		 * Fonction displayCredits
		 * Permet d'afficher la page principale d'informations
		 */
		public function displayCredits(){
			$this->smarty->display(TPL_DIR."content_credits.tpl");
		}

		/**
		 * Fonction display404
		 * Permet d'afficher la page 404
		 */
		public function display404(){
			$this->smarty->display(TPL_DIR."404.tpl");
		}
	}
?>