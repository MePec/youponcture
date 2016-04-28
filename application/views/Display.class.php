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
			$this->smarty->display(TPL_DIR."content_accueil.tpl");
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
			$this->smarty->display(TPL_DIR."content_recherche.tpl");
		}
	}
?>