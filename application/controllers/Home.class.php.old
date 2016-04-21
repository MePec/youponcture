<?php
	class Home {

		/**
		 * Fonction displayHome
		 * Permet d'afficher la page d'accueil
		 */
		public static function displayHome(Smarty $smarty){	
			//fonction Affichage flux RSS

			// Flux RSS source
			$url_feed = "http://www.medecine-globale.ch/feed/?post_type=listing_type";

			// Nombre d'éléments à afficher
			$nb_items = 2;

			// Lecture du flux distant
			$rss = fetch_rss($url_feed);

			// Lecture des éléments
			if (is_array($rss->items))
			{
				// On récupère les éléments les plus récents
				// fonction array_slice = permet d'extraire une portion de tableau/ ici 0 est l'indice de début
				$items = array_slice($rss->items, 0, $nb_items);
				$cpt = 0; $list_rss = Array();

				// Boucle sur tous les éléments
				foreach ($items as $val) {
					$list_rss[$cpt]['ITEM_TITLE'] = utf8_encode($val['title']);
					$list_rss[$cpt]['ITEM_LINK'] = utf8_encode($val['link']);					
					$list_rss[$cpt]['ITEM_DESCRIPTION'] = utf8_encode($val['description']);
					
					$cpt++;
				}
			}

			// affecte les valeurs de RSS pour affichage sur la page
			$smarty->assign('rss',$list_rss);	

			$smarty->display(TPL_DIR."content_accueil.tpl");	
		}	
		

		/**
		 * Fonction submitLoginForm
		 * Permet de soumettre le formulaire de connexion et de le valider/vérifier
		 */
		public static function submitLoginForm(Engine $engine,Smarty $smarty){
			// Controle du Login et MDP lors de la connexion
			if (isset($_POST['login']) && isset($_POST['password'])) {
				//$mdp = md5($_POST['MDP']); // mode MD5 sécurisé
				$password = $_POST['password'];
				
				$loged = $engine->checkIdentity($_POST['login'], $password);

				if (isset($_POST['connection']) && ($loged > 0)) {
					$_SESSION['login'] = $_POST['login'];
					$_SESSION['password'] = $password;	
					$_SESSION['Logged'] = true;	

					// affecte le message à afficher
					$msg = "Vous êtes maitenant bien connecté!";
					$smarty->assign('contenu_msg',$msg);
					$smarty->display(TPL_DIR."display_msg.tpl");
			
				}
				else {
					$_SESSION['Logged'] = false;

					$msg = "Erreur lors de la connexion! Veuillez réessayer.";
					$smarty->assign('contenu_msg',$msg);
					$smarty->display(TPL_DIR."display_msg.tpl");
					
				}
			}
			else {
					$msg = "Un des champs n'a pas été complété! Veuillez réessayer.";
					$smarty->assign('contenu_msg',$msg);
					$smarty->display(TPL_DIR."display_msg.tpl");
			}
		}	

		/**
		 * Fonction submitSignForm
		 * Permet de soumettre le formulaire d'inscription et de le valider/vérifier
		 */
		public static function submitSignForm(Engine $engine,Smarty $smarty){

			if (isset($_POST['accnt_subscr']) && !empty($_POST['accnt_subscr']) && !empty($_POST['name']) && !empty($_POST['first_name']) && !empty($_POST['login']) && !empty($_POST['pwd_subscr']) && !empty($_POST['pwd_2_subscr']) ) {
				$data_send = true;
			}	
			else {
				$data_send = false;

				$msg = "Un des champs n'a pas été complété ou mal complété! Veuillez réessayer.";
				$smarty->assign('contenu_msg',$msg);
				$smarty->display(TPL_DIR."display_msg.tpl");
			}

			if ($data_send == true) {
				//$mdp = md5($_POST['MDP']);
				$login = $_POST['login'];
				$pwd = $_POST['pwd_subscr'];
				$pwd2 = $_POST['pwd_2_subscr'];
				$name = $_POST['name'];
				$first_name = $_POST['first_name'];
				
				// vérification de la double saisie du mdp
				if($pwd == $pwd2){

					$sign_in = $engine->signIn($login, $pwd, $name , $first_name);
				
					// message si erreur dans l'insertion en BDD
					if ($sign_in == false)  {
						$msg = "Un problème s'est passé pour l'insertion en BDD.Veuillez réessayer.";
						$smarty->assign('contenu_msg',$msg);
						$smarty->display(TPL_DIR."display_msg.tpl");
					}
					else {
						$msg = "L'insertion s'est bien passée.";
						$smarty->assign('contenu_msg',$msg);
						$smarty->display(TPL_DIR."display_msg.tpl");
					}

					$msg = "Vous êtes maintenant bien inscrit.Veuillez vous connecter.";
					$smarty->assign('contenu_msg',$msg);
					$smarty->display(TPL_DIR."display_msg.tpl");

				}
				else{
					$msg = "Les deux MDP saisis ne sont pas similaires : Veuillez réessayer.";
					$smarty->assign('contenu_msg',$msg);
					$smarty->display(TPL_DIR."display_msg.tpl");
				}
				// gérer si utilisateur déjà inscrit
			}
	
		}

	}
?>