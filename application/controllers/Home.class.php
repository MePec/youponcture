<?php
	class Home {

		/**
		 * Fonction displayHome
		 * Permet d'afficher la page d'accueil
		 */
		public static function displayHome(Smarty $smarty){	
			//fonction Affichage flux RSS

			// Flux RSS source
			// $url_feed = "http://www.pcastuces.com/xml/pca_logitheque.xml";
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
					// $list_rss[$cpt]['ITEM_DATE'] = $val['pubDate'];
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
		public static function submitLoginForm(Engine $engine){
			// Controle du Login et MDP lors de la connexion
			if (isset($_POST['login']) && isset($_POST['password'])) {
				//$mdp = md5($_POST['MDP']); ou sha256()
				$password = $_POST['password'];
				
				$loged = $engine->checkIdentity($_POST['login'], $password);

				if (isset($_POST['connection']) && ($loged > 0)) {
					$_SESSION['login'] = $_POST['login'];
					$_SESSION['password'] = $password;	
					$_SESSION['Logged'] = true;	
					header('Location: index.php?p=2');		
					// on peut prévoir une alert() en JS qui dit qu'on est bien connecté
				}
				else {
					$_SESSION['Logged'] = false;
					header('Location: index.php?p=1'); 
					// prévoir Vrai message d'erreur
				}
			}
			else
				header('Location: index.php?p=1'); 
				//prévoir Vrai message d'erreur		
		}	

		/**
		 * Fonction submitSignForm
		 * Permet de soumettre le formulaire d'inscription et de le valider/vérifier
		 */
		public static function submitSignForm(Engine $engine){
			var_dump($_POST);

			if (isset($_POST['accnt_subscr']) && !empty($_POST['accnt_subscr']))
				$data_send = true;
			else {
				$data_send = false;
				header('Location: index.php?p=1'); 
				// prévoir fonction php de gestion de message d'erreur qui renvoit une alerte JS
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
					// $this->engine->signIn($login, $mdp, $nom , $prenom);
				
					// message si erreur dans l'insertion en BDD
					if ($sign_in == false)  {
						header('Location: index.php?p=1');
					}
					else {
						// insertion s'est bien passé
					}

					// penser à prévoir messsage JS (ou insertion en PHP  en dur) pour confirmer l'inscription quand pas de problèmes
					header('Location: index.php?p=2');

				}
				else{
					header('Location: index.php?p=1');
					// message d'avertissement : MDP non égaux

				}
				// penser à gérer si utilisateur déjà inscrit
			}
	
		}

	}
?>