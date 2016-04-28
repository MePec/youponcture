<?php

    class ErrorPage {
    	/**
		 * Fonction isUrlExist()
		 * Si le document n'existe pas, verifie si il ne s'agit pas du Web Service
		 */
		public static function isUrlExist(){
			// gérer la redirection pour le Web Service
			if(preg_match('^/WS/models$^', $_SERVER['REQUEST_URI'], $match, PREG_OFFSET_CAPTURE)){  
			  //à garder en commentaire : 1ère version de redirection : $_SERVER['REDIRECT_URL']
			  //modification du code retour
			  header("Status: 200 OK", false, 200); 

			  header("Location: /youponcture/public/index.php?p=5&q=1");			  
			}
			elseif(preg_match('^/WS/download$^', $_SERVER['REQUEST_URI'], $match, PREG_OFFSET_CAPTURE)) {
			  header("Status: 200 OK", false, 200);

			  header("Location: /youponcture/public/index.php?p=5&q=2");
			}
			elseif(preg_match('^/WS/modify/add/([a-zA-Z]*)^', $_SERVER['REQUEST_URI'], $match, PREG_OFFSET_CAPTURE)) {
			  header("Status: 200 OK", false, 200);

			  $_GET['champs'] = $match[1][0];

			  header("Location: /youponcture/public/index.php?p=5&q=3&champs=".$_GET['champs']);
			}
			elseif(preg_match('^/WS/addition/([0-9])/([0-9])$^', $_SERVER['REQUEST_URI'], $match, PREG_OFFSET_CAPTURE)){
			  header("Status: 200 OK", false, 200);
			  //alimentation du paramètre GET
			  $_GET['param1'] = $match[1][0];
			  // $_REQUEST['param1'] = $match[1][0];
			  $_GET['param2'] = $match[2][0];
			  // $_REQUEST['param2'] = $match[2][0];
		  
			  header("Location: /youponcture/public/index.php?p=6&param1=".$_GET['param1']."&param2=".$_GET['param2']);
			} 
			else
				return false;	
		}
	}
?>