<?php
	class Manager {
		private $db;
		private $engine;
		
		private $page;
		private $section;
		
		/**
		 * Constructeur
		 */
		public function __construct($page = null, $section = null){
			$classDB = new DB();
			
			$this->db = $classDB->getInstance();
	       	$this->engine = new Engine($this->db);
	       	$this->template = new Template(TPL_DIR);
			
			$this->page = $page;
		    $this->section = $section;
			
			$logged = 0;
				
			// Controle du Login et MDP lors de la connexion
			if (isset($_POST['Login']) && isset($_POST['MDP'])) {
				$mdp = md5($_POST['MDP']);
				
				$logged = $this->engine->checkIdentify($_POST['Login'], $mdp);
				
				if (isset($_POST['Connexion']) && ($logged != 0)) {
					$_SESSION['Login'] = $_POST['Login'];
					$_SESSION['MDP'] = $mdp;	
					$_SESSION['Permission'] = $this->engine->verifResponsable($_SESSION['Login']);
					$_SESSION['Logged'] = true;				
				}
				else
					$_SESSION['Logged'] = false;
			}
				
			if(isset($_SESSION['Logged']) && $_SESSION['Logged'] != true)
				$page = 10;
			
			switch($page){
				case "1":
					$this->displaySearch();
					break;
			
				case "2":
					$this->displayList();
					break;
			
				case "3":
					if (isset($_SESSION['Permission']) && $_SESSION['Permission'] > 0)
						$this->displayFormAdd();
					else 
						$this->displayMsgError();
						
					break;

				case "4":
					switch($this->section){
						case "1":
							$this->displaySearch_PARD();
							break;	
						
						case "2":
							$this->displayList_PARD();
							break;	
						
						case "3":
							if (isset($_SESSION['Permission']) && $_SESSION['Permission'] > 0)
								$this->displayFormAdd_PARD();
							else
								$this->displayMsgError();
								
							break;							

						case "4":
							$this->exportData_PARD();
							break;						
					
						default:
							$this->displayHome();
					}
					
					break;					
				
				case "5":		
					switch($this->section){
						case "5":
							$this->exportDataRow();
							break;	
						
						case "6":
							$this->exportDataCol();
							break;						
				
						case "7":
							$this->exportDataRowCompressed();
							break;
						
						case "8":
							$this->exportData_Existant();
							break;
						
						case "9":
							$this->exportData_Comment_G2R();
							break;
						
						case "10":
							$this->exportData_Site();
							break;
							
						case "11":
							$this->exportData_Radio();
							break;
							
						case "12":
							$this->exportData_Equipements();
							break;
						
						case "13":
							$this->exportData_Iqlink();
							break;	
						
						case "14":
							$this->exportData_World();
							break;

						case "15":
							$this->exportData_PTC();
							break;
						
						case "16":
							$this->exportData_Comment_Liens();
							break;
							
						default:
							$this->displayExportChoice();
					}	
					
					break;	
					
				case "6":
					switch($this->section){
						case "1":
							if (isset($_SESSION['Permission']) && $_SESSION['Permission'] > 0)
								$this->displayRapport_PeuplementBDDCible();
							else 
								$this->displayMsgError();
							break;				
					
						case "2":
							if (isset($_SESSION['Permission']) && $_SESSION['Permission'] > 0)
								$this->exportRapport_PeuplementBDDCible();
							else 
								$this->displayMsgError();
							break;
						
						case "3":
							$this->displayRapport_FH();
							break;
						
						case "4":
							$this->exportRapport_FH();
							break;
						
						case "5":
							$this->displayRapport_IP();
							break;
						
						case "6":
							$this->exportRapport_IP();
							break;
						
						default:
							$this->displayRapportChoice();
					}
						
					break;	
				
				case "7":
					$this->displayInjection();
					break;
				
				case "8":
					$this->displaySearchLink();
					break;

				case "9":
					$this->exportAuto();
					break;	
			
				case "10":
					$this->displayConnexion();
					break;
					
				case "11":
					switch($this->section){
						case "1":
							$this->submitFormCom_G2R();
							$this->displaySearch();
							break;	
						
						case "2":
							$this->delete_com_G2R();
							$this->displaySearch();
							break;	
												
						default:
							$this->displaySearch();
					}
					break;	
				
				case "12":
					if (isset($_SESSION['Permission']) && $_SESSION['Permission'] > 0)
						$this->displayPassword();
					else 
						$this->displayMsgError();
					break;

					
				case "13":
					if (isset($_SESSION['Permission']) && $_SESSION['Permission'] > 0) {
						switch($this->section){
							case "1":
								$this->MAJdatasG2Rv2();
								break;	
							default:
								$this->displayImportMenu();
						}	
					}
					else 
						$this->displayMsgError();
					break;

				case "14":
					switch($this->section){
						case "1":
							$this->submitFormCom_Liens();
							$this->displaySearchLink();
							break;	
						
						case "2":
							$this->delete_com_Liens();
							$this->displaySearchLink();
							break;	
												
						default:
							$this->displaySearchLink();
					}
					break;					
					
				case "15":
					$this->displayExportDocumentationChoice();
					break;	
				
				case "16":
					if (isset($_SESSION['Permission']) && $_SESSION['Permission'] > 0)
						$this->displayFormAdd_AttributsSite();
					else 
						$this->displayMsgError();	
					break;
						
						
				// Lancement des tâches planifiées
				case "99":
					$this->importRSU();
					break;
				
				default:
					$this->displayReport();
			}
		}
		
		/**
		 * Fonction displayHome
		 * Permet d'afficher la page d'accueil
		 */
		public function displayHome(){
			$this->template->set_filenames(array(
		    	'tpl_home'			=> 'home.tpl',
				'tpl_menu'			=> 'menu.tpl'
	       	));
		
			$this->displayMenu();
		
	       	$this->template->pparse('tpl_home');
		}	
		
		 /**
		 * Fonction displayConnexion
		 * Permet d'afficher la page d'identification
		 */
		public function displayConnexion(){
			session_unset();
			session_destroy();
				
			$this->template->set_filenames(array(	
				'tpl_connect'		=> 'connexion.tpl',		
				'tpl_menu'			=> 'menu.tpl'			
	       	));		
			
			$this->displayMenu();	
			
	       	$this->template->pparse('tpl_connect');
		}	
		
		/**
		 * Fonction displayPassword
		 * Permet d'afficher le formulaire pour changer le MDP
		 */
		public function displayPassword(){
			$this->template->set_filenames(array(
				'tpl_password'		=> 'change_password.tpl',
				'tpl_menu'			=> 'menu.tpl'
			));

			$this->displayMenu();

			if(isset($_POST['send']) && !empty($_POST['send'])){
				if(isset($_POST['MDP']) && isset($_POST['MDP2']) && $_POST['MDP'] == $_POST['MDP2']){
					$this->template->assign_block_vars('success', array());		

					$this->engine->updatePassword($_SESSION['Login'], md5($_POST['MDP']));
				}
				else {
					$this->template->assign_block_vars('error', array(
						'MSG'		=> 'Erreur : les mots de passe doivent être renseignés et identiques.'
					));
				}		
			}

			$this->template->pparse('tpl_password');
		}

		/**
		 * Fonction displayReport
		 * Permet d'afficher le tableau de bord de l'état des données des référentiels utilisés pour générer les informations dans la BDD Existant-Radio-Site
		 */
		public function displayReport(){
			$this->template->set_filenames(array(
		    	'tpl_report'			=> 'report.tpl',
				'tpl_menu'				=> 'menu.tpl'
	       	));
			
			$this->displayMenu();	
			
			// Récupération des infos. de MAJ de la BDD Existant - traitement particulier
			/****************************************************************************/
			$date = date("Y-m-d"); 
			$date_p1 = date("Y-m-d",strtotime("-1 days")); 
			$last_maj = $this->engine->getLast_MAJ();
			$date_last_maj = implode(",", $last_maj);
			
			if ($date_last_maj == $date) {
				$etat = "OK";
				$dateMAJ = $date;
				$fond = "vert";
			}
			/*
			else if($date_last_maj == $date_p1) {
				$etat = "NOK";
				$dateMAJ = $date_last_maj;
				$fond = "jaune";
			}
			*/
			else {
				$etat = "NOK";
				$dateMAJ = $date_last_maj;
				$fond = "rouge";
			}		
			
			$this->template->assign_block_vars('etat_bdd_existant', array(
						'ETAT' 			=> $etat,
						'MAJ'	 		=> $dateMAJ,
						'FOND'	 		=> $fond
			));
			
			//Récupération des infos. de MAJ des autres Référentiels récupérés en amont - traitement générique	
			/****************************************************************************/
			$tab_ref = array("RRCAP","RSU","OCEAN","G2R_TRANS","RRTRANS"); 
			$tab_tpl = array_map('strtolower', $tab_ref); // Génération d'un tableau en minuscule pour assigner pour chaque Référentiel les bonnes données au Template

			for($i = 0; $i < count($tab_ref); $i++) {
				$categorie = $tab_ref[$i];
				$referentiel = $tab_tpl[$i];
				$tpl = 'etat_'.$referentiel.'';
			
				$info_maj_autres = $this->engine->getEtatMAJaDate($categorie);
				
				// Vérification s'il existe une MAJ qui date du jour même
				if(count($info_maj_autres) > 1){
					$erreurs = $info_maj_autres['NB_ERREURS'];
					
					// Vérification et traitement s'il existe ou pas des erreurs lors de la MAJ du jour même
					if($erreurs == 0) {
						$this->template->assign_block_vars($tpl, array(
							'ETAT'				=> "OK",
							'MAJ'				=> $info_maj_autres['DATE_EXPORT_SOURCES'],
							'FOND'				=> "vert"
						));
					}
					else {
						$this->template->assign_block_vars($tpl, array(
							'ETAT'				=> "NOK",
							'MAJ'				=> $info_maj_autres['DATE_EXPORT_SOURCES'],
							'FOND'				=> "rouge"
						));
					}
				}
				//si pas de MAJ à date,récupérer la dernière date de MAJ
				else {
					$info_maj_autres2 = $this->engine->getDerniereMAJReferentiels($categorie);
					$erreurs = $info_maj_autres['NB_ERREURS'];
					
					//vérification et traitement s'il existe ou pas des erreurs lors de la dernière MAJ 
					if($erreurs == 0) {
						$this->template->assign_block_vars($tpl, array(
							'ETAT'				=> "NOK",
							'MAJ'				=> $info_maj_autres2['DATE_EXPORT_SOURCES'],
							'FOND'				=> "jaune"
						));
					}
					else {
						$this->template->assign_block_vars($tpl, array(
							'ETAT'				=> "NOK",
							'MAJ'				=> $info_maj_autres2['DATE_EXPORT_SOURCES'],
							'FOND'				=> "rouge"
						));
					}
				}
			}
			
	       	$this->template->pparse('tpl_report');
		}	
		
		/**
		 * Fonction displayRapportChoice
		 * Permet d'afficher la page des rapports
		 */
		public function displayRapportChoice(){
			$this->template->set_filenames(array(
		    	'tpl_rapports_menu'		=> 'rapport.tpl',
				'tpl_menu'				=> 'menu.tpl'
	       	));
		
			$this->displayMenu();	

			$this->template->pparse('tpl_rapports_menu');
		}
		
		/**
		 * Fonction displayExportChoice
		 * Permet d'afficher la page de recherche
		 */
		public function displayExportChoice(){
			$this->template->set_filenames(array(
		    	'tpl_export_menu'		=> 'export.tpl',
				'tpl_menu'				=> 'menu.tpl'
	       	));
		
			$this->displayMenu();	
		
			$this->template->pparse('tpl_export_menu');
		}

		/**
		 * Fonction displayExportDocumetnationChoice
		 * Permet d'afficher la page d'export de la documentation
		 */
		public function displayExportDocumentationChoice(){
			$this->template->set_filenames(array(
		    	'tpl_export_menu'		=> 'export_doc.tpl',
				'tpl_menu'				=> 'menu.tpl'
	       	));
		
			$this->displayMenu();	
		
			$this->template->pparse('tpl_export_menu');
		}
		
		/**
		 * Fonction displayMsgError
		 * Permet d'afficher une boite de dialogue avec un mesage d'alerte quand un utilisateur non autorisé essaye d'accéder à une page interdite
		 */
		public function displayMsgError(){
			$this->template->set_filenames(array(
		    	'tpl_home'		=> 'home.tpl',
				'tpl_menu'		=> 'menu.tpl'
	       	));
		
			$this->displayMenu();	
			
			$this->template->set_filenames(array(
		    	'tpl_display_msg'		=> 'display_msg.tpl'
	       	));
			
			$error = true;
			$msg = "Vous n'êtes pas autorisé à accéder à cette page !";
				
			$this->template->assign_block_vars('error', array(
				'MSG' => 'Attention : '.'\n\n'.$msg
			));
					
			$this->template->pparse('tpl_display_msg');

			$this->template->pparse('tpl_home');
		}

		/**
		 * Fonction displayImportMenu
		 * Permet d'afficher la page permettant d'importer et d'écraser les données SUPPORT/TECHNO pour chaque G2R dans la BDD Cible
		 */
		public function displayImportMenu(){
			$this->template->set_filenames(array(
		    	'tpl_import'	=> 'import.tpl',
				'tpl_menu'		=> 'menu.tpl'
	       	));

			$this->displayMenu();	
			
			$this->template->pparse('tpl_import');
		}
		
		/**
		 * Fonction MAJdatasG2Rv2
		 * Permet de faire tout le traitement d'importation des fichiers fournis pas l'utilisateur : ie. d'écraser les données SUPPORT/TECHNO des G2R 
		 */
		public function MAJdatasG2Rv2(){ 			
			$this->template->set_filenames(array(
				'tpl_import'		=> 'import.tpl',
				'tpl_menu'			=> 'menu.tpl'
			));
			$this->displayMenu();	
			
			// Pour les fichiers de type .csv uniquement (pré-requis)
			$tmp_name = Array();		
			$allowed =  array('csv');
				
			//controle de l'extension des fichiers uploadés
			$name = $_FILES['fichier']['name'];
			$ext = pathinfo($name, PATHINFO_EXTENSION);		
			if(!in_array($ext,$allowed))
				$error_import = true;				
			else
				$error_import = false;
				
			//chargement en mémoire du contenu du fichier pour vérification fichier
			$tmp_name = $_FILES['fichier']['tmp_name'];

			if(!$error_import && !empty($tmp_name)) {			
				$row = 1;
				
				if (($handle = fopen($tmp_name, "r")) !== FALSE) {
					$donnees = array();			
				
					while(($data = fgetcsv($handle,10000, ";")) !== FALSE) {
						$num = count($data); //$num : nombre de champs (colonnes) du fichier chargé
		
						for($c = 0; $c < $num; $c++) {
							$donnees[$row][$c] = $data[$c];	
						}					
						$result_supp = $this->engine->getRefSupport($donnees[$row][1]); 
						$result_tech = $this->engine->getRefTechno($donnees[$row][2]);							
						$this->engine->updateSupportTechno($donnees[$row][0],$result_supp['ID_Support'],$result_tech['ID_Techno']); 
		
						$row++; //$row : nombre de lignes du fichier en input
					}
					fclose($handle);
				}	
				$this->template->set_filenames(array(
					'tpl_display_msg' 	=> 'display_msg.tpl'
				));
				$this->template->assign_block_vars('confirmation', array(
						'MSG' => 'Les changements de SUPPORT et TECHNO (champs non vides dans le fichier d\'import) pour les G2R concernés ont été effectué dans la BDD Cible'
				));			
				$this->template->pparse('tpl_display_msg');
			}
			else {	
				$this->template->set_filenames(array(
					'tpl_display_msg' 	=> 'display_msg.tpl'
				));			
				$this->template->assign_block_vars('error_import', array(
						'MSG' => 'Il y a un problème avec le fichier importée (vide,mal formaté ou mauvaise extension)'
				));
				$this->template->pparse('tpl_display_msg');
			}

			$this->template->pparse('tpl_import');
		} 
		
		/**
		 * Fonction displaySearchLink
		 * Permet d'afficher la page de recherche des liens
		 */
		public function displaySearchLink(){
			$this->template->set_filenames(array(
		    	'tpl_search_link'	=> 'search_link.tpl',
				'tpl_menu'			=> 'menu.tpl'
	       	));
		
			$this->displayMenu();

			$find = false;

			$g2rA = $g2rB = null;
			$siteA = $siteB = null;
		
			// Récupération des informations pour le G2R A
			if(isset($_GET['G2R_A']) && !empty($_GET['G2R_A'])){
				$g2rA = $_GET['G2R_A'];
				$find = true;
			}
			
			if(isset($_POST['G2R_A']) && !empty($_POST['G2R_A'])){
				$g2rA = $_POST['G2R_A'];
				$find = true;
			}

			// Récupération des informations pour le G2R B
			if(isset($_GET['G2R_B']) && !empty($_GET['G2R_B'])){
				$g2rB = $_GET['G2R_B'];
				$find = true;
			}
			
			if(isset($_POST['G2R_B']) && !empty($_POST['G2R_B'])){
				$g2rB = $_POST['G2R_B'];
				$find = true;
			}

			$this->template->assign_var('G2R_A', $g2rA);
			$this->template->assign_var('G2R_B', $g2rB);

			// Récupération des G2R saisis dans formulaire
			if($find){
				$this->template->assign_block_vars('display', array());

				/**********************************************************
				 ** MODULE SITE
				 **********************************************************/
				$data_siteA = $this->engine->getInfo_Site($g2rA);
				
				if(count($data_siteA) > 0){
					foreach ($data_siteA as $result_site) {
						$siteA = $result_site['G2R'] .' '. $result_site['NOM'];

						$this->template->assign_block_vars('display.result_siteA', array(
							'DPT'					=> $result_site['DPT'],
							'CODE'					=> $result_site['CODE_POSTAL'],
							'COMMUNE' 				=> $result_site['COMMUNE'],
							'CODE_INSEE' 			=> $result_site['CODE_INSEE'],
							'TYPE' 					=> $result_site['TYPE'],
							'CODE_JV' 				=> $result_site['CODE'],
							'TYPE_RAN_SHARING' 		=> $result_site['TYPE_RAN_SHARING'],
							'LAT' 					=> $result_site['LATITUDE'],
							'LONG' 					=> $result_site['LONGITUDE'],
							'X'						=> $result_site['X'],
							'Y'						=> $result_site['Y']
						));
					}

					unset($result_site);
				}
				else {
					$this->template->assign_block_vars('display.no_result_siteA', array());
				}

				$data_siteB = $this->engine->getInfo_Site($g2rB);
				
				if(count($data_siteB) > 0){
					foreach ($data_siteB as $result_site) {
						$siteB = $result_site['G2R'] .' '. $result_site['NOM'];

						$this->template->assign_block_vars('display.result_siteB', array(
							'DPT'					=> $result_site['DPT'],
							'CODE'					=> $result_site['CODE_POSTAL'],
							'COMMUNE' 				=> $result_site['COMMUNE'],
							'CODE_INSEE' 			=> $result_site['CODE_INSEE'],
							'TYPE' 					=> $result_site['TYPE'],
							'CODE_JV' 				=> $result_site['CODE'],
							'TYPE_RAN_SHARING' 		=> $result_site['TYPE_RAN_SHARING'],
							'LAT' 					=> $result_site['LATITUDE'],
							'LONG' 					=> $result_site['LONGITUDE'],
							'X'						=> $result_site['X'],
							'Y'						=> $result_site['Y']
						));
					}

					unset($result_site);
				}
				else {
					$this->template->assign_block_vars('display.no_result_siteB', array());
				}

				if($siteA == null && $siteB == null)
					$title = $g2rA . " - " . $g2rB;
				else if($siteA == null)
					$title = $g2rA . " - " . $siteB;
				else if($siteB == null)
					$title = $siteA . " - " . $g2rB;
				else
					$title = $siteA . " - " . $siteB;

				$this->template->assign_block_vars('title', array(
					'NOM_SITE'		=> $title
				));


				/**********************************************************
				 ** MODULE EXISTANT : IQLINK
				 **********************************************************/
				$result_fh = $this->engine->getInfo_iQlink($g2rA, $g2rB, "Réel");
				$data_fh = $result_fh['data'];
				$nb_fh = $result_fh['nb'];
				
				$this->template->assign_block_vars('display.result_fh', array());
				
				if($nb_fh > 0) {								
					for($i = 0; $i < $nb_fh; $i++){	
						$this->template->assign_block_vars('display.result_fh.content', array(	
							'LOPID'					=> $data_fh[$i]['LOPID'],
							'VERSION' 				=> $data_fh[$i]['VERSION'],
							'LINK_ID' 				=> $data_fh[$i]['LINK_ID'],
							'G2R_A' 				=> $data_fh[$i]['G2R_A'],
							'G2R_B' 				=> $data_fh[$i]['G2R_B'],
							'DISTANCE'				=> round($data_fh[$i]['DISTANCE'], 1),
							'FOURNISSEUR'			=> $data_fh[$i]['FOURNISSEUR'],
							'EQUIPEMENT'			=> $data_fh[$i]['EQUIPEMENT'],
							'CAPACITE'				=> $data_fh[$i]['CAPACITE'],
							'FREQUENCE'				=> $data_fh[$i]['FREQUENCE'],
							'CANALISATION'			=> $data_fh[$i]['CANALISATION'],
							'CANAL'					=> $data_fh[$i]['CANAL'],
							'POLAR'					=> $data_fh[$i]['POLAR_1'],
							'REF_ANT_A'				=> $data_fh[$i]['REF_ANT_A'],
							'REF_ANT_B'				=> $data_fh[$i]['REF_ANT_B'],
							'DIAMETRE_A'			=> $data_fh[$i]['DIAMETRE_A'] *100,
							'DIAMETRE_B'			=> $data_fh[$i]['DIAMETRE_B'] *100
						));
					}
				}
				else {
					$this->template->assign_block_vars('display.result_fh.no_content', array());
				}

				/**********************************************************
				 ** MODULE DIMENSIONNEMENT 
				 **********************************************************/
				$result_conf = $this->engine->getConfigDimensionnement();
				$data_conf = $result_conf['data'];
				
				$debit_pic = $debit_garanti = $somme_debits = 0;
				$debit_cible_crozon = Array();
				$i = $j = 0;
				
				//Récupération des sites en amont à prendre en compte
				$tabSitesImpactes = $this->search_sites_amont($g2rA, $g2rB);
				$siteAmont = Array();

				//Récupération des valeurs de débits pic/critique et contour à prendre en compte pour chaque site amont
				if(count($tabSitesImpactes) > 0){
					foreach($tabSitesImpactes as $site){
						$dataSite2 = $this->engine->getInfo_Crozon($site);
						$data_crozon2 = $dataSite2['data'];
						
						//recupération Debit 2022
						$debit_2022  = 0;
						$result_debit2022 = $this->engine->getDebit2022($site);
						$debit_2022 = $result_debit2022['DEBIT'];
									
						if($data_crozon2[$i]['CONTOUR'])
							$siteAmont[$i]['Contour'] = $data_crozon2[$i]['CONTOUR'];
						else 
							$siteAmont[$i]['Contour'] = "Zone B - SFR"; //choix par défaut si pas d'infos de contour CROZON dans la BDD Table "CROZON", à verifier auprès de Morgan
						
						//calculs préalables pour Debit Pic
						if($siteAmont[$i]['Contour'] == "Zone A - SFR" || $siteAmont[$i]['Contour'] == "ZTD")
							$debit_max_racco = $data_conf[0]['DebitPic_ZoneA']; 
						else	
							$debit_max_racco = $data_conf[0]['DebitPic_HorsZoneA']; 
						
						//calculs préalables pour Debit Garanti						
						if($debit_2022 != 0){		
							$debit_cible_crozon[$i] = $debit_2022 * $data_conf[0]['Coeff_CROZON']; //Coefficient CROZON (Prise en compte BYTel) 
							$somme_debits += $debit_cible_crozon[$i];
							//round($somme_debits,0);
						}	
						else if($debit_2022 == 0){
						// cas si Débit 2022 non renseigné dans BDD : on prend valeurs Contour CROZON par défaut
							switch($siteAmont[$i]['Contour']){
								case "Zone A - SFR":
									$debit_cible_crozon[$i] = $data_conf[0]['DebitCritique_ZoneA']; 
									$somme_debits += $debit_cible_crozon[$i];
									//round($somme_debits,0);
									break;
									
								case "ZTD":
									$debit_cible_crozon[$i] = $data_conf[0]['DebitCritique_ZoneA']; 
									$somme_debits += $debit_cible_crozon[$i];
									//round($somme_debits,0);
									break;
									
								case "Zone B - SFR":
									$debit_cible_crozon[$i] = $data_conf[0]['DebitCritique_HorsZoneA']; 
									$somme_debits += $debit_cible_crozon[$i];
									//round($somme_debits,0);
									break;
							}
						}
						$i++;
					}
				}
				
				$debit_garanti = $somme_debits * $data_conf[0]['Coeff_Debits']; //Coefficient Débit garanti vs débit pic 
				$debit_garanti = round($debit_garanti,0);
				$debit_pic = max($debit_max_racco,$somme_debits);
				
				
				/* Détermination de la distance entre les 2 sites , via les coordonnées disponibles dans la BDD Existant (RSU) */
				$distance = $this->Distance($g2rA, $g2rB);
				
				/* Détermination du type raccordement */
				if(count($tabSitesImpactes) > 5)
					$type = "XPIC";
				else if(count($tabSitesImpactes) > 1 && count($tabSitesImpactes) < 6)
					$type = "TRANSPORT";
				else 
					$type = "RACCO";
				
				/*Determination des débits critiques/pics validés selon les débits brut*/
				//débit pic validé
				//faire double switch
				switch($type) {
					case ($type == "XPIC"):
							if($debit_pic >= 892 && $distance <= 12) 
								$debit_pic_valide = 892;
							if($debit_pic >= 892 && $distance > 12)
								$debit_pic_valide = 630;	
							else if($debit_pic < 892 && $debit_pic >= 630 && $distance >= 10)
								$debit_pic_valide = 630;
							else if($debit_pic < 892 && $debit_pic >= 630 && $distance < 10)
								$debit_pic_valide = 892;
							else if($debit_pic < 630 && $distance <= 20)
								$debit_pic_valide = 448;
							break;
					
					case ($type == "TRANSPORT"):
						if($debit_pic >= 446 && $distance <= 12)
							$debit_pic_valide = 446;
						else if($debit_pic >= 446 && $distance > 12 )
							$debit_pic_valide = 315;
						else if($debit_pic < 446 && $debit_pic >= 315 && $distance < 10)
							$debit_pic_valide = 446;
						else if($debit_pic < 446 && $debit_pic >= 315 && $distance > 10)
							$debit_pic_valide = 315;
						else if($debit_pic < 315 && $debit_pic >= 225 && $distance > 10)
							$debit_pic_valide = 315;
						else if($debit_pic < 315 && $debit_pic >= 225 && $distance < 10)
							$debit_pic_valide = 225;
						else if($debit_pic < 225)
							$debit_pic_valide = 225;
						break;
									
					case ($type == "RACCO"):
						if($debit_pic >= 446 && $distance <= 14)
							$debit_pic_valide = 446;
						else if($debit_pic >= 446 && $distance > 14)
							$debit_pic_valide = 225;
						else if($debit_pic < 446)
							$debit_pic_valide = 225;
						break;	
						
					default :
						$debit_pic_valide = "-";
				}
				
				//débit critique validé
				switch($type) {
					case ($type == "XPIC"):
							if($debit_pic >= 892 && $distance <= 12) {
								switch($debit_garanti){
									case($debit_garanti <= 332):
										$debit_critique_valide = 332;
										break;
									case($debit_garanti > 332 && $debit_garanti <= 414):
										$debit_critique_valide = 414;
										break;
									case($debit_garanti > 414 && $debit_garanti <= 524):
										$debit_critique_valide = 524;
										break;	
									case($debit_garanti > 524 && $debit_garanti <= 622):
										$debit_critique_valide = 622;
										break;		
									case($debit_garanti > 622 && $debit_garanti <= 718):
										$debit_critique_valide = 718;
										break;
									case($debit_garanti > 718 && $debit_garanti <= 800):
										$debit_critique_valide = 800;
										break;
									default: 
										$debit_critique_valide = "-";
								}
							}
							else if($debit_garanti >= 892 && $distance > 12){
								switch($debit_garanti){
									case($debit_garanti <= 370):
										$debit_critique_valide = 370;
										break;
									case($debit_garanti > 370 && $debit_garanti <= 438):
										$debit_critique_valide = 438;
										break;
									case($debit_garanti > 438 && $debit_garanti <= 506):
										$debit_critique_valide = 506;
										break;	
									case($debit_garanti > 506 && $debit_garanti <= 564):
										$debit_critique_valide = 564;
										break;		
									default: 
										$debit_critique_valide = "-";
								}
							}
							else if($debit_garanti < 892 && $debit_garanti >= 630 && $distance >= 10){
								switch($debit_garanti){
									case($debit_garanti <= 370):
										$debit_critique_valide = 370;
										break;
									case($debit_garanti > 370 && $debit_garanti <= 438):
										$debit_critique_valide = 438;
										break;
									case($debit_garanti > 438 && $debit_garanti <= 506):
										$debit_critique_valide = 506;
										break;	
									case($debit_garanti > 506 && $debit_garanti <= 564):
										$debit_critique_valide = 564;
										break;		
									default: 
										$debit_critique_valide = "-";
								}
							}
							else if($debit_garanti < 892 && $debit_garanti >= 630 && $distance < 10){
								switch($debit_garanti){
									case($debit_garanti <= 332):
										$debit_critique_valide = 332;
										break;
									case($debit_garanti > 332 && $debit_garanti <= 414):
										$debit_critique_valide = 414;
										break;
									case($debit_garanti > 414 && $debit_garanti <= 524):
										$debit_critique_valide = 524;
										break;	
									case($debit_garanti > 524 && $debit_garanti <= 622):
										$debit_critique_valide = 622;
										break;		
									case($debit_garanti > 622 && $debit_garanti <= 718):
										$debit_critique_valide = 718;
										break;
									case($debit_garanti > 718 && $debit_garanti <= 800):
										$debit_critique_valide = 800;
										break;
									default: 
										$debit_critique_valide = "-";
								}
							}	
							else if($debit_garanti < 630 && $distance <= 20){
								switch($debit_garanti){
									case($debit_garanti <= 168):
										$debit_critique_valide = 168;
										break;
									case($debit_garanti > 168 && $debit_garanti <= 210):
										$debit_critique_valide = 210;
										break;
									case($debit_garanti > 210 && $debit_garanti <= 264):
										$debit_critique_valide = 264;
										break;	
									case($debit_garanti > 264 && $debit_garanti <= 314):
										$debit_critique_valide = 314;
										break;		
									case($debit_garanti > 314 && $debit_garanti <= 360):
										$debit_critique_valide = 360;
										break;
									case($debit_garanti > 360 && $debit_garanti <= 400):
										$debit_critique_valide = 400;
										break;
									default: 
										$debit_critique_valide = "-";
								}
							}
							break;
					
					case ($type == "TRANSPORT"):
						if($debit_garanti >= 446 && $distance <= 12){
							switch($debit_garanti){
									case($debit_garanti <= 166):
										$debit_critique_valide = 166;
										break;
									case($debit_garanti > 166 && $debit_garanti <= 207):
										$debit_critique_valide = 207;
										break;
									case($debit_garanti > 207 && $debit_garanti <= 262):
										$debit_critique_valide = 262;
										break;	
									case($debit_garanti > 262 && $debit_garanti <= 311):
										$debit_critique_valide = 311;
										break;		
									case($debit_garanti > 311 && $debit_garanti <= 359):
										$debit_critique_valide = 359;
										break;
									case($debit_garanti > 359 && $debit_garanti <= 400):
										$debit_critique_valide = 400;
										break;
									default: 
										$debit_critique_valide = "-";
								}
						}		
						else if($debit_garanti >= 446 && $distance > 12 ){
							switch($debit_garanti){
									case($debit_garanti <= 185):
										$debit_critique_valide = 185;
										break;
									case($debit_garanti > 185 && $debit_garanti <= 219):
										$debit_critique_valide = 219;
										break;
									case($debit_garanti > 219 && $debit_garanti <= 253):
										$debit_critique_valide = 253;
										break;	
									case($debit_garanti > 253 && $debit_garanti <= 282):
										$debit_critique_valide = 282;
										break;		
									default: 
										$debit_critique_valide = "-";
								}
						}		
						else if($debit_garanti < 446 && $debit_garanti >= 315 && $distance < 10){
							switch($debit_garanti){
									case($debit_garanti <= 166):
										$debit_critique_valide = 166;
										break;
									case($debit_garanti > 166 && $debit_garanti <= 207):
										$debit_critique_valide = 207;
										break;
									case($debit_garanti > 207 && $debit_garanti <= 262):
										$debit_critique_valide = 262;
										break;	
									case($debit_garanti > 262 && $debit_garanti <= 311):
										$debit_critique_valide = 311;
										break;		
									case($debit_garanti > 311 && $debit_garanti <= 359):
										$debit_critique_valide = 359;
										break;
									case($debit_garanti > 359 && $debit_garanti <= 400):
										$debit_critique_valide = 400;
										break;
									default: 
										$debit_critique_valide = "-";
								}
						}
						else if($debit_garanti < 446 && $debit_garanti >= 315 && $distance > 10){
							switch($debit_garanti){
									case($debit_garanti <= 185):
										$debit_critique_valide = 185;
										break;
									case($debit_garanti > 185 && $debit_garanti <= 219):
										$debit_critique_valide = 219;
										break;
									case($debit_garanti > 219 && $debit_garanti <= 253):
										$debit_critique_valide = 253;
										break;	
									case($debit_garanti > 253 && $debit_garanti <= 282):
										$debit_critique_valide = 282;
										break;		
									default: 
										$debit_critique_valide = "-";
								}
						}
						else if($debit_garanti < 315 && $debit_garanti >= 225 && $distance > 10){
								switch($debit_garanti){
									case($debit_garanti <= 185):
										$debit_critique_valide = 185;
										break;
									case($debit_garanti > 185 && $debit_garanti <= 219):
										$debit_critique_valide = 219;
										break;
									case($debit_garanti > 219 && $debit_garanti <= 253):
										$debit_critique_valide = 253;
										break;	
									case($debit_garanti > 253 && $debit_garanti <= 282):
										$debit_critique_valide = 282;
										break;		
									default: 
										$debit_critique_valide = "-";
								}
						}
						else if($debit_garanti < 315 && $debit_garanti >= 225 && $distance < 10){
							switch($debit_garanti){
									case($debit_garanti <= 84):
										$debit_critique_valide = 84;
										break;
									case($debit_garanti > 84 && $debit_garanti <= 105):
										$debit_critique_valide = 105;
										break;
									case($debit_garanti > 105 && $debit_garanti <= 132):
										$debit_critique_valide = 132;
										break;	
									case($debit_garanti > 132 && $debit_garanti <= 157):
										$debit_critique_valide = 157;
										break;		
									case($debit_garanti > 157 && $debit_garanti <= 180):
										$debit_critique_valide = 180;
										break;	
									case($debit_garanti > 180):
										$debit_critique_valide = 200;
										break;
									default: 
										$debit_critique_valide = "-";
								}
						}
						else if($debit_garanti < 225){
							switch($debit_garanti){
									case($debit_garanti <= 84):
										$debit_critique_valide = 84;
										break;
									case($debit_garanti > 84 && $debit_garanti <= 105):
										$debit_critique_valide = 105;
										break;
									case($debit_garanti > 105 && $debit_garanti <= 132):
										$debit_critique_valide = 132;
										break;	
									case($debit_garanti > 132 && $debit_garanti <= 157):
										$debit_critique_valide = 157;
										break;		
									case($debit_garanti > 157 && $debit_garanti <= 180):
										$debit_critique_valide = 180;
										break;	
									case($debit_garanti > 180):
										$debit_critique_valide = 200;
										break;
									default: 
										$debit_critique_valide = "-";
								}
						}
						break;
							

							
					case ($type == "RACCO"):
						if($debit_garanti >= 446 && $distance <= 14){
							switch($debit_garanti){
									case($debit_garanti <= 166):
										$debit_critique_valide = 166;
										break;
									case($debit_garanti > 166 && $debit_garanti <= 207):
										$debit_critique_valide = 207;
										break;
									case($debit_garanti > 207 && $debit_garanti <= 262):
										$debit_critique_valide = 262;
										break;	
									case($debit_garanti > 262 && $debit_garanti <= 311):
										$debit_critique_valide = 311;
										break;		
									case($debit_garanti > 311 && $debit_garanti <= 359):
										$debit_critique_valide = 359;
										break;
									case($debit_garanti > 359 && $debit_garanti <= 400):
										$debit_critique_valide = 400;
										break;
									default: 
										$debit_critique_valide = "-";
								}
						}
						else if($debit_garanti >= 446 && $distance > 14){
							switch($debit_garanti){
									case($debit_garanti <= 84):
										$debit_critique_valide = 84;
										break;
									case($debit_garanti > 84 && $debit_garanti <= 105):
										$debit_critique_valide = 105;
										break;
									case($debit_garanti > 105 && $debit_garanti <= 132):
										$debit_critique_valide = 132;
										break;	
									case($debit_garanti > 132 && $debit_garanti <= 157):
										$debit_critique_valide = 157;
										break;		
									case($debit_garanti > 157 && $debit_garanti <= 180):
										$debit_critique_valide = 180;
										break;	
									case($debit_garanti > 180):
										$debit_critique_valide = 200;
										break;
									default: 
										$debit_critique_valide = "-";
								}
						}
						else if($debit_garanti < 446){
							switch($debit_garanti){
									case($debit_garanti <= 84):
										$debit_critique_valide = 84;
										break;
									case($debit_garanti > 84 && $debit_garanti <= 105):
										$debit_critique_valide = 105;
										break;
									case($debit_garanti > 105 && $debit_garanti <= 132):
										$debit_critique_valide = 132;
										break;	
									case($debit_garanti > 132 && $debit_garanti <= 157):
										$debit_critique_valide = 157;
										break;		
									case($debit_garanti > 157 && $debit_garanti <= 180):
										$debit_critique_valide = 180;
										break;	
									case($debit_garanti > 180):
										$debit_critique_valide = 200;
										break;
									default: 
										$debit_critique_valide = "-";
								}							
						}
						break;	
						
					default :
						$debit_critique_valide = "-";
				}
				
				
				
				/* Détermination de l'opération qui sera lancé (simplifié) */
				//$nb_fh vient du MODULE EXISTANT IQLINK (traité plus haut dans le code)
				if($nb_fh > 0){
					$ope = "RAN";
					$j = $nb_fh;
					while($j > 0){
						//regex insensible à la casse
						if(eregi("^AL2+",$data_fh[$j]['EQUIPEMENT'],$regs)	)			
							$secu = $data_fh[$j]['SECU'];
						$j--;
					}
					if(!$secu)
						$secu = $data_fh[0]['SECU'];
				}
				else 
					$ope = "NOUVEAU";
				
				
				// a mettre à jour pour prendre en compte mes débits validés 
				/* Détermination de la Conf. */
				switch($ope){
							case "RAN":
								if($type == "XPIC")
									$conf = "N8";
								else if($type == "TRANSPORT"){
									if($secu == "1+0")
										$conf = "N7";	
									else if($secu == "1+1" || $secu == "HSB")
										$conf = "N9";		
									else $conf = "-";	
								}
								else if($type == "RACCO")
									$conf = "N2";
								break;
							case "NOUVEAU":
								if($type == "XPIC")
									$conf = "N8";
								else if($type == "TRANSPORT")
									$conf = "N2/N9";
								else if($type == "RACCO")
									$conf = "N2";
								break;
								
							default:
								$conf = "-";
				}
				$this->template->assign_block_vars('display.result_config', array());
				
				if(count($tabSitesImpactes) > 0){								
						$this->template->assign_block_vars('display.result_config.content', array(	
							'CONF'					=> $conf,
							'DEB_PIC' 				=> ceil($debit_pic),
							'DEB_CRI' 				=> ceil($debit_garanti),
							'DEB_CRI_VAL' 			=> ceil($debit_critique_valide),
							'DEB_PIC_VAL' 			=> ceil($debit_pic_valide)
						));
				}
				else {
					$this->template->assign_block_vars('display.result_config.no_content', array());
				}		
				
				/**********************************************************
				 ** MODULE EXISTANT : RRCAP / RRTRANS
				 **********************************************************/
				$result_trans = $this->engine->getInfo_RRCAPRRTRANS($g2rA, $g2rB, array('A Muter', 'Réel', 'MHS', 'A Supprimer', 'Réservé'));
				$data_trans = $result_trans['data'];
				$nb_trans = $result_trans['nb'];

				$this->template->assign_block_vars('display.result_liens', array());
				
				if($nb_trans > 0) {								
					for($i = 0; $i < $nb_trans; $i++){	
						$this->template->assign_block_vars('display.result_liens.content', array(	
							'REFERENTIEL'				=> $data_trans[$i]['REFERENTIEL'],
							'LIEN' 						=> $data_trans[$i]['LIEN'],
							'G2R_A' 					=> $data_trans[$i]['G2R_1'],
							'EQUIPEMENT_A' 				=> $data_trans[$i]['NOEUD_1'],
							'G2R_B' 					=> $data_trans[$i]['G2R_2'],
							'EQUIPEMENT_B'				=> $data_trans[$i]['NOEUD_2'],
							'CAPACITE'					=> $data_trans[$i]['CAPACITE'],
							'TYPE'						=> $data_trans[$i]['TYPE'],
							'ETAT'						=> $data_trans[$i]['ETAT']
						));
					}
				}
				else {
					$this->template->assign_block_vars('display.result_liens.no_content', array());
				}
				
				/**********************************************************
				 ** MODULE EXISTANT : WORLD
				 **********************************************************/
				$result_world = $this->engine->getInfo_World($g2rA, $g2rB, "Réelle");
				$data_world = $result_world['data'];
				$nb_world = $result_world['nb'];
				
				$this->template->assign_block_vars('display.result_world', array());
				
				if($nb_world > 0) {								
					for($i = 0; $i < $nb_world; $i++){	
						$this->template->assign_block_vars('display.result_world.content', array(	
							'PRODUIT'					=> $data_world[$i]['PRODUIT'],
							'OPERATEUR_TIERS' 			=> $data_world[$i]['OPERATEUR_TIERS'],
							'REF_COMMERCIALE' 			=> $data_world[$i]['REF_COMMERCIALE'],
							'REF_TECHNIQUE' 			=> $data_world[$i]['REF_TECHNIQUE'],
							'G2R_A' 					=> $data_world[$i]['G2R_A'],
							'G2R_B'						=> $data_world[$i]['G2R_B'],
							'DEBIT'						=> $data_world[$i]['DEBIT'],
							'PROGRAMME'					=> $data_world[$i]['PROGRAMME'],
							'UTILISATION'				=> $data_world[$i]['UTILISATION'],
							'DISTANCE_FOURNISSEUR'		=> $data_world[$i]['DISTANCE_FOURNISSEUR'],
							'DISTANCE_SFR'				=> $data_world[$i]['DISTANCE_SFR'],
							'DATE_MAD'					=> $data_world[$i]['DATE_MAD']
						));
					}
				}
				else {
					$this->template->assign_block_vars('display.result_world.no_content', array());
				}


				/**********************************************************
				 ** MODULE CIBLE
				 **********************************************************/				
				$tabSitesImpactes = $this->search_sites_amont($g2rA, $g2rB);

				$this->template->assign_block_vars('display.result_cible', array(
					'NB_SITES'	=> count($tabSitesImpactes)
				));

				if(count($tabSitesImpactes) > 0){
					foreach($tabSitesImpactes as $site){
						$dataSite = $this->engine->getInfo_Site($site);
						$dataSiteCrozon = $this->engine->getInfo_Crozon($site);
						$data_site_crozon = $dataSiteCrozon['data'];

						$this->template->assign_block_vars('display.result_cible.content', array(
							'G2R'					=> $dataSite[0]['G2R'],
							'NOM'					=> $dataSite[0]['NOM'],
							'TYPE'					=> $dataSite[0]['TYPE'],
							'TYPE_RAN_SHARING'		=> $dataSite[0]['TYPE_RAN_SHARING'],
							'CONTOUR'				=> $data_site_crozon[0]['CONTOUR'],
							'ETAT'					=> $data_site_crozon[0]['STATUT']
						));
					}
				}
				else
					$this->template->assign_block_vars('display.result_cible.no_content', array());
					
					/**********************************************************
					 ** MODULE HISTORIQUE
					 **********************************************************/
					 
					 // Si l'utilisateur a les droits (pour suppression)
					$res = $this->engine->verifPerm($g2rA, $_SESSION['Login']); //$_POST['G2R_2_A']
					$res2 = $this->engine->verifPerm($g2rB, $_SESSION['Login']); //$_POST['G2R_2_B']
					
					if($res['nb_plaque'] <> 0 && $res2['nb_plaque'] <> 0)
						$act = "Action";
					else
						$act = "";
							
					if($res['nb_plaque'] <> 0 && $res2['nb_plaque'] <> 0)
						$img = "img/icon-delete.png";
					else
						$img = "";
						
					//Traitement pour affichage des Commentaires : *1 pour convertir les G2Rs en nombre
					$result_com = $this->engine->getComment_Liens($g2rA *1,$g2rB *1);
					$data_com = $result_com['data'];
					$nb_com = $result_com['nb'];
					
					$this->template->assign_block_vars('display.result_historique', array(
						'ACTION'				=> $act
					));
					
					if ($nb_com > 0) {
						for($i = 0; $i < $nb_com; $i++){
							$this->template->assign_block_vars('display.result_historique.content', array(						
								'DATE' 					=> $data_com[$i]['DATE_COM'],
								'COMMENTAIRES'			=> nl2br($data_com[$i]['COMMENTAIRES']),
								'CATEGORIE'				=> $data_com[$i]['CATEGORIE'],
								'UTILISATEUR'			=> $data_com[$i]['PRENOM'].' '.$data_com[$i]['NOM'],
								'G2R_A'					=> $g2rA,
								'G2R_B'					=> $g2rB,
								'IMAGE'					=> $img
							));	
						}	
					}
					else
						$this->template->assign_block_vars('display.result_historique.no_content', array());	
					
					if($res['nb_plaque'] <> 0 && $res2['nb_plaque'] <> 0)
						$this->displayAdd_Com_Liens($g2rA,$g2rB);
			}				

			$this->template->pparse('tpl_search_link');
		}

		/**
		 * Fonction displaySearch
		 * Permet d'afficher la page de recherche
		 */
		public function displaySearch(){
			$this->template->set_filenames(array(
		    	'tpl_search'		=> 'search.tpl',
				'tpl_menu'			=> 'menu.tpl'
	       	));
		
			$this->displayMenu();
		
			$find = false;
			$g2r = NULL;
		
			if(isset($_GET['G2R']) && !empty($_GET['G2R'])){
				$g2r = $_GET['G2R'];
				$find = true;
			}
			
			if(isset($_POST['G2R']) && !empty($_POST['G2R'])){
				$g2r = $_POST['G2R'];
				$find = true;
			}
			
			// Récupération du G2R saisi dans formulaire
			if($find){
				$this->template->assign_var('G2R', $g2r);
				
				$g2r_find = $g2r;
				
				/**********************************************************
				 ** MODULE SITE
				 **********************************************************/
				$data_site = $this->engine->getInfo_Site($g2r);
				
				if(count($data_site) > 0){
					foreach ($data_site as $result_site) {
						$this->template->assign_block_vars('result_site', array(
							'G2R_Site'				=> $result_site['G2R'],
							'NOM_SITE'				=> $result_site['G2R'] .' - '. $result_site['NOM'],
							'DPT'					=> $result_site['DPT'],
							'CODE'					=> $result_site['CODE_POSTAL'],
							'COMMUNE' 				=> $result_site['COMMUNE'],
							'TYPE' 					=> $result_site['TYPE'],
							'CODE_JV' 				=> $result_site['CODE'],
							'TYPE_RAN_SHARING' 		=> $result_site['TYPE_RAN_SHARING'],
							'CODE_INSEE' 			=> $result_site['CODE_INSEE'],
							'LAT' 					=> $result_site['LATITUDE'],
							'LONG' 					=> $result_site['LONGITUDE'],
							'X'						=> $result_site['X'],
							'Y'						=> $result_site['Y']
						));
					}
						
					/**********************************************************
					 ** MODULE CROZON
					 **********************************************************/
					$result_crozon = $this->engine->getInfo_Crozon($g2r);
					$data_crozon = $result_crozon['data'];
					$nb_crozon = $result_crozon['nb'];
					
					if($nb_crozon > 0) {								
						for($i = 0; $i < $nb_crozon; $i++){	
							$this->template->assign_block_vars('result_site.result_crozon', array(	
								'CODE_JV'				=> $data_crozon[$i]['CODE_JV'],
								'CODE_BYTEL'			=> $data_crozon[$i]['CODE_BYTEL'],
								'PLAQUE'				=> $data_crozon[$i]['PLAQUE'],
								'CONTOUR' 				=> $data_crozon[$i]['CONTOUR'],
								'STATUT' 				=> $data_crozon[$i]['STATUT'],
								'PATRIMOINE' 			=> $data_crozon[$i]['PATRIMOINE']
							));
						}
					}
					else {
						$this->template->assign_block_vars('result_site.no_result_crozon', array());
					}
					
					/**********************************************************
					 ** MODULE CARACTERISTIQUES SITE
					 **********************************************************/
					$result_attr_site = $this->engine->getInfo_Attributs_Site($g2r);
					$data_attr_site = $result_attr_site['data'];
					$nb_carac = $result_attr_site['nb'];
					
					if($nb_carac > 0) {						
						$this->template->assign_block_vars('result_site.result_caracteristiques_sites', array(	
							'NACELLE'				=> $data_attr_site['NACELLE']
						));
					}
					else {
						$this->template->assign_block_vars('result_site.no_result_caracteristiques_sites', array());
					}
					

					/**********************************************************
					 ** MODULE RADIO
					 **********************************************************/					
					$result_radio = $this->engine->getInfo_Radio($g2r);
					$data_radio = $result_radio['data'];
					$nb_radio = $result_radio['nb'];
					
					$this->template->assign_block_vars('result_radio', array());
					
					if($nb_radio > 0) {								
						for($i = 0; $i < $nb_radio; $i++){	
							$this->template->assign_block_vars('result_radio.content', array(	
								'CONSTRUCTEUR'			=> $data_radio[$i]['CONSTRUCTEUR'],
								'TECHNO' 				=> $data_radio[$i]['TECHNO'],
								'SYSTEME' 				=> $data_radio[$i]['SYSTEME'],
								'EQUIPEMENT' 			=> $data_radio[$i]['EQUIPEMENT'],
								'MODELE' 				=> $data_radio[$i]['MODELE'],
								'ETAT'					=> $data_radio[$i]['ETAT']
							));
						}
					}
					else {
						$this->template->assign_block_vars('result_radio.no_content', array());
					}
					
					/**********************************************************
					 ** MODULE CIBLE
					 **********************************************************/
					
					$result = $this->engine->getInfoG2R($g2r);
					$data = $result['data'];
					$nb = $result['nb'];
					
					$this->template->assign_block_vars('result_cible', array());
					
					if($result['nb'] > 0){
						if(!isset($data['Type_Support']))
							$data['Type_Support'] = '<i>non-renseigné</i>';
						
						if(!isset($data['Type_Techno']))
							$data['Type_Techno'] = '<i>non-renseigné</i>';
										
						$pere = intval($data['G2R']);
						
						$tabBeB = Array();

						do {
							$dataFils = $this->engine->getFils($pere);					
							$tabBeB[] = $dataFils;
							$pere = $dataFils['G2R_fils'];
						} while ($pere != null);

						// Cas coloc
						if(count($tabBeB) == 1)
							$tabBeB[] = $tabBeB[0];

						$displayBeB = '';
						
						foreach ($tabBeB as $i => $bond){
							$displayBeB .= $bond['G2R_pere'];
							
							if(count($tabBeB) > $i+1)
								$displayBeB .= ' > ';
						}
						
						// Récupération des informations associées au PARD
						$resultPARD = $this->engine->getInfoPARD($tabBeB[count($tabBeB)-1]['G2R_pere']);
						$dataPARD = $resultPARD['data'];
						
						$this->template->assign_block_vars('result_cible.content', array(
							'SUPPORT_TRANS'	 	=> $data['Type_Support'],
							'TECHNO_TRANS' 		=> $data['Type_Techno'],
							'PARD' 				=> $dataPARD['G2R_PARD'],
							'PARD_TYPE'			=> $dataPARD['Type_PARD'],
							'BEB' 				=> $displayBeB
						));
					}
					else
						$this->template->assign_block_vars('result_cible.no_content', array());
					
					/**********************************************************
					 ** MODULE EXISTANT
					 **********************************************************/
					if (strlen($g2r) == 5)
						$g2r = "0".$g2r;
					
					$result_existant = $this->engine->getInfoG2R_Existant($g2r);
					$data_existant = $result_existant['data'];
					$nb_existant = $result_existant['nb'];
										
					$this->template->assign_block_vars('result_existant', array());	
					
					if ($nb_existant > 0){	
						for($i = 0; $i < $nb_existant; $i++){
							if($data_existant[$i]['TECHNO_TRANS'] == "IP")
								$data_existant[$i]['E1'] = "-";
						
							if(!isset($data_existant[$i]['SUPPORT_TRANS']))
								$data_existant[$i]['SUPPORT_TRANS'] = '<i>non-renseigné</i>';
						
							if(!isset($data_existant[$i]['TECHNO_TRANS']))
								$data_existant[$i]['TECHNO_TRANS'] = '<i>non-renseignée</i>';
					
							$this->template->assign_block_vars('result_existant.content', array(						
								'CIRCUIT' 					=> $data_existant[$i]['CIRCUIT'],
								'ETAT_CIRCUIT'				=> $data_existant[$i]['CIRCUIT_ETAT'],
								'EQUIPEMENT'				=> $data_existant[$i]['EQUIPEMENT_RRCAP'],
								'SUPPORT_EXISTANT'			=> $data_existant[$i]['SUPPORT_TRANS'],
								'TECHNO_EXISTANT' 			=> $data_existant[$i]['TECHNO_TRANS'],
								'ADSL_EXISTANT' 			=> $data_existant[$i]['ADSL'],
								'E1_EXISTANT' 				=> $data_existant[$i]['E1'],
								'SYNCHRONISATION'			=> $data_existant[$i]['SYNCHRONISATION'],
								'CONTROLEUR_G2R'			=> $data_existant[$i]['CONTROLEUR_G2R'],
								'CONTROLEUR_EQUIPEMENT'		=> $data_existant[$i]['CONTROLEUR_EQUIPEMENT'],
								'RS' 						=> $data_existant[$i]['RAN_SHARING'],
								'PARD'						=> $data_existant[$i]['PARD'],
								'PARD_EQUIPEMENT' 			=> $data_existant[$i]['EQUIPEMENT_PARD']								
							));		
						}
					}
					else
						$this->template->assign_block_vars('result_existant.no_content', array());	
					
					/**********************************************************
					 ** MODULE HISTORIQUE
					 **********************************************************/
					 
					 // Si l'utilisateur a les droits (pour suppression)
					$res = $this->engine->verifPerm($g2r_find, $_SESSION['Login']); //$_POST['G2R']
					
					if($res['nb_plaque'] <> 0)
						$act = "Action";
					else
						$act = "";
							
					if($res['nb_plaque'] <> 0)
						$img = "img/icon-delete.png";
					else
						$img = "";
						
					// Traitement pour affichage des Commentaires : *1 pour convertir le G2R en nombre
					$result_com = $this->engine->getComment_G2R($g2r_find *1);
					$data_com = $result_com['data'];
					$nb_com = $result_com['nb'];
					
					$this->template->assign_block_vars('result_historique', array(
						'ACTION'				=> $act
					));
					
					if ($nb_com > 0) {
						for($i = 0; $i < $nb_com; $i++){
							$this->template->assign_block_vars('result_historique.content', array(						
								'DATE' 					=> $data_com[$i]['DATE_COM'],
								'COMMENTAIRES'			=> nl2br($data_com[$i]['COMMENTAIRES']),
								'CATEGORIE'				=> $data_com[$i]['CATEGORIE'],
								'UTILISATEUR'			=> $data_com[$i]['PRENOM'].' '.$data_com[$i]['NOM'],
								'G2R'					=> $g2r_find,
								'IMAGE'					=> $img
							));	
						}	
					}
					else
						$this->template->assign_block_vars('result_historique.no_content', array());	
					
					if($res['nb_plaque'] <> 0)
						$this->displayAdd_Com_G2R($g2r_find);		
				}
				// Site non trouvé dans table SITE
				else
					$this->template->assign_block_vars('no_result_default', array());
			}
			else
				$this->template->assign_var('G2R', '');	

			$this->template->pparse('tpl_search');	
		}
		
		/**
		 * Fonction displayAdd_Com_G2R
		 * Permet d'afficher le formulaire pour entrer des commentaires
		 */
		public function displayAdd_Com_G2R($g2r_find){
			$current_date = date("Y-m-d H:i:s");
		
			$this->template->assign_block_vars('result_historique.add_comments', array(
				'Current_Date'			=> $current_date,
				'G2R'					=> $g2r_find,
				'USER' 					=> $_SESSION['Login']
			));
		
			$dataCategory = $this->engine->getCategory_g2r();
			
			foreach ($dataCategory as $categ) {	
				$this->template->assign_block_vars('result_historique.add_comments.Category_com', array(
					'ID_CATEGORY'	=> $categ['ID'],
					'CATEGORY'		=> $categ['CATEGORIE']
				));
			}
		}
		
		/**
		 * Fonction displayAdd_Com_Liens
		 * Permet d'afficher le formulaire pour entrer des commentaires
		 */
		public function displayAdd_Com_Liens($g2r_a,$g2r_b){
			$current_date = date("Y-m-d H:i:s");
		
			$this->template->assign_block_vars('display.result_historique.add_comments', array(
				'Current_Date'			=> $current_date,
				'G2R_A'					=> $g2r_a,
				'G2R_B'					=> $g2r_b,
				'USER' 					=> $_SESSION['Login']
			));
		
			$dataCategory = $this->engine->getCategory_liens();
			
			foreach ($dataCategory as $categ) {	
				$this->template->assign_block_vars('display.result_historique.add_comments.Category_com', array(
					'ID_CATEGORY'	=> $categ['ID'],
					'CATEGORY'		=> $categ['CATEGORIE']
				));
			}
		}
		
		/**
		 * Fonction displaySearch_PARD
		 * Permet d'afficher la page de recherche pour les PARD
		 */
		public function displaySearch_PARD(){
			$this->template->set_filenames(array(
		    	'tpl_search'		=> 'search_PARD.tpl',
				'tpl_menu'			=> 'menu.tpl'
	       	));
		
			$this->displayMenu();
		
			$find = false;
		
			if(isset($_GET['PARD']) && !empty($_GET['PARD'])){
				$pard = $_GET['PARD'];
				$find = true;
			}
			
			if(isset($_POST['PARD']) && !empty($_POST['PARD'])){
				$pard = $_POST['PARD'];
				$find = true;
			}
			
			if($find){
				$this->template->assign_var('PARD', $pard);
				
				$result = $this->engine->getInfoPARD($pard);
				$data = $result['data'];
				
				if($result['nb'] > 0){			
					$this->template->assign_block_vars('result', array(
						'NUM_PARD' 			=> $data['G2R_PARD'].' - '.$data['Nom'], 
						'PARD_TYPE'			=> $data['Type_PARD'],
						'ETAT_PARD'			=> $data['Etat_PARD'],
						'ETAT_PEFTTS'		=> $data['Etat_PEFTTS']
					));
				}
				else
					$this->template->assign_block_vars('no_result', array());
			} 
			else
				$this->template->assign_var('PARD', '');	
			
	       	$this->template->pparse('tpl_search');
		}	
		
		/**
		 * Fonction displayEditForm_Site
		 * Permet d'afficher la page de modification des caractéristiques d'un site
		 */
		public function displayEditForm_Site(){
			$this->template->set_filenames(array(
		    	'tpl_edit'			=> 'edit_site.tpl',
				'tpl_menu'			=> 'menu.tpl'
	       	));
		
			$this->displayMenu();
	
			$find = false;
			$g2r = NULL;
		
			if(isset($_GET['G2R']) && !empty($_GET['G2R'])){
				$g2r = $_GET['G2R'];
				$find = true;
			}
			
			if(isset($_POST['G2R']) && !empty($_POST['G2R'])){
				$g2r = $_POST['G2R'];
				$find = true;
			}
			
			//Récupération du G2R
			if($find){
				$this->template->assign_var('G2R', $g2r);
				
				$g2r_find = $g2r;		
			}	
			
	       	$this->template->pparse('tpl_edit');
		}	
		
		/**
		 * Fonction displayList
		 * Permet d'afficher la page de listing des saisies
		 */
		public function displayList(){
			$this->template->set_filenames(array(
		    	'tpl_list'			=> 'list.tpl',
				'tpl_menu'			=> 'menu.tpl'
	       	));
		
			$this->displayMenu();
		
			$data = $this->engine->getListG2R();

			$this->template->assign_var('NB_SITES', count($data));

			foreach ($data as $site) {
				$displayTrans = '&#x2717;';
				$displayBeB = '&#x2717;';
				
				if($site['trans'] == 1)
					$displayTrans = '&#x2713;';
					
				if($site['beb'] == 1)
					$displayBeB = '&#x2713;';
				
				$this->template->assign_block_vars('result', array(
					'PLAQUE'	=> $site['Plaque'],
					'DPT'		=> $site['DPT'],
					'DPT'		=> $site['DPT'],
					'G2R' 		=> $site['G2R'],
					'NOM' 		=> $site['nom'],
					'BEB' 		=> $displayBeB,
					'TRANS' 	=> $displayTrans
				));
			}
			
	       	$this->template->pparse('tpl_list');
		}	

		/**
		 * Fonction displayList_PARD
		 * Permet d'afficher la page de listing des PARD
		 */
		public function displayList_PARD(){
			$this->template->set_filenames(array(
		    	'tpl_list'			=> 'list_PARD.tpl',
				'tpl_menu'			=> 'menu.tpl'
	       	));
		
			$this->displayMenu();
		
			$data = $this->engine->getListPARD(false);

			$this->template->assign_var('NB_PARD', count($data));

			foreach ($data as $pard) {
				$this->template->assign_block_vars('result', array(
					'PLAQUE'		=> $pard['Plaque'],
					'DPT'			=> $pard['DPT'],
					'G2R_PARD' 		=> $pard['G2R_PARD'],
					'NOM' 			=> $pard['Nom'],
					'PARD_TYPE' 	=> $pard['Type_PARD'],
					'ETAT_PARD'		=> $pard['Etat_PARD'],
					'ETAT_PEFTTS'	=> $pard['Etat_PEFTTS']
				));
			}
			
	       	$this->template->pparse('tpl_list');
		}
		
		/**
		 * Fonction displayFormAdd
		 * Permet d'afficher le formulaire permettant d'ajouter un BeB
		 */
		public function displayFormAdd(){
			$this->template->set_filenames(array(
		    	'tpl_insert'		=> 'insert.tpl',
				'tpl_menu'			=> 'menu.tpl'
	       	));
		
			$this->displayMenu();	
			
			$dataSupport = $this->engine->getSupport();
			
			foreach ($dataSupport as $support) {	
				$this->template->assign_block_vars('Support', array(
					'ID_SUPPORT'	=> $support['ID_Support'],
					'SUPPORT'		=> $support['Type_Support']	
				));
			}
		
			$dataTechno = $this->engine->getTechno();
			
			foreach ($dataTechno as $techno) {	
				$this->template->assign_block_vars('Techno', array(
					'ID_TECHNO'	=> $techno['ID_Techno'],
					'TECHNO'	=> $techno['Type_Techno']
				));
			}
			
			$check = false;

			if(isset($_POST) && !empty($_POST)) 
				$data = $_POST;
				
			if(isset($_SESSION['data']) && !empty($_SESSION['data']))  
				$data = $_SESSION['data'];  

			// Le formulaire a été rempli (valider les données)
			if(isset($data))
				$check = true;

			// L'utilisateur modifie son formulaire (ne pas valider les données)
			if(isset($data['retry']) && $data['retry'] == true)
				$check = false;

			// L'utilisateur confirme l'écrasement des les données (re-valideer les données)
			if(isset($_POST['confirm']) && !empty($_POST['confirm']))
				$check = true;

			// Ne pas valider les données, donc afficher le formulaire de saisie
			if(!$check) {
				// Vider les données en session
				$_SESSION['data'] = null;

				$dataPARD = $this->engine->getListPARD();

				$nextPARDType = '';
				
				foreach ($dataPARD as $PARD) {
					if($nextPARDType != $PARD['Type_PARD']){
						$this->template->assign_block_vars('category', array(
							'CATEGORIE' => $PARD['Type_PARD'])
						);
					}
					
					$this->template->assign_block_vars('category.PARD', array(
						'G2R' => $PARD['G2R_PARD'],
						'NOM' => $PARD['Nom'],
						'PARD_TYPE' => $PARD['Type_PARD']
					));

					$nextPARDType = $PARD['Type_PARD'];
				}
			}
			// Sinon, Vérifier les données dans $data 
			else {
				// Données remplies dans la fonction "submit"
				$error = false;
				$warning = false;
				$msg = null;
				
				$tabResult = $this->submitFormG2R($error, $warning, $msg, $data);

				$error = $tabResult[0];
				$warning = $tabResult[1];
				$msg = $tabResult[2];
					
				if(!$error && !$warning) {
					// Vider les données en session
					$_SESSION['data'] = null;
					
					/*
						// Afficher un message de confirmation & Rediriger l'utilisateur
						$this->template->set_filenames(array(
							'tpl_display_msg' 	=> 'display_msg.tpl'
						));
						
						$this->template->assign_block_vars('redirect', array(
							'G2R' => $data['G2R']
						));	
							
						$this->template->assign_block_vars('confirmationBEB', array(
							'MSG' => 'Le BEB a bien été ajouté'
						));
							
						$this->template->pparse('tpl_display_msg');
					*/
					
					header('Location: index.php?p=1&G2R='.$data['G2R']);
				} 
				else {
					// On propose à l'utilisateur de modifier son formulaire
					$data['retry'] = true;

					// Stockage des données en session
					$_SESSION['data'] = $data;

					// Afficher le template de confirmation / erreur
					$this->template->set_filenames(array(
						'tpl_display_msg' 	=> 'display_msg.tpl'
					));
					
					if($error){
						$this->template->assign_block_vars('error', array(
							'MSG' => 'Les données saisies sont erronées : '.'\n\n'.$msg
						));
					}
					
					if($warning){
						$this->template->assign_block_vars('warning', array(
							'MSG' => 'Attention, le BeB que vous souhaitez insérer impacte la cible trans des sites suivants : '.'\n\n'.$msg.'\n'.' Voulez-vous continuer ?'
						));
					}
					
					$this->template->pparse('tpl_display_msg');
				}
			}			
			
	       	$this->template->pparse('tpl_insert');
		}	
		
		/**
		 * Fonction displayFormAdd_PARD
		 * Permet d'afficher le formulaire permettant d'ajouter un PARD
		 */
		public function displayFormAdd_PARD(){			
			if (isset($_GET['PARD']) && !empty($_GET['PARD']))
				$pard = $_GET['PARD'];
			else 
				$pard = '';
			
			$this->template->set_filenames(array(
		    	'tpl_insert'		=> 'insert_PARD.tpl',
				'tpl_menu'			=> 'menu.tpl'
	       	));
			
			$this->displayMenu();
		
			$this->template->assign_vars(array(	
				'PARD' 			=> $pard
			));
			
			$dataEtat = $this->engine->getEtat();
			
			foreach ($dataEtat as $etat) {	
				$this->template->assign_block_vars('etat_pard', array(
					'ID_ETAT'	=> $etat['ID_Etat'],
					'ETAT'		=> $etat['Etat']
				));
			}
			
			$check = false;

			if(isset($_POST) && !empty($_POST))
				$data = $_POST;
			
			// Le formulaire a été rempli (valider les données)
			if(isset($data))
				$check = true;

			// L'utilisateur modifie son formulaire (ne pas valider les données)
			if(isset($data['retry']) && $data['retry'] == true)
				$check = false;

			// L'utilisateur confirme l'écrasement des les données (re-valideer les données)
			if(isset($_POST['confirm']) && !empty($_POST['confirm']))
				$check = true;

			// Ne pas valider les données, donc afficher le formulaire de saisie
			if(!$check) {
				// Vider les données en session
				unset ($_POST);
			}				
			else {
				// Données remplies dans la fonction "submit"
				$error = false;
				$warning = false;
				$msg = null;

				$tabResult = $this->submitFormPARD($error, $warning, $msg, $data);

				$error = $tabResult[0];
				$warning = $tabResult[1];
				$msg = $tabResult[2];
				
				if(!$error && !$warning) {
					// Vider les données en session	
					unset ($_POST); 
					
					// Afficher message de confirmation & Rediriger l'utilisateur 
					/*
						$this->template->set_filenames(array(
							'tpl_display_msg' 	=> 'display_msg.tpl'
						));
						
						$this->template->assign_block_vars('redirect', array(
								'PARD' => $data['G2R_PARD']
						));	
							
						$this->template->assign_block_vars('confirmationPARD', array(
								'MSG' => 'Le PARD a bien été ajouté'
						));
						
						$this->template->pparse('tpl_display_msg');
					*/
					
					header('Location: index.php?p=4&q=1&PARD='.$data['G2R_PARD']); 
				} 
				else {
					// On propose à l'utilisateur de modifier son formulaire
					$data['retry'] = true;

					// Stockage des données en session
					$_SESSION['data'] = $data;

					// Afficher le template de confirmation / erreur
					$this->template->set_filenames(array(
						'tpl_display_msg' 	=> 'display_msg.tpl'
					));
					
					if($error){
						$this->template->assign_block_vars('error', array(
							'MSG' => 'Les données saisies sont erronées : '.'\n\n'.$msg
						));
					}
					
					$this->template->pparse('tpl_display_msg');
				}			
			}						
	       	$this->template->pparse('tpl_insert');
		}	
		
		/**
		 * Fonction importRSU
		 * Permet de mettre à jour la table RSU
		 */
		public function importRSU(){
			// Récupération du nb d'enregistrements contenus dans RSU
			$result = $this->engine->checkDataRSU();
			var_dump($result);
			
			// Données RSU complètes : OK => MAJ des données dans BDD Cible
			if ($result > 0) {
				$this->engine->deleteSite();		
				$this->engine->importSite();
			}
		}
		
		/**
		 * Fonction exportDataRow
		 * Permet d'exporter certaines données des tables au format .csv avec le Beb en ligne
		 */
		public function exportDataRow(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_row.tpl'
	       	));

			$dataSites = $this->engine->getExportRow();
			$dataBeB = $this->engine->getBeB();

			foreach ($dataSites as $site) {
				$pere = $site['G2R'];
				$tabBeB = Array();
				
				do {
					$tabBeB[] = $pere;
					$pere = $dataBeB[$pere];
				} while (!is_null($pere));

				$tabBeB = array_reverse($tabBeB) + array_fill(0, 12, '');
				
				if ($tabBeB[1] == null)
					$tabBeB[1] = $site['G2R'];
				
				$resultPARD = $this->engine->getInfoPARD($tabBeB[0]);
				$dataPARD = $resultPARD['data'];
				
				// 'TECHNO_RADIO'	 	=> $site['techno_radio'],
				
				$this->template->assign_block_vars('site', array(
					'PLAQUE'			=> $site['Plaque'],
					'DPT'				=> $site['DPT'],
					'G2R' 				=> $site['G2R'],
					'NOM' 				=> $site['nom'],
					'SUPPORT' 			=> $site['Type_support'],
					'TECHNO_TRANS' 		=> $site['Type_techno'],
					'PARD_TYPE' 		=> $dataPARD['Type_PARD'],
					'ETAT_PARD'			=> $dataPARD['Etat_PARD'],
					'ETAT_PEFTTS'		=> $dataPARD['Etat_PEFTTS'],
					'PARD' 				=> $tabBeB[0],
					'SITE_2'			=> $tabBeB[1],
					'SITE_3' 			=> $tabBeB[2],
					'SITE_4' 			=> $tabBeB[3],
					'SITE_5' 			=> $tabBeB[4],
					'SITE_6' 			=> $tabBeB[5],
					'SITE_7' 			=> $tabBeB[6],
					'SITE_8' 			=> $tabBeB[7],
					'SITE_9' 			=> $tabBeB[8],
					'SITE_10' 			=> $tabBeB[9],
					'SITE_11'			=> $tabBeB[10],
					'SITE_12'			=> $tabBeB[11],
					'X'					=> $site['X'],
					'Y'					=> $site['Y']		
				));
				
				
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_cible_trans_'.date("d-m-Y_H-m-s").'.csv"');
		   
	       	$this->template->pparse('tpl_export');
		}			
		
		/**
		 * Fonction exportDataRowCompressed
		 * Permet d'exporter certaines données des tables au format .csv avec le Beb en ligne, version compressé
		 */
		public function exportDataRowCompressed(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_row_compressed.tpl'
	       	));

			$dataSites = $this->engine->getExportRow();
			$dataBeB = $this->engine->getBeB();

			foreach ($dataSites as $site) {
				$pere = $site['G2R'];
				$tabBeB = Array();
				
				do {
					$tabBeB[] = $pere;
					$pere = $dataBeB[$pere];
				} while (!is_null($pere));

				$tabBeB = array_reverse($tabBeB);
				
				if ($tabBeB[1] == null)
					$tabBeB[1] = $site['G2R'];
				
				$resultPARD = $this->engine->getInfoPARD($tabBeB[0]);
				$dataPARD = $resultPARD['data'];
				
				$beb = implode(" > ", array_reverse($tabBeB));

				$this->template->assign_block_vars('site', array(
					'PLAQUE'			=> $site['Plaque'],
					'DPT'				=> $site['DPT'],
					'G2R' 				=> $site['G2R'],
					'NOM' 				=> $site['nom'],
					'SUPPORT' 			=> $site['Type_support'],
					'TECHNO_TRANS' 		=> $site['Type_techno'],
					'PARD_TYPE' 		=> $dataPARD['Type_PARD'],
					'ETAT_PARD' 		=> $dataPARD['Etat_PARD'],
					'ETAT_PEFTTS' 		=> $dataPARD['Etat_PEFTTS'],
					'PARD' 				=> $dataPARD['G2R_PARD'],
					'BEB'				=> $beb,
					'X'					=> $site['X'],
					'Y'					=> $site['Y']				
				));
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_cible_trans_'.date("d-m-Y_H-m-s").'.csv"');
		   
	       	$this->template->pparse('tpl_export');
		}
		
		/**
		 * Fonction exportDataCol
		 * Permet d'exporter certaines données des tables au format .csv avec le Beb en colonne
		 */
		public function exportDataCol(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_col.tpl'
	       	));

			
			$dataSites = $this->engine->getExportCol();
			$dataBeB = $this->engine->getBeB();
			
			foreach ($dataSites as $site) {
				$pere = $site['G2R'];
				$tabBeB = Array();
				
				do {
					$tabBeB[] = $pere;
					$pere = $dataBeB[$pere];
				} while (!is_null($pere));
			
				$pere = $site['G2R'];
				$i = 0;
				//$i = 1; //initial

				do {
					$fils = $tabBeB[$i];
					$i++;
					
					if ($fils == null)
						$fils = $pere;

					// probleme avec valeur de $pere et $fils
					$this->template->assign_block_vars('site', array(
						'PLAQUE'			=> $site['Plaque'],
						'DPT'				=> $site['DPT'],
						'G2R' 				=> $site['G2R'],
						'NOM' 				=> $site['nom'],
						'G2R_1'				=> $pere,
						'G2R_2' 			=> $fils
					));	
					
					$pere = $fils;			
				} while ($tabBeB[$i] != null);			
				
			}
		
			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_cible_trans_colonne_'.date("d-m-Y_H-m-s").'.csv"');
		   
	       	$this->template->pparse('tpl_export');
		}			
			
		/**
		 * Fonction exportData_PARD
		 * Permet d'exporter certaines données des PARD au format .csv
		 */
		public function exportData_PARD(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_PARD.tpl'
	       	));
		
			$dataPard = $this->engine->getExport_PARD();

			foreach ($dataPard as $Pard) {
				$this->template->assign_block_vars('pard', array(	
					'G2R_PARD' 			=> $Pard['G2R_PARD'],
					'PARD_TYPE' 		=> $Pard['type_PARD'],
					'PLAQUE'			=> $Pard['Plaque'],
					'DPT'				=> $Pard['DPT'],
					'NOM'				=> $Pard['nom'],
					'ETAT_PARD'			=> $Pard['Etat_PARD'],
					'ETAT_PEFTTS'		=> $Pard['Etat_PEFTTS']
				));
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_cible_trans_PARD_'.date("d-m-Y_H-m-s").'.csv"');
		
	       	$this->template->pparse('tpl_export');
		}			
		
		/**
		 * Fonction exportData_Existant
		 * Permet d'exporter certaines données de la BDD Existant au format .csv
		 */
		public function exportData_Existant(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_Existant.tpl'
	       	));
		
			$data = $this->engine->getExport_Existant();

			foreach ($data as $result) {
				$this->template->assign_block_vars('resultat', array(
					'G2R'							=> $result['G2R_RADIO'],
					'CIRCUIT'						=> $result['CIRCUIT'],
					'CIRCUIT_ETAT' 					=> $result['CIRCUIT_ETAT'],
					'EQUIPEMENT' 					=> $result['EQUIPEMENT'], 
					'EQUIPEMENT_RRCAP' 				=> $result['EQUIPEMENT_RRCAP'],
					'E1' 							=> $result['E1'],
					'SUPPORT_TRANS' 				=> $result['SUPPORT_TRANS'],
					'TECHNO_TRANS' 					=> $result['TECHNO_TRANS'],
					'ADSL' 							=> $result['ADSL'],
					'SYNCHRONISATION' 				=> $result['SYNCHRONISATION'],
					'PARD' 							=> $result['PARD'],
					'PARD_EQUIPEMENT' 				=> $result['EQUIPEMENT_PARD'],
					'CONTROLEUR_G2R' 				=> $result['CONTROLEUR_G2R'],
					'CONTROLEUR_EQUIPEMENT'	 		=> $result['CONTROLEUR_EQUIPEMENT'],
					'RAN_SHARING' 					=> $result['RAN_SHARING']
				));
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_existant_trans_'.date("d-m-Y_H-m-s").'.csv"');
		
	       	$this->template->pparse('tpl_export');
		}
		
		/**
		 * Fonction exportData_Site
		 * Permet d'exporter les données des "Sites" de la BDD Cible au format .csv
		 */
		public function exportData_Site(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_Site.tpl'
	       	));
		
			$data = $this->engine->getExport_Site();

			foreach ($data as $result) {
				$this->template->assign_block_vars('resultat', array(
					'G2R'							=> $result['G2R'],
					'SITE'							=> $result['NOM'],
					'DPT'							=> $result['DPT'],
					'CODE'							=> $result['CODE_POSTAL'],
					'COMMUNE' 						=> $result['COMMUNE'],
					'CODE_INSEE' 					=> $result['CODE_INSEE'],
					'LAT' 							=> $result['LATITUDE'],
					'LONG' 							=> $result['LONGITUDE'],
					'X'								=> $result['X'],
					'Y'								=> $result['Y']
				));
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_site_trans_'.date("d-m-Y_H-m-s").'.csv"');
		
	       	$this->template->pparse('tpl_export');
		}
		
		/**
		 * Fonction exportData_Radio
		 * Permet d'exporter les données Radio de la BDD Existant au format .csv
		 */
		public function exportData_Radio(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_Radio.tpl'
	       	));
		
			$data = $this->engine->getExport_Radio();  

			foreach ($data as $result) {
				$this->template->assign_block_vars('resultat', array(
					'G2R'							=> $result['G2R'],
					'CONSTRUCTEUR'					=> $result['CONSTRUCTEUR'],
					'TECHNO'						=> $result['TECHNO'],
					'SYSTEME' 						=> $result['SYSTEME'],
					'EQUIPEMENT' 					=> $result['EQUIPEMENT'],
					'MODELE' 						=> $result['MODELE']
				));
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_radio_'.date("d-m-Y_H-m-s").'.csv"');
		
	       	$this->template->pparse('tpl_export');
		}
		
		/**
		 * Fonction exportData_Equipements
		 * Permet d'exporter les données Equipements de la BDD Existant au format .csv
		 */
		public function exportData_Equipements(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_Equipements.tpl'
	       	));
		
			$data = $this->engine->getExport_Equipements();  

			foreach ($data as $result) {
				$this->template->assign_block_vars('resultat', array(
					'G2R'					=> $result['G2R'],
					'ALIAS'					=> $result['ALIAS'],
					'EQUIPEMENT'			=> $result['EQUIPEMENT'],
					'CONFIG' 				=> $result['CONFIG'],
					'ETAT' 					=> $result['ETAT']
				));
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_equipements_'.date("d-m-Y_H-m-s").'.csv"');
		
	       	$this->template->pparse('tpl_export');
		}
		
		/**
		 * Fonction exportData_Iqlink
		 * Permet d'exporter les données Iqlink de la BDD Existant au format .csv
		 */
		public function exportData_Iqlink(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_Iqlink.tpl'
	       	));
		
			$data = $this->engine->getExport_Iqlink();  

			foreach ($data as $result) {
				$this->template->assign_block_vars('resultat', array(
					'LOPID'						=> $result['LOPID'],
					'VERSION'					=> $result['VERSION'],
					'LINK_ID'					=> $result['LINK_ID'],
					'ETAT' 						=> $result['ETAT'],
					'CLE_FH' 					=> $result['CLE_FH'],
					'G2R_A' 					=> $result['G2R_A'],
					'G2R_B'						=> $result['G2R_B'],
					'DISTANCE'					=> $result['DISTANCE'],
					'FOURNISSEUR'				=> $result['FOURNISSEUR'],
					'EQUIPEMENT' 				=> $result['EQUIPEMENT'],
					'SECU' 						=> $result['SECU'],
					'CAPACITE' 					=> $result['CAPACITE'],
					'FREQUENCE'					=> $result['FREQUENCE'],
					'CANALISATION'				=> $result['CANALISATION'],
					'CANAL'						=> $result['CANAL'],
					'POLAR_1' 					=> $result['POLAR_1'],
					'POLAR_2' 					=> $result['POLAR_2'],
					'DIAMETRE_A' 				=> $result['DIAMETRE_A'],
					'DIAMETRE_B' 				=> $result['DIAMETRE_B'],
					'HT_INSTALL_A' 				=> $result['HT_INSTALL_A'],
					'HT_INSTALL_B' 				=> $result['HT_INSTALL_B']
				));
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_iqlink_'.date("d-m-Y_H-m-s").'.csv"');
		
	       	$this->template->pparse('tpl_export');
		}

		/**
		 * Fonction exportData_World
		 * Permet d'exporter les données World de la BDD Existant au format .csv
		 */
		public function exportData_World(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_World.tpl'
	       	));
		
			$data = $this->engine->getExport_World();  

			foreach ($data as $result) {
				$this->template->assign_block_vars('resultat', array(
					'PRODUIT'					=> $result['PRODUIT'],
					'OPERATEUR_TIERS'			=> $result['OPERATEUR_TIERS'],
					'REF_COMMERCIALE'			=> $result['REF_COMMERCIALE'],
					'REF_TECHNIQUE' 			=> $result['REF_TECHNIQUE'],
					'G2R_A' 					=> $result['G2R_A'],
					'G2R_B'						=> $result['G2R_B'],
					'DEBIT'						=> $result['DEBIT'],
					'ETAT'						=> $result['ETAT'],
					'PROGRAMME' 				=> $result['PROGRAMME'],
					'UTILISATION' 				=> $result['UTILISATION'],
					'DISTANCE_FOURNISSEUR' 		=> $result['DISTANCE_FOURNISSEUR'],
					'DISTANCE_SFR'				=> $result['DISTANCE_SFR'],
					'DATE_MES'					=> $result['DATE_MES'],
					'DATE_MAD'					=> $result['DATE_MAD'],
					'DATE_MAD_REELLE' 			=> $result['DATE_MAD_REELLE'],
					'DATE_SUPPRESSION_REELLE'	=> $result['DATE_SUPPRESSION_REELLE']
				));
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_world_'.date("d-m-Y_H-m-s").'.csv"');
		
	       	$this->template->pparse('tpl_export');
		}
		
		/**
		 * Fonction exportData_PTC
		 * Permet d'exporter les données PTC de la BDD Existant au format .csv
		 */
		public function exportData_PTC(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_PTC.tpl'
	       	));
		
			$data = $this->engine->getExport_PTC();  

			foreach ($data as $result) {
				$this->template->assign_block_vars('resultat', array(
					'G2R'					=> $result['G2R'],
					'NOM'					=> $result['NOM'],
					'TYPE'					=> $result['TYPE']
				));
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_ptc_'.date("d-m-Y_H-m-s").'.csv"');
		
	       	$this->template->pparse('tpl_export');
		}

		/**
		 * Fonction exportData_Comment_G2R
		 * Permet d'exporter les commentaires pour chaque G2R au format .csv
		 */
		public function exportData_Comment_G2R(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_Historique_G2R.tpl'
	       	));
		
			$data = $this->engine->getExport_Com_G2R();

			foreach ($data as $result) {
				$com = $result['Commentaires'];
				
				$com = str_replace(CHR(10)," ",$com); 
				$com = str_replace(CHR(13)," ",$com); 
				
				$this->template->assign_block_vars('Historique', array(	
					'DATE'						=> $result['Date_Com'],
					'G2R'						=> $result['G2R'],
					'CATEGORIE' 				=> $result['CATEGORIE'],
					'RESPONSABLE' 				=> $result['PRENOM'].' '.$result['NOM'],
					'COMMENTAIRES' 				=> $com
				));
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_historique_G2R_trans_'.date("d-m-Y_H-m-s").'.csv"');
		
	       	$this->template->pparse('tpl_export');
		}
		
		/**
		 * Fonction exportData_Comment_Liens
		 * Permet d'exporter les commentaires pour chaque lien au format .csv
		 */
		public function exportData_Comment_Liens(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_Historique_Liens.tpl'
	       	));
		
			$data = $this->engine->getExport_Com_Liens();

			foreach ($data as $result) {
				$com = $result['Commentaires'];
				
				$com = str_replace(CHR(10)," ",$com); 
				$com = str_replace(CHR(13)," ",$com); 
				
				$this->template->assign_block_vars('Historique', array(	
					'DATE'						=> $result['Date_Com'],
					'G2R_A'						=> $result['G2R_A'],
					'G2R_B'						=> $result['G2R_B'],
					'CATEGORIE' 				=> $result['CATEGORIE'],
					'RESPONSABLE' 				=> $result['PRENOM'].' '.$result['NOM'],
					'COMMENTAIRES' 				=> $com
				));
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_historique_Liens_trans_'.date("d-m-Y_H-m-s").'.csv"');
		
	       	$this->template->pparse('tpl_export');
		}
		
		/**
		 * Fonction exportRapport_PeuplementBDDCible
		 * Permet d'exporter les données du rapport exécuté à la volée concernant le niveau de peuplement de la BDD Cible, au format .csv
		 */
		public function exportRapport_PeuplementBDDCible(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_Peuplement.tpl'
	       	));
		
			$data = $this->engine->getExport_PeuplementBDDCible();  

			foreach ($data as $result) {
				$this->template->assign_block_vars('resultat', array(
					'G2R'						=> $result['G2R'],
					'RESPONSABLE'				=> $result['PRENOM'],
					'DPT'						=> $result['DPT'],
					'NOM_SITE'					=> $result['SITE']
				));
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_peuplementCible_'.date("d-m-Y_H-m-s").'.csv"');
		
	       	$this->template->pparse('tpl_export');
		}
		
		/**
		 * Fonction exportRapport_FH
		 * Permet d'exporter les données du rapport exécuté à la volée concernant les FH à lancer au format .csv
		 */
		public function exportRapport_FH(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_FH.tpl'
	       	));

			$data_fh = $this->generateRapportFH();
			
			foreach ($data_fh as $result) {
				$this->template->assign_block_vars('resultat', array(
					'PLAQUE'				=> $result['Plaque'],
					'DPT'					=> $result['DPT'],
					'G2R'					=> $result['G2R'],
					'NOM'					=> $result['nom'],
					'CLE_FH'				=> $result['CLE_FH'],
					'G2R_B'					=> $result['G2R_pere'],
					'G2R_A'					=> $result['G2R_fils'],
					'NB_SITES'				=> $result['NB_SITES'],
					'EQUIPEMENT'			=> $result['EQUIPEMENT'],
					'ETAT'					=> $result['ETAT']
				));
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_fh_'.date("d-m-Y_H-m-s").'.csv"');
		
	       	$this->template->pparse('tpl_export');
		}
		
		/**
		 * Fonction exportRapport_IP
		 * Permet d'exporter les données du rapport exécuté à la volée concernant le potentiel IP de chaque G2R
		 */
		public function exportRapport_IP(){
			$this->template->set_filenames(array(
		    	'tpl_export'		=> 'export_csv_IP.tpl'
	       	));
				
			$table = $this->generateTmpTableRapport_FH();
			
			$data_ip = $this->generateRapportIP();		
			
			foreach ($data_ip as $result) {
				$this->template->assign_block_vars('resultat', array(
					'G2R'					=> $result['G2R'],
					'SUPP_EXIST'			=> $result['SUPPORT_EXISTANT'],
					'PARD_EXIST'			=> $result['PARD_EXISTANT'],
					'SUPP_CIBLE'			=> $result['SUPPORT_CIBLE'],
					'PARD_CIBLE'			=> $result['PARD_CIBLE'],
					'ETAT'					=> $result['ETAT']
				));
			}

			header('Content-type: text/csv');
			header('Content-Disposition: attachment; filename="export_potentielIP_'.date("d-m-Y_H-m-s").'.csv"');
		
	       	$this->template->pparse('tpl_export');
			
			$this->engine->deleteTmpTableRapport_FH($table);	
		}
		
		/**
		 * Fonction Pourentage
		 * Permet d'afficher un nombre en %
		 */
		public function Pourcentage($Nombre, $Total) {
			$value = number_format($Nombre * 100 / $Total, 2);
			
			return $value .'%';
		}
		
		/**
		 * Fonction Distance
		 * Permet de calculer la distance entre deux sites radio 
		 * @param g2rA , g2rB : les deux sites
		 * @return la distance entre les deux sites rentrés en paramètres
		 */
		public function Distance($g2rA, $g2rB) {
			$coord_g2rA = $this->engine->getCoord($g2rA);
			$coord_g2rB = $this->engine->getCoord($g2rB);
			
			$long = pow(($coord_g2rB['X'] - $coord_g2rA['X']),2) + pow(($coord_g2rB['Y'] - $coord_g2rA['Y']),2);
			$distance = sqrt($long);
			$distance = number_format($distance/1000,2);
			
			return $distance;
		}
		
		
		/**
		 * Fonction pereFils
		 * Permet de déterminer si le segment FH correspond aux G2R indiqués en paramètres
		 * @param pere, fils
		 * @return vrai si le segment est retrouvé dans la BDD et que le père correspond bien au père indiqué en paramètre
		 */
		public function pereFils($pere, $fils){
			$dataFils = $this->engine->getFils($pere);

			if(count($dataFils) > 0 && $dataFils['G2R_fils'] == $fils)
				return true;
			else
				return false;
		}


		/**
		 * Fonction search_sites_amont
		 * Permet de récupérer la liste des sites associés à un segment FH
		 * @param g2rA, g2rB : extrémités du segment FH recherché
		 * @return un tableau des sites impactés si le segment existe en base, autrement null.
		 */
		public function search_sites_amont($g2rA, $g2rB){
			if($this->pereFils($g2rA, $g2rB)){
				$pere = $g2rA;
				$fils = $g2rB;

				$find = true;
			}
			else if($this->pereFils($g2rB, $g2rA)){
				$pere = $g2rB;
				$fils = $g2rA;

				$find = true;
			}
			else
				$find = false;

			if($find){								
				$beb = $this->engine->getBeB(); 

				$sites_fils = array($pere);
				$liste_sites_amont = array($pere);

				do {
					$sites_pere = array();

					foreach ($sites_fils as $fils) {
						$sites_pere = array_merge($sites_pere, array_keys($beb, $fils));
					}
					
					$sites_fils = $sites_pere;
					$liste_sites_amont = array_merge($liste_sites_amont, $sites_pere);
				} while(count($sites_pere) > 0);

				asort($liste_sites_amont);

				return $liste_sites_amont;
			}
			else
				return null;
		}	

		/**
		 * Fonction displayRapport_PeuplementBDDCible
		 * Permet dd générer et d'afficher le rapport de peuplement de la BDD Cible
		 */
		public function displayRapport_PeuplementBDDCible(){
			$this->template->set_filenames(array(
		    	'tpl_rapport'		=> 'rapport_peuplement.tpl',
				'tpl_menu'			=> 'menu.tpl'
	       	));
		
			$this->displayMenu();

			$data_peuplement = $this->engine->getRapport_Peuplement();
			
			$total_beb_non = 0;
			$total_beb_oui = 0;
			$total_general = 0;
			
			
			
			for($i = 0;$i < count($data_peuplement); $i++) {
				$this->template->assign_block_vars('content', array(	
					'RESPONSABLE'				=> $data_peuplement[$i]['Prenom'],
					'G2R'						=> $data_peuplement[$i]['G2R'],
					'BEB_NON' 					=> $data_peuplement[$i]['G2R'] - $data_peuplement[$i]['G2R_TRANS'] ,
					'BEB_OUI' 					=> $data_peuplement[$i]['G2R_TRANS']
				));
				
				/* code inital */
				// $this->template->assign_block_vars('content_pourcent', array(	
					// 'RESPONSABLE'				=> $data_peuplement[$i]['Prenom'],
					// 'G2R_POURCENT'				=> "100,00%",
					// 'IMAGE'						=> "none",
					// 'BEB_NON_POURCENT' 			=> $this->Pourcentage($data_peuplement[$i]['G2R'] - $data_peuplement[$i]['G2R_TRANS'],$data_peuplement[$i]['G2R']) ,
					// 'BEB_OUI_POURCENT' 			=> $this->Pourcentage($data_peuplement[$i]['G2R_TRANS'],$data_peuplement[$i]['G2R']),
				// ));
				
				//code temporaire //
				if($this->Pourcentage($data_peuplement[$i]['G2R_TRANS'],$data_peuplement[$i]['G2R']) == 100)
					$this->template->assign_block_vars('content_pourcent', array(	
						'RESPONSABLE'				=> $data_peuplement[$i]['Prenom'],
						'G2R_POURCENT'				=> "100,00%",
						'IMAGE'						=> "inline",
						'BEB_NON_POURCENT' 			=> $this->Pourcentage($data_peuplement[$i]['G2R'] - $data_peuplement[$i]['G2R_TRANS'],$data_peuplement[$i]['G2R']),
						'BEB_OUI_POURCENT' 			=> $this->Pourcentage($data_peuplement[$i]['G2R_TRANS'],$data_peuplement[$i]['G2R'])
					));
				else
					$this->template->assign_block_vars('content_pourcent', array(	
						'RESPONSABLE'				=> $data_peuplement[$i]['Prenom'],
						'G2R_POURCENT'				=> "100,00%",
						'IMAGE'						=> "none",
						'BEB_NON_POURCENT' 			=> $this->Pourcentage($data_peuplement[$i]['G2R'] - $data_peuplement[$i]['G2R_TRANS'],$data_peuplement[$i]['G2R']),
						'BEB_OUI_POURCENT' 			=> $this->Pourcentage($data_peuplement[$i]['G2R_TRANS'],$data_peuplement[$i]['G2R'])
					));
				
				
				/* fin code temporaire */
				
				$total_beb_non = $total_beb_non + $data_peuplement[$i]['G2R'] - $data_peuplement[$i]['G2R_TRANS'];
				$total_beb_oui = $total_beb_oui + $data_peuplement[$i]['G2R_TRANS'];
				$total_general = $total_beb_non + $total_beb_oui;
			}
			
			$this->template->assign_block_vars('sum_g2r_pourcent', array(	
				'BEB_NON_POURCENT'				=> $this->Pourcentage($total_beb_non, $total_general),
				'BEB_OUI_POURCENT'				=> $this->Pourcentage($total_beb_oui, $total_general),
				'TOTAL_POURCENT' 				=> "100,00%"
			));
			
			$this->template->assign_block_vars('sum_g2r', array(	
				'BEB_NON'				=> $total_beb_non,
				'BEB_OUI'				=> $total_beb_oui,
				'TOTAL' 				=> $total_general
			));			
	
	       	$this->template->pparse('tpl_rapport');
		}
		
		/**
		 * Fonction displayRapport_FH
		 * Permet de générer et d'afficher le rapport de FH à lancer
		 */
		public function displayRapport_FH(){
			$this->template->set_filenames(array(
		    	'tpl_rapport'		=> 'rapport_fh.tpl',
				'tpl_menu'			=> 'menu.tpl'
	       	));

			$this->displayMenu();

			$data_fh = $this->generateRapportFH();

			foreach ($data_fh as $result) {
				$this->template->assign_block_vars('content', array(
					'PLAQUE'				=> $result['Plaque'],
					'DPT'					=> $result['DPT'],
					'G2R'					=> $result['G2R'],
					'NOM'					=> $result['nom'],
					'CLE_FH'				=> $result['CLE_FH'],
					'G2R_B'					=> $result['G2R_pere'],
					'G2R_A'					=> $result['G2R_fils'],
					'NB_SITES'				=> $result['NB_SITES'],
					'EQUIPEMENT'			=> $result['EQUIPEMENT'],
					'ETAT'					=> $result['ETAT']
				));
			}

	       	$this->template->pparse('tpl_rapport');
		}
		
		/**
		 * Fonction generateTmpTableRapport_FH
		 * Permet de générer et de remplir une table temporaire contenant le Rapport FH
		 */
		public function generateTmpTableRapport_FH(){
			$data_fh = $this->generateRapportFH();

			$table = "tmp_rapport_fh";
			foreach ($data_fh as $result) {						
				$this->engine->createTableRapportFH($table,$result['G2R'],$result['NB_SITES'],$result['ETAT']);
			}
			
			return $table;
		}
		
		/**
		 * Fonction displayRapport_IP
		 * Permet de générer et d'afficher le rapport du Potentiel IP de chaque site
		 */
		public function displayRapport_IP(){
			$this->template->set_filenames(array(
		    	'tpl_rapport'		=> 'rapport_ip.tpl',
				'tpl_menu'			=> 'menu.tpl'
	       	));

			$this->displayMenu();
			
			$table = $this->generateTmpTableRapport_FH();
			
			$data_ip = $this->generateRapportIP();	
			
			foreach ($data_ip as $result) {
				$this->template->assign_block_vars('content', array(
					'G2R'					=> $result['G2R'],
					'SUPP_EXIST'			=> $result['SUPPORT_EXISTANT'],
					'PARD_EXIST'			=> $result['PARD_EXISTANT'],
					'SUPP_CIBLE'			=> $result['SUPPORT_CIBLE'],
					'PARD_CIBLE'			=> $result['PARD_CIBLE'],
					'ETAT'					=> $result['ETAT']
				));
			}

	       	$this->template->pparse('tpl_rapport');
			
			$this->engine->deleteTmpTableRapport_FH($table);
		}	
		
		/**
		 * Fonction generateRapportFH
		 * Permet de générer le rapport FH
		 */
		public function generateRapportFH(){
			// Traitement pour récupérer les valeurs au format de l'Export en Colonne de la BDD Cible			
			$table1 = "tmp_rapport_exportCol";
			$table2 = "tmp_rapport_equipement";
			
			$this->engine->createTempRapportFH($table1,$table2);
			
			$filtre = true;
			$dataSites = $this->engine->getExportCol($filtre);
			$dataBeB = $this->engine->getBeB();
			
			foreach ($dataSites as $site) {
				$pere = $site['G2R'];
				$tabBeB = Array();
				
				do {
					$tabBeB[] = $pere;
					$pere = $dataBeB[$pere];				
				} while (!is_null($pere));
			
				$pere = $site['G2R'];
							
				$i = 1;

				do {
					$fils = $tabBeB[$i];
					$i++;
					
					if ($fils == null)
							$fils = $pere;
					
					$p1 = strval($pere) ;
					$f1 = strval($fils) ;
					$cle_fh = min($p1,$f1).max($p1,$f1);
					
					$this->engine->insertData_TempExportCibleCol($table1, $site['Plaque'], $site['DPT'], $site['G2R'], $site['nom'], $pere, $fils, $cle_fh);	
						
					$pere = $fils;	
					var_dump($tabBeB[$i]);
					
				}while ($tabBeB[$i] != null);	
				//while ($tabBeB[$i] != null);
				
			 }
			// Fin du Traitement sur le format en Colonne 
			 
			$this->engine->insertData_TempEquipement($table2);
			 
			$data_fh = $this->engine->getRapport_FH();
			
			$this->engine->deleteTempRapportFH($table1,$table2);
			
			return $data_fh;
		}
		
		/**
		 * Fonction generateRapportIP
		 * Permet de générer le rapport Potentiel IP
		 */
		public function generateRapportIP(){
			// table temporaire pour récupérer le PARD Cible de chaque G2R
			$table = "tmp_rapportIP_pard";
			
			$this->engine->createTempRapportIP($table);
			
			$result = $this->engine->getInfoG2R_All();	
		
			foreach ($result as $data) {
				$pere = $data['G2R'];
				
				$tabBeB = Array();

				do {
					$dataFils = $this->engine->getFils($pere);					
					$tabBeB[] = $dataFils;
					$pere = $dataFils['G2R_fils'];
				} while ($pere != null);

				// Cas coloc
				if(count($tabBeB) == 1)
					$tabBeB[] = $tabBeB[0];			

				// insertion des données dans la table temporaire
				$this->engine->insertData_TempPARD($table,$data['G2R'],$tabBeB[count($tabBeB)-1]['G2R_pere'],$data['Support']);
			}
			
			$data_ip = $this->engine->getRapport_IP();		
			
			$this->engine->deleteTempRapportIP($table);
			
			return $data_ip;
		}
		
		/**
		 * Fonction displayMenu
		 * Permet d'afficher le menu
		 */
		public function displayMenu(){
			if (isset($_SESSION['Login'])){
				$this->template->assign_block_vars('login', array(
					'Login' 			=> $_SESSION['Login']
				));		
				
				if(isset($_SESSION['Permission']) && $_SESSION['Permission'] > 0){
					$this->template->assign_block_vars('permission', array());
				}
			}
			else
				$this->template->assign_block_vars('no_login', array());						

			$this->template->assign_var_from_handle('BOX_MENU', 'tpl_menu');
		}		
		
		/**
		 * Fonction delete_com_G2R
		 * Permet de supprimer un commentaire
		 * @param error : variable permettant d'indiquer si des "incohérences" ont été détectées
		 * @param warning : variable permettant d'alerter d'une modification l'utilisateur
		 * @param msg : message à afficher en sortie
		 * @param data : données à analyser et insérer dans la BDD
		 */
		public function delete_com_G2R(){
			//$data = $_POST;

			$error = false;
			$msg = null;
			
			$g2r = $_POST['G2R'];
			$date_time = $_POST['Date'];
			$writer = $_POST['User'];
			
			$use = $this->engine->getUser($_SESSION['Login']);
			$data_use = $use['data'];
			$nom_prenom = $data_use[0]["PRENOM"]." ".$data_use[0]["NOM"];
			
			// Si l'utilisateur a les droits (pour suppression)
			$res = $this->engine->verifPerm($_POST['G2R'], $_SESSION['Login']);
			
			if($res['nb_plaque'] == 0){
				$error = true;
				$msg = "Vous n'avez pas les droits sur cette plaque!";
				
				$this->template->set_filenames(array(
					'tpl_display_msg' 	=> 'display_msg.tpl'
				));
				
				$this->template->assign_block_vars('error_com_g2r', array(
					'MSG' => 'Erreur lors du traitement : '.'\n\n'.$msg
				));
				
				$this->template->pparse('tpl_display_msg');								
			}
			else if ($writer <> $nom_prenom) {
				$error = true;
				$msg = "Vous ne pouvez pas supprimer un commentaire que vous n'avez pas rédigé!";
				
				$this->template->set_filenames(array(
					'tpl_display_msg' 	=> 'display_msg.tpl'
				));
				
				$this->template->assign_block_vars('error_com_g2r', array(
					'MSG' => 'Erreur lors du traitement : '.'\n\n'.$msg
				));
				
				$this->template->pparse('tpl_display_msg');
			}				
			else {
				$this->engine->deleteComment_G2R($g2r, $date_time);
				//header('Location: index.php?p=1&G2R='.$g2r);
			}
		}
		
		/**
		 * Fonction delete_com_liens
		 * Permet de supprimer un commentaire pour les liens
		 * @param error : variable permettant d'indiquer si des "incohérences" ont été détectées
		 * @param warning : variable permettant d'alerter d'une modification l'utilisateur
		 * @param msg : message à afficher en sortie
		 * @param data : données à analyser et insérer dans la BDD
		 */
		public function delete_com_liens(){
			//$data = $_POST;

			$error = false;
			$msg = null;
			
			$g2r_a = $_POST['G2R_A']; 
			$g2r_b = $_POST['G2R_B'];
			$date_time = $_POST['Date'];
			$writer = $_POST['User'];
			
			$use = $this->engine->getUser($_SESSION['Login']);
			$data_use = $use['data'];
			$nom_prenom = $data_use[0]["PRENOM"]." ".$data_use[0]["NOM"];
			
			// Si l'utilisateur a les droits (pour suppression)
			$res = $this->engine->verifPerm($_POST['G2R_A'], $_SESSION['Login']);
			$res2 = $this->engine->verifPerm($_POST['G2R_B'], $_SESSION['Login']);
			
			if(($res['nb_plaque'] == 0)){
				$error = true;
				$msg = "Vous n'avez pas les droits sur cette plaque!";
				
				$this->template->set_filenames(array(
					'tpl_display_msg' 	=> 'display_msg.tpl'
				));
				
				$this->template->assign_block_vars('error_com_liens', array(
					'MSG' => 'Erreur lors du traitement : '.'\n\n'.$msg
				));
				
				$this->template->pparse('tpl_display_msg');								
			}
			else if ($writer <> $nom_prenom) {
				$error = true;
				$msg = "Vous ne pouvez pas supprimer un commentaire que vous n'avez pas rédigé!";
				
				$this->template->set_filenames(array(
					'tpl_display_msg' 	=> 'display_msg.tpl'
				));
				
				$this->template->assign_block_vars('error_com_liens', array(
					'MSG' => 'Erreur lors du traitement : '.'\n\n'.$msg
				));
				
				$this->template->pparse('tpl_display_msg');
			}				
			else {
				$this->engine->deleteComment_Liens($g2r_a,$g2r_b,$date_time);
				//header('Location: index.php?p=8');
			}
		}
		
		/**
		 * Fonction submitFormCom_G2R
		 * Permet d'insérer un commentaire pour un G2R
		 * @param error : variable permettant d'indiquer si des "incohérences" ont été détectées
		 * @param warning : variable permettant d'alerter d'une modification l'utilisateur
		 * @param msg : message à afficher en sortie
		 * @param data : données à analyser et insérer dans la BDD
		 */
		public function submitFormCom_G2R(){
			if(isset($_POST) && !empty($_POST))
				$data = $_POST;
				
			$check = false;
			$error = false;
			$warning = false;
			$msg = null;
			
			$this->template->assign_block_vars('redirect', array(
				'G2R' => $_POST['G2R_2']
			));
			
			// Si l'utilisateur a les droits (pour suppression)
			$res = $this->engine->verifPerm($_POST['G2R_2'], $_SESSION['Login']);
		
			if($res['nb_plaque'] == 0){
				$error = true;
				$msg = "Vous n'avez pas les droits sur cette plaque!";
				
				$this->template->set_filenames(array(
					'tpl_display_msg' 	=> 'display_msg.tpl'
				));
				
				$this->template->assign_block_vars('error_com_g2r', array(
					'MSG' => 'Erreur lors du traitement : '.'\n\n'.$msg
				));
				
				$this->template->pparse('tpl_display_msg');
			}
		
			//Controle si tous les champs sont remplis		
			if(!isset($data['utilisateur']) && empty($data['utilisateur'])){
				$error = true;
				$msg = "L'utilisateur n'est pas renseigné";
			}
			
			if(!$error && !isset($data['categorie']) && empty($data['categorie'])){
				$error = true;
				$msg = "La catégorie n'est pas renseignée";
			}
			
			if((!$error && !isset($data['coms']))  || (!$error && empty($data['coms']))) {
				$error = true;
				$msg = "Le champs Commentaires est vide";
			}
			
			if($res['nb_plaque'] <> 0 && $error){
				// Stockage des données en session
				$_SESSION['data'] = $data;
			
				// Afficher le message d'erreur
				$this->template->set_filenames(array(
					'tpl_display_msg' 	=> 'display_msg.tpl'
				));
				
				$this->template->assign_block_vars('error_com_g2r', array(
					'MSG' => 'Les données saisies sont erronées : '.'\n\n'.$msg
				));
				
				$this->template->pparse('tpl_display_msg');
			}					
			else if($res['nb_plaque'] <> 0 && $error == false) {	
				$g2r = $data['G2R_2']; 
				$user = $data['utilisateur'];
				$category = $data['categorie'];
				$date_time = $data['date_com'];
				$com = $data['coms'];
				
				$this->engine->insertCom_G2R($g2r, $user, $category, $date_time, $com);
				
				header('Location: index.php?p=1&G2R='.$g2r);	
			}						
		}
		
		/**
		 * Fonction submitFormCom_Liens
		 * Permet d'insérer un commentaire pour un lien
		 * @param error : variable permettant d'indiquer si des "incohérences" ont été détectées
		 * @param warning : variable permettant d'alerter d'une modification l'utilisateur
		 * @param msg : message à afficher en sortie
		 * @param data : données à analyser et insérer dans la BDD
		 */
		public function submitFormCom_Liens(){
			if(isset($_POST) && !empty($_POST))
				$data = $_POST;
				
			$check = false;
			$error = false;
			$warning = false;
			$msg = null;
			
			$this->template->assign_block_vars('redirect', array(
				'G2R_A' => $_POST['G2R_2_A'],
				'G2R_B' => $_POST['G2R_2_B']
			));
			
			// Si l'utilisateur a les droits (pour suppression)
			$res = $this->engine->verifPerm($_POST['G2R_2_A'], $_SESSION['Login']);
			$res2 = $this->engine->verifPerm($_POST['G2R_2_B'], $_SESSION['Login']);
		
			if(($res['nb_plaque'] == 0) || ($res2['nb_plaque'] == 0)){
				$error = true;
				$msg = "Vous n'avez pas les droits sur cette plaque!";
				
				$this->template->set_filenames(array(
					'tpl_display_msg' 	=> 'display_msg.tpl'
				));
				
				$this->template->assign_block_vars('error_com_liens', array(
					'MSG' => 'Erreur lors du traitement : '.'\n\n'.$msg
				));
				
				$this->template->pparse('tpl_display_msg');
			}
		
			//Controle si tous les champs sont remplis		
			if(!isset($data['utilisateur']) && empty($data['utilisateur'])){
				$error = true;
				$msg = "L'utilisateur n'est pas renseigné";
			}
			
			if(!$error && !isset($data['categorie']) && empty($data['categorie'])){
				$error = true;
				$msg = "La catégorie n'est pas renseignée";
			}
			
			if((!$error && !isset($data['coms']))  || (!$error && empty($data['coms']))) {
				$error = true;
				$msg = "Le champs Commentaires est vide";
			}
			
			if($res['nb_plaque'] <> 0 && $res2['nb_plaque'] <> 0 && $error){
				// Stockage des données en session
				$_SESSION['data'] = $data;
			
				// Afficher le message d'erreur
				$this->template->set_filenames(array(
					'tpl_display_msg' 	=> 'display_msg.tpl'
				));
				
				$this->template->assign_block_vars('error_com_liens', array(
					'MSG' => 'Les données saisies sont erronées : '.'\n\n'.$msg
				));
				
				$this->template->pparse('tpl_display_msg');
			}					
			else if($res['nb_plaque'] <> 0 && $res2['nb_plaque'] <> 0 && $error == false) {	
				$g2r_a = $data['G2R_2_A']; 
				$g2r_b = $data['G2R_2_B']; 
				$user = $data['utilisateur'];
				$category = $data['categorie'];
				$date_time = $data['date_com'];
				$com = $data['coms'];
				
				$this->engine->insertCom_Liens($g2r_a, $g2r_b, $user, $category, $date_time, $com);
				
				header('Location: index.php?p=8&G2R_A='.$g2r_a.'&G2R_B='.$g2r_b);	
			}						
		}
		
		/**
		 * Fonction submitFormG2R
		 * Permet d'effectuer les divers contrôles de cohérences / insertion / mise à jour des données suite à la validation des données
		 * @param error : variable permettant d'indiquer si des "incohérences" ont été détectées
		 * @param warning : variable permettant d'alerter d'une modification l'utilisateur
		 * @param msg : message à afficher en sortie
		 * @param data : données à analyser et insérer dans la BDD
		 */
		public function submitFormG2R($error, $warning, $msg, $data){
			// $data contient les données envoyées (dans session['data'])

			// Eclatement du BeB et on le retourne
			preg_match_all(REGEX_G2R, $data['BEB'], $tabBeB);
			$tabBeB = array_reverse($tabBeB[0]);
			
			$nbSites = count($tabBeB);
			$nbSiteUnique = count(array_unique($tabBeB));
			
			$beb_str = implode(", ", $tabBeB);	
			
			// Support où le PARD est un CPE
			$ajoutCPE = false;
			$supportCPE = $this->engine->getSupport(1);
			
			if(isset($data['Support']) && !empty($data['Support'])) {
				$res0 = in_array($data['Support'], $supportCPE['0']);
				$res1 = in_array($data['Support'], $supportCPE['1']);
				$res2 = in_array($data['Support'], $supportCPE['2']);
				$res3 = in_array($data['Support'], $supportCPE['3']);
				
				if(isset($data['G2R_PARD']) && !empty($data['G2R_PARD'])) {
					if(($res0 || $res1 || $res2 || $res3) == true && $data['G2R_PARD'] == 'CPE' && $tabBeB[0] == $data['G2R']){
						$ajoutCPE = true;
					}  
				}
			}	
			
			// Contrôle de cohérences
			// Si G2R est non renseigné
			if($data['G2R'] == null) {
				$error = true;
				$msg = "Veuillez renseigner le G2R!";				
			}
			
			// Si G2R ne contient pas de chiffres
			if(isset($data['G2R']) && !empty($data['G2R']) && !is_numeric($data['G2R'])) {
				$error = true;
				$msg = "Le G2R ne doit contenir que des chiffres!";
			}
			
			if(!$error && isset($data['G2R']) && !empty($data['G2R'])) {
				// Si G2R renseigné n'est pas dans la table SITE
				$res = $this->engine->verifSites($data['G2R']);
				
				if(!$error && ($res['nb_G2R'] == 0)){
					$error = true;
					$msg = "Le G2R saisi n'est pas dans la table SITE";
				}
				
				// Si l'utilisateur a les droits (pour écriture)
				$res = $this->engine->verifPerm($data['G2R'], $_SESSION['Login']);
				
				if(!$error && ($res['nb_plaque'] == 0)){
					$error = true;
					$msg = "Vous n'avez pas les droits sur cette plaque!";
				}				
			}
		
			//Si le Beb n'est pas renseigné
			if(!$error && $tabBeB == null){
				$error = true;
				$msg = "Le Beb n'est pas renseigné!";
			}
			
			//Si un PARD n'est pas sélectionné dans le menu déroulant
			if(!$error && !isset($data['G2R_PARD']) && empty($data['G2R_PARD'])) {
				$error = true;
				$msg = "Le PARD n'est pas renseigné!";
			}	
			
			//Si un type de Support n'est pas sélectionné dans le menu déroulant quand Ajout d'un nouveau CPE
			if(!$error && ($data['G2R_PARD'] == 'CPE') && !isset($data['Support']) && empty($data['Support'])) {
				$error = true;
				$msg = "Le Support n'est pas renseigné!";
			}	
			
			//Si le G2R renseigné est différent de celui donné dans le Beb
			if(!$error && end($tabBeB) != $data['G2R']) {
				$error = true;
				$msg = 'Le site radio est différent de celui spécifié dans le bout en bout !';
			}
			
			//Si le PARD choisi dans le menu déroulant est différent de celui donné dans le Beb
			if(!$error && (intval($tabBeB[0]) != $data['G2R_PARD']) && !$ajoutCPE) {
				$error = true;
				$msg = 'Le PARD est différent de celui spécifié dans le bout en bout !\n OU \n Le support pour le nouveau CPE est non conforme!';
			}
			
			//Si il y a plusieurs fois le même G2R de sites renseigné dans le Beb
			if(!$error && $nbSites > 2 && $nbSites != $nbSiteUnique) {
				$error = true;
				$msg = 'Le bout en bout spécifié contient plusieurs fois le même site !';
			}
			
			//Si il y a des G2R de sites non présents dans la table SITE lors du renseignement du Beb
			if(!$error) {
				$result = $this->engine->verifSites($beb_str);
				
				if($nbSiteUnique != $result['nb_G2R']) {
					$error = true;
					$msg = 'Le bout en bout spécifié contient des sites non réferencés dans la BDD !';
				}
			}
			
			//Si il y a déjà un PE spécifié pour le site lors de l'ajout d'un nouveau CPE
			if(!$error && $ajoutCPE == true) {
				$result = $this->engine->verifPARD($data['G2R']);	
				
				if($result['nb_PARD'] != 0) {
					$error = true;
					$msg = 'Il existe déjà un PE sur le site '.$data['G2R'] .' !';
				}
			}

			if(!$error) {
				// On récupère les clés qui ont comme père les G2R du BEB. Format : G2R_pere => G2R_fils
				$beb_bdd = $this->engine->getCles($beb_str);

				// On ajoute un G2R null comme première valeur (= fils à null)
				array_unshift($tabBeB, null);
				
				$beb_array = array();
				
				for ($i = 1; $i < count($tabBeB); $i++){
					// Test du cas coloc
					if($tabBeB[$i] != $tabBeB[$i-1]){
						// Formatage du BeB au format similaire : G2R_pere => G2R_fils
						if(isset($tabBeB[$i-1]) && !empty($tabBeB[$i-1]))
							$beb_array[($tabBeB[$i] * 1)] = $tabBeB[$i-1] * 1;
						else						
							$beb_array[($tabBeB[$i] * 1)] = $tabBeB[$i-1];
					}
				}

				// Compare les deux tableaux
				$beb_diff = array_diff($beb_array, $beb_bdd);
				$beb_insert = array_diff_key($beb_array, $beb_bdd);
				$beb_update = array_diff($beb_diff, $beb_insert);

				// Confirmation de l'utilisateur si le BEB renseigné est impactant pour les sites en aval
				if(count($beb_update) > 0 && !isset($_POST['confirm'])){
					$warning = true;
					
					foreach($beb_update as $G2R_pere => $G2R_fils_new){
						$G2R_fils_old = $beb_bdd[$G2R_pere];
						
						if(is_null($G2R_fils_old))
							$G2R_fils_old = $G2R_pere;
							
						if(is_null($G2R_fils_new))
							$G2R_fils_new = $G2R_pere;
							
						$msg .= '- '.$G2R_pere.' vers '.$G2R_fils_new.' (au lieu de '.$G2R_fils_old.')\n';
					}
				}

				// Si pas de warning ou bien si l'utilisateur a confirmé, on enregistre les données
				if(!$warning) {
					if($ajoutCPE) {
						$this->engine->insertPARD($data['G2R'], "CPE", "-", "-");
						$data['G2R_PARD'] = intval($data['G2R']);
					}

					$result = $this->engine->verifTrans(intval($data['G2R']));
					
					if($result['nb'] == 0) {
						$this->engine->insertTrans(intval($data['G2R']), $data['Support'], $data['Techno_trans']);					
					}
					else
						$this->engine->updateTrans(intval($data['G2R']), $data['Support'], $data['Techno_trans']);
					
					$this->engine->insertTransNotPresent($beb_str);
					
					foreach ($beb_insert as $G2R_pere => $G2R_fils)
						$this->engine->insertBeB($G2R_pere, $G2R_fils);
					
					foreach ($beb_update as $G2R_pere => $G2R_fils)
						$this->engine->updateBeB($G2R_pere, $G2R_fils);
				}
			}

			$tabResult = array();
			
			$tabResult[0] = $error;
			$tabResult[1] = $warning;
			$tabResult[2] = $msg;
			
			return $tabResult;
		}
		
		/**
		 * Fonction submitFormPARD
		 * Permet d'effectuer les divers contrôles de cohérences / insertion / mise à jour des données suite à la validation des données concernant les PARD
		 * @param error : variable permettant d'indiquer si des "incohérences" ont été détectées
		 * @param warning : variable permettant d'alerter d'une modification l'utilisateur
		 * @param msg : message à afficher en sortie
		 * @param data : données à analyser et insérer dans la BDD
		 */
		public function submitFormPARD($error, $warning, $msg, $data){
			// $data contient les données envoyées (soit POST, soit Session)
	
			// Controle si tous les champs sont remplis		
			if(!isset($data['Type_PARD']) || empty($data['Type_PARD'])){
				$error = true;
				$msg = "Le Type de PARD n'est pas renseigné";
			}					
			
			// si G2R non rempli avec des chiffres	
			if(!empty($data['G2R_PARD']) && isset($data['G2R_PARD']) && !is_numeric($data['G2R_PARD'])){
				$error = true;
				$msg = "Le G2R PARD ne doit contenir que des chiffres !";
			}
			
			// si l'état n'est pas renseigné pour le PARD	
			if(empty($data['Etat_PARD']) || !isset($data['Etat_PARD']) || $data['Etat_PARD'] == '6'){
				$error = true;
				$msg = "L'état du PARD doit être renseigné !";
			}
			
			// si l'état n'est pas renseigné pour le PE-FTTS	
			if(empty($data['Etat_PEFTTS']) || !isset($data['Etat_PEFTTS'])){
				$error = true;
				$msg = "L'état du PE-FTTS doit être renseigné !";
			}
			
			// Contrôle de cohérences : si G2R ne match pas avec table SITE,lancer erreur	
			if(!$error && isset($data['G2R_PARD'])){
				$result = $this->engine->verifSites($data['G2R_PARD']);	
				
				if($result['nb_G2R'] == 0 && isset($data['G2R_PARD'])){
					$error = true;
					$msg = "Le G2R n'est pas renseigné ou absent de la table SITE!";
				}
			}			
			
			// Si pas de warning ni d'erreur
			$res = $this->engine->verifPARD($data['G2R_PARD']);
			
			if(!$warning && !$error &&  ($res['nb_PARD'] == 0))
				$this->engine->insertPARD($data['G2R_PARD'],$data['Type_PARD'],$data['Etat_PARD'], $data['Etat_PEFTTS']);
			else if (!$warning && !$error && ($res['nb_PARD'] != 0))
				$this->engine->updatePARD($data['G2R_PARD'],$data['Type_PARD'],$data['Etat_PARD'], $data['Etat_PEFTTS']);

			$tabResult = array();
			
			$tabResult[0] = $error;
			$tabResult[1] = $warning;
			$tabResult[2] = $msg;
			
			return $tabResult;
		}
		
		/**
		 * Fonction displayFormAdd_AttributsSite()
		 * Permet d'effectuer les diverses modifications sur les caractéristiques d'un site
		 */
		public function displayFormAdd_AttributsSite(){
			
			$find = false;
			$sucess = false;
			
			$this->template->set_filenames(array(
		    	'tpl_edit'			=> 'edit_site.tpl',
				'tpl_menu'			=> 'menu.tpl',
				'tpl_search'		=> 'search.tpl'
	       	));
			
			//$this->displayMenu();
					
			if(isset($_GET['G2R']) && !empty($_GET['G2R'])){
				$this->template->assign_block_vars('Site', array(
						'G2R_Site' => $_GET['G2R']
					));
				$find = true;
				$_SESSION['G2R_temp'] = $_GET['G2R'];
			}
			else {
				$this->template->assign_block_vars('Site', array(
						'G2R_Site' => 'Site non trouvé'
					));
			}

			// Si l'utilisateur a les droits 
			$result = $this->engine->verifPerm($_SESSION['G2R_temp'], $_SESSION['Login']);
			//$result = $this->engine->verifPerm($_SESSION['G2R_temp'], "u098833");  $_SESSION['Login']
			
			if($result['nb_plaque'] == 0){
				$error = true;
				$msg = "Vous n'avez pas les droits sur cette plaque!"; 
				
				$this->template->set_filenames(array(
					'tpl_display_msg' 	=> 'display_msg.tpl'
				));
				
				$this->template->assign_block_vars('error', array(
					'MSG' => 'Erreur lors du traitement : '.'\n\n'.$msg
				));
				
				$this->template->pparse('tpl_display_msg');	
				
				$_GET['G2R'] = $_SESSION['G2R_temp']; 
				$this->displaySearch();	
				$unauthorized = true;
				$erreur = true;
			}			
			else {
				$unauthorized = false;
				$erreur = false;
			}

				
			if(isset($_POST) && !empty($_POST) && $unauthorized == false){				
				$check = false;
			
				if(isset($_POST) && !empty($_POST))
					$data = $_POST;
					
				// Le formulaire a été rempli (valider les données)
				if(isset($data))
					$check = true;
					
				// L'utilisateur modifie son formulaire (ne pas valider les données)
				if(isset($data['retry']) && $data['retry'] == true)
					$check = false;

				// L'utilisateur confirme l'écrasement des les données (re-valideer les données)
				if(isset($_POST['confirm']) && !empty($_POST['confirm']))
					$check = true;

				// Ne pas valider les données, donc afficher le formulaire de saisie
				if(!$check) {
					// Vider les données en session
					unset ($_POST); 
				}
				else {
					// Données remplies dans la fonction "submit"
					$error = false;
					$warning = false;
					$msg = null;

					$tabResult = $this->submitEditAttributsForm($error, $warning, $msg, $data);

					$error = $tabResult[0];
					$warning = $tabResult[1];
					$msg = $tabResult[2];
						
					//Si l'Injection/MAJ a marché => //pb niveau affichage login  du à display-message?
					if(!$error && !$warning) {
						// Vider les données en session	
						unset ($_POST); 
						
						// méthode de redirection initiale 
						// $_GET['G2R'] = $_SESSION['G2R_temp'];
						// $this->displaySearch();	 
						
						$this->template->set_filenames(array(
							'tpl_display_msg' 	=> 'display_msg.tpl'
						));		
						$this->template->assign_block_vars('error_com_g2r', array(
								'MSG' => 'Les données ont bien été modifiées pour le G2R '.$_SESSION['G2R_temp']
						));
						$this->template->pparse('tpl_display_msg');
						$sucess = true;
						//$_SESSION['G2R_temp'] = null;
						
						header('Location: index.php?p=1&G2R='.$_SESSION['G2R_temp']);
						$_SESSION['G2R_temp'] = null;						
					} 
					else {
						// On propose à l'utilisateur de modifier son formulaire
						$data['retry'] = true;

						// Stockage des données en session
						$_SESSION['data'] = $data;

						// Afficher le template de confirmation / erreur
						$this->template->set_filenames(array(
							'tpl_display_msg' 	=> 'display_msg.tpl'
						));
						
						if($error){
							$this->template->assign_block_vars('error', array(
								'MSG' => 'Les données saisies sont erronées'
							));
						}
						
						$this->template->pparse('tpl_display_msg');	
						}			
				}	
		} //if POST
		if(!$sucess && !$erreur){
			$this->displayMenu();
			$this->template->pparse('tpl_edit');
			}			
		}

		/**
		 * Fonction submitEditAttributsForm
		 * Permet d'insérer / mettre à jour les données de caractéristiques d'un site
		 */
		public function	submitEditAttributsForm($error, $warning, $msg, $data) {
			
			$g2r = $data['G2R_Site'];
			
			// Si pas de warning ni d'erreur
			$res = $this->engine->verifG2R_attributs($g2r); 
			
			if(!$warning && !$error && ($res['nb'] == 0)) {
				$this->engine->insert_attributs_site($g2r,$data['Nacelle']);
			}
			else if(!$warning && !$error && ($res['nb'] > 0)) {
				$this->engine->update_attributs_site($g2r,$data['Nacelle']);
			}

			$tabResult = array();
			
			$tabResult[0] = $error;
			$tabResult[1] = $warning;
			$tabResult[2] = $msg;
			
			return $tabResult;
		}
		
		/**
		 * Fonction exportAuto
		 * Permet d'exporter en planifié les infos concernant les G2R-Beb et les PARD
		 */
		public function	exportAuto() {
			$db = "bdd_cible_trans";
			$table1 = "G2R-BEB";
			$table2 = "PARD";
			$date = date("Ymd-H\hi");
			
			$filename1 = EXPORT_REP."Export_".$db."_".$table1."_".$date.".csv";
			$filename2 = EXPORT_REP."Export_".$db."_".$table2."_".$date.".csv";	
			$export = $this->engine->exportAutomatic($filename1, $filename2);
			
			exit;
		}
	}
?>