<?php
	class Home {

		/**
		 * Fonction displayHome
		 * Permet d'afficher la page d'accueil
		 */
		public function displayHome(Smarty $smarty){	
			$smarty->display(TPL_DIR."content_accueil.tpl");	
		}	
		

		/**
		 * Fonction submitLoginForm
		 * Permet de soumettre le formulaire de connexion et de le valider/vérifier
		 */
		public function submitLoginForm(Engine $engine){
			// Controle du Login et MDP lors de la connexion
			if (isset($_POST['login']) && isset($_POST['mdp'])) {
				//$mdp = md5($_POST['MDP']);
				$mdp = $_POST['mdp'];
				
				$logged = $engine->checkIdentity($_POST['login'], $mdp);
				
				if (isset($_POST['connexion']) && ($logged != true)) {
					$_SESSION['login'] = $_POST['login'];
					$_SESSION['mdp'] = $mdp;	
					$_SESSION['Logged'] = true;	
					header('Location: index.php?p=2');		
				}
				else {
					$_SESSION['Logged'] = false;
					header('Location: index.php?p=1'); 
				}
			}
			else
				header('Location: index.php?p=1'); 
				//prévoir message d'erreur		
		}	

		/**
		 * Fonction submitSignForm
		 * Permet de soumettre le formulaire d'inscription et de le valider/vérifier
		 */
		public function submitSignForm(Engine $engine){
			if (isset($_POST['send_signup']) && !empty($_POST['send_signup']))
				$data_send = true;
			else {
				$data_send = false;
				header('Location: index.php?p=1'); 
				// prévoir fonction php de gestion de message d'erreur qui renvoit une alerte JS
			}

			if ($data_send == true) {
				//$mdp = md5($_POST['MDP']);
				$login = $_POST['login'];
				$mdp = $_POST['mdp'];
				$mdp_bis = $_POST['mdp_bis'];
				$nom = $_POST['nom'];
				$prenom = $_POST['prenom'];
				//$email = $_POST['email'];
				
				// vérification de la sdouble saisie du mdp
				if($mdp == $mdp_bis){

					$sign_in = $engine->signIn($login, $mdp, $nom , $prenom);
					// $this->engine->signIn($login, $mdp, $nom , $prenom);
				
					// message si erreur dans l'insertion en BDD
					if ($sign_in == false)  {
						header('Location: index.php?p=1');
					}
					else{
						// insertion s'est bien passé
					}

					// penser à prévoir messsage JS (ou insertion en PHP  en dur) pour confirmer l'inscription quand pas de problèmes
					header('Location: index.php?p=3');

				}
				else{
					header('Location: index.php?p=1');
					// message d'avertissement : MDP non égaux

				}
			}
	
		}

	}
?>