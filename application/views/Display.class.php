<?php
	require_once('Smarty.class.php');

	class Display {

		private $smarty;

		public function __construct(){
			$this->smarty = new Smarty();
		}

		public function displayHome(array $list_rss){
			$this->smarty->assign('rss',$list_rss);
			$this->smarty->display(TPL_DIR."content_accueil.tpl");
		}

		public function displayMsg($msg){
			$this->smarty->assign('contenu_msg',$msg);
			$this->smarty->display(TPL_DIR."display_msg.tpl");
		}
	}
?>