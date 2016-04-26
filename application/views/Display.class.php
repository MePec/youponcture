<?php
	require_once('Smarty.class.php');
	require_once (CTL_DIR.'Home.class.php');

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
	}
?>