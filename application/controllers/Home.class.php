<?php

	require_once(VDR_DIR."magpierss/rss_fetch.inc");
	require_once(CTL_DIR."CheckValues.class.php");
	require_once(MDL_DIR."Engine.class.php");

	class Home {

		// Flux RSS source
		private $url_feed = "http://www.medecine-globale.ch/feed/?post_type=listing_type";
		// Nombre d'éléments à afficher
		private $nb_items = 2;
		// Message à l'utilisateur
		private $msg = '';


		/**
		 * Constructeur
		 */
		// public function __construct(){}

		/**
		 * Fonction setUrlFeed()
		 * Setter de l'attribut url_feed (Flux RSS source)
		 */
		public function setUrlFeed($url_feed){
			$this->url_feed = $url_feed;
		}

		/**
		 * Fonction setNbItems()
		 * Setter de l'attribut nb_items (Nombre d'éléments à afficher)
		 */
		public function setNbItems($nb_items){
			$this->nb_items = $nb_items;
		}


		/**
		 * Fonction getUrlFeed()
		 * Getter de l'attribut url_feed (Flux RSS source)
		 */
		public function getUrlFeed(){
			return $this->url_feed;
		}

		/**
		 * Fonction getNbItems()
		 * Getter de l'attribut nb_items (Nombre d'éléments à afficher)
		 */
		public function getNbItems(){
			return $this->nb_items;
		}

		/**
		 * Fonction getMsg()
		 * Getter de l'attribut msg (Message à l'utilisateur)
		 */
		public function getMsg(){
			return $this->msg;
		}

		/**
		 * Fonction getRss
		 * Permet de récuperer le flux RSS
		 */
		public function getRss(){	
			
			// Lecture du flux distant
			$rss = @fetch_rss($this->url_feed);

			// Lecture des éléments
			if (is_array($rss->items))
			{
				// On récupère les éléments les plus récents
				// fonction array_slice = permet d'extraire une portion de tableau/ ici 0 est l'indice de début
				$items = array_slice($rss->items, 0, $this->nb_items);
				$cpt = 0; $list_rss = Array();

				// Boucle sur tous les éléments
				foreach ($items as $val) {
					$list_rss[$cpt]['ITEM_TITLE'] = utf8_encode($val['title']);
					$list_rss[$cpt]['ITEM_LINK'] = utf8_encode($val['link']);					
					$list_rss[$cpt]['ITEM_DESCRIPTION'] = utf8_encode($val['description']);
					
					$cpt++;
				}
			}

			return $list_rss;
		}

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
			$rss = @fetch_rss($url_feed);

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
		public function submitLoginForm(){
			$login = ''; $password = '';

			// Controle du Login
			if(isset($_POST['login']) && CheckValues::checkEmail($_POST['login'])){
				$login = $_POST['login'];
			} else {
				$this->msg = "Il semble qu'il y ait un problème avec votre identifiant! Veuillez réessayer.";
				return false;
			}

			// Controle du MDP
			if(isset($_POST['password']) && CheckValues::checkNoSpace($_POST['password'])){
				$password = $_POST['password'];
			} else {
				$this->msg = "Il semble qu'il y ait un problème avec votre mot de passe! Veuillez réessayer.";
				return false;
			}

				
			$logged = Engine::checkIdentity($_POST['login'], $password);

			if (isset($_POST['connection']) && ($logged > 0)) {
				$_SESSION['login'] = $_POST['login'];
				//$_SESSION['password'] = $password;	
				$_SESSION['Logged'] = true;	

				// affecte le message à afficher
				$this->msg = "Vous êtes maitenant bien connecté!";
				return true;			
			}
			else {
				$_SESSION['Logged'] = false;

				$this->msg = "Erreur lors de la connexion! Veuillez réessayer.";
				return false;					
			}
		}	

		/**
		 * Fonction submitSignForm
		 * Permet de soumettre le formulaire d'inscription et de le valider/vérifier
		 */
		public function submitSignForm(){

			$login = '';
			$pwd = '';
			$pwd2 = '';
			$name = '';
			$first_name = '';

			// Controle si un ou plusieurs champs ne sont pas vide
			if (!isset($_POST['accnt_subscr']) || empty($_POST['accnt_subscr']) || empty($_POST['name']) || empty($_POST['first_name']) || empty($_POST['login']) || empty($_POST['pwd_subscr']) || empty($_POST['pwd_2_subscr']) ) {
				$this->msg = "Un des champs n'a pas été complété ou mal complété! Veuillez réessayer.";
				return false;
			}


			// Controle du nom de famille
			if(isset($_POST['name']) && CheckValues::checkName($_POST['name'])){
				$name = $_POST['name'];
			} else {
				$this->msg = "Il semble qu'il y ait un problème avec votre nom! Veuillez réessayer.";
				return false;
			}

			// Controle du prénom
			if(isset($_POST['first_name']) && CheckValues::checkName($_POST['first_name'])){
				$first_name = $_POST['first_name'];
			} else {
				$this->msg = "Il semble qu'il y ait un problème avec votre prénom! Veuillez réessayer.";
				return false;
			}

			// Controle du Login
			if(isset($_POST['login']) && CheckValues::checkEmailwithMX($_POST['login'])){
				$login = $_POST['login'];
			} else {
				$this->msg = "Il semble qu'il y ait un problème avec votre e-mail! Veuillez réessayer.";
				return false;
			}

			// Controle des MDP
			if(isset($_POST['pwd_subscr']) && CheckValues::checkPwd($_POST['pwd_subscr']) &&
				isset($_POST['pwd_2_subscr']) && CheckValues::checkPwd($_POST['pwd_2_subscr'])){
				$pwd = $_POST['pwd_subscr'];
				$pwd2 = $_POST['pwd_2_subscr'];
			} else {
				$this->msg = "Votre mot de passe doit faire 8 caractères minimum, contenir au moins une majuscule, une miniscule, un chiffre et aucun espace";
				return false;
			}
			
			// vérification de la double saisie du mdp
			if($pwd === $pwd2){

				$sign_in = Engine::signIn($login, $pwd, $name , $first_name);

				// message si erreur dans l'insertion en BDD
				if ($sign_in == false)  {
					$this->msg = "Il y eu un problème, vous êtes peut-être déja inscrit.Veuillez réessayer.";
				}
				else {
					$this->msg = "Vous êtes maintenant bien inscrit.Veuillez vous connecter.";
				}

			}
			else{
				$this->msg = "Les deux MDP saisis ne sont pas similaires : Veuillez réessayer.";
			}
			// gérer si utilisateur déjà inscrit
	
		}

	}
?>