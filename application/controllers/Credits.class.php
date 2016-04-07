<?php
    class Credits {

		/**
		 * Fonction displayCredits
		 * Permet d'afficher la page principale d'infos
		 */
		public static function displayCredits(Smarty $smarty){
			$smarty->display(TPL_DIR."content_infos.tpl");	
		}

	}
?>