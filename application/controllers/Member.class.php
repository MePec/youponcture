<?php
	class Member {
		/**
		 * Fonction Member_form
		 * Permet de gérer la gestion de l'affichage du formaulaire pour memnre de recherche
		 */
		public static function Member_connection(){		
			if($_SESSION['Logged'] == true){
			 echo 'connected'; //valeur renvoyé à JS Ajax
			}
			else
				echo 'disconnected';
		}
	}
?>