<?php

	require_once(MDL_DIR."Engine.class.php");

	class Search {
		
		private $list_patho;
		private $list_merid;
		private $list_sympt;

		/**
		 * Constructeur
		 */
		public function __construct(){
			$this->definePathos();
			$this->defineMeridiens();
			$this->defineSymptoms();
		}

		/**
		 * Fonction getPathos()
		 * Getter de l'attribut list_patho
		 */
		public function getPathos(){
			return $this->list_patho;
		}

		/**
		 * Fonction getMeridiens()
		 * Getter de l'attribut list_merid
		 */
		public function getMeridiens(){
			return $this->list_merid;
		}

		/**
		 * Fonction getsSymptoms()
		 * Getter de l'attribut list_sympt
		 */
		public function getSymptoms(){
			return $this->list_sympt;
		}

		private function definePathos(){
			$this->list_patho = array();

			$resultat_patho = Engine::requestPathos();
			$data_patho = $resultat_patho['data'];
			$nb_patho = $resultat_patho['nb'];

			// Assignation Pathos
			if($nb_patho > 0) {								
				for($i = 0; $i < $nb_patho; $i++){	
					$this->list_patho[$i]['PATHO_DESC'] = $data_patho[$i]['desc'];
				}
			}
		}

		private function defineMeridiens(){
			$this->list_merid = array();

			$resultat_merid = Engine::requestMeridiens();
			$data_merid = $resultat_merid['data'];
			$nb_merid = $resultat_merid['nb'];

			// Assignation Méridiens
			if($nb_merid > 0) {								
				for($i = 0; $i < $nb_merid; $i++){	
					$this->list_merid[$i]['MERID_DESC'] = $data_merid[$i]['nom'];
				}
			}
		}

		private function defineSymptoms(){
			$this->list_sympt = array();

			$resultat_sympt = Engine::requestSymptoms();
			$data_sympt = $resultat_sympt['data'];
			$nb_sympt = $resultat_sympt['nb'];

			// Assignation Symptomes
			if($nb_sympt > 0) {								
				for($i = 0; $i < $nb_sympt; $i++){	
					$this->list_sympt[$i]['SYMPT_DESC'] = $data_sympt[$i]['desc'];
				}
			}
		}

		/**
		 * Fonction displaySearch
		 * Permet d'afficher la page principale de recherches (sans traitements)
		 */
		public static function displaySearch(Engine $engine, Smarty $smarty){

			$resultat_patho = $engine->getPathos();
			$data_patho = $resultat_patho['data'];
			$nb_patho = $resultat_patho['nb'];

			$resultat_merid = $engine->getMeridiens();
			$data_merid = $resultat_merid['data'];
			$nb_merid = $resultat_merid['nb'];

			$resultat_sympt = $engine->getSymptoms();
			$data_sympt = $resultat_sympt['data'];
			$nb_sympt = $resultat_sympt['nb'];

			$list_patho = array();
			$list_merid = array();
			$list_sympt = array();

			if($nb_patho > 0) {								
				for($i = 0; $i < $nb_patho; $i++){	
					$list_patho[$i]['PATHO_DESC'] = $data_patho[$i]['desc'];
				}
			}
			// else {
			// 	$smarty->assign('pathology',array());
			// }
			$smarty->assign('pathology',$list_patho);

			// Assignation Méridiens
			if($nb_merid > 0) {								
				for($i = 0; $i < $nb_merid; $i++){	
					$list_merid[$i]['MERID_DESC'] = $data_merid[$i]['nom'];
				}
			}
			$smarty->assign('meridiens',$list_merid);

			// Assignation Symptomes
			if($nb_sympt > 0) {								
				for($i = 0; $i < $nb_sympt; $i++){	
					$list_sympt[$i]['SYMPT_DESC'] = $data_sympt[$i]['desc'];
				}
			}
			$smarty->assign('symptoms',$list_sympt);

			$smarty->display(TPL_DIR."content_recherche.tpl");

		}

		/**
		 * Fonction submitForm_KeywordSearch
		 * Permet de calculer et d'afficher les résultats d'une requete de recherche par mot clés
		 */
		public static function submitForm_KeywordSearch(Engine $engine, Smarty $smarty){
			if (isset($_POST['keywords']) && !empty($_POST['keywords']))
				$data_send = true;
			else{
				$data_send = false;
				header('Location: index.php?p=4');
			}
			
			if($data_send == true){
				$key = $_POST['keywords'];

				// récupération des résultats de pathologie selon le mots clé
				$result_path_ky = $engine->getPathos_Keywords($key);
				$data_patho_ky = $result_path_ky['data'];
				$nb_patho_ky = $result_path_ky['nb'];

				$list_patho_ky = array();

				if($nb_patho_ky > 0) {								
					for($i = 0; $i < $nb_patho_ky; $i++){	
						$list_patho_ky[$i]['PATHOS'] = $data_patho_ky[$i]['Patho'];
						$list_patho_ky[$i]['SYMPT'] = $data_patho_ky[$i]['Symp'];
					}
				}
				if($list_patho_ky != null){
					$smarty->assign('patho_ky',$list_patho_ky);
				}
				else{
					$msg[0]['PATHOS'] = 'Pas de résultats trouvés pour cette recherche';
					$msg[0]['SYMPT'] = 'Pas de résultats trouvés pour cette recherche';
					$smarty->assign('patho_ky',$msg);		
				}

			}		

			Search::displaySearch($engine, $smarty);	

			$smarty->display(TPL_DIR."content_recherche.tpl");		
		}	

		/**
		 * Fonction submitForm_MainRecherches
		 * Permet de calculer et d'afficher les résultats d'une requete de recherche par critère
		 */
		public static function submitForm_MainSearch(Engine $engine, Smarty $smarty){
				if (isset($_POST['type_patho']) && !empty($_POST['type_patho']) && isset($_POST['type_meridien']) && !empty($_POST['type_meridien']) && isset($_POST['caracteristiques_meridien']) && !empty($_POST['caracteristiques_meridien']))
					$data_send = true;
				else {
					$data_send = false;
					header('Location: index.php?p=4');
				}

				if($data_send == true){
					$categorie_patho = $_POST['type_patho'];
					$caracter = $_POST['caracteristiques_meridien'];
					$cpt = 0;
					$list_patho = array();
					$list_sy = array();
					if($caracter == "default"){
						$data_type_mer = $engine->getType_Merid_Default($categorie_patho);
						$nb_data_type_mer = $data_type_mer['nb'];
						if($nb_data_type_mer > 0){
							for($cpt = 0;$cpt < $nb_data_type_mer;$cpt++){
								$type_mer[$cpt] = $data_type_mer['data'][$cpt]['type_mer'];
							}
						}
					}
					else {
						$data_type_mer = $engine->getType_Merid($categorie_patho,$caracter);
						$type_mer[0] = $data_type_mer['data'][0]['type_mer'];
						$cpt = 1;
					}

					// pour selection multiple sur type Meridien
					$j = 0;
					foreach($_POST['type_meridien'] as $val){   
						$data_meridien = $engine->getCodeMeridien($val);
						$meridien[$j] = $data_meridien['data'][0]['code'] ;
						$j++;
					}

					// pour selection multiple Meridien
					$id = 0;
					$mark = 0;
					for($i = 0; $i < $cpt; $i++){
						for($l = 0;$l < $j;$l++){
							$result_path = $engine->getList_Patho($meridien[$l],$type_mer[$i]);				
							$data_path[$mark] = $result_path['data'];
							$nb_path[$mark]= $result_path['nb'];

							// traitement pour récupérer les symptomes associés
							$result_sy = $engine->getList_SymptomsByPatho($meridien[$l],$type_mer[$i]);
							$data_sy[$mark] = $result_sy['data'];
							$nb_sy[$mark] = $result_sy['nb'];
							$mark++;
						}
					$id = $i+1;
					}

					$tmp = 0;
					$nb_results = sizeof($nb_path);
					for($kl = 0;$kl < $nb_results;$kl++) {
						if($nb_path[$kl] > 0){
							for($p = 0; $p < $nb_path[$kl]; $p++){				
								$list_patho[$tmp]['RESULT_PATHO'] = $data_path[$kl][$p]['desc'];
								$mpt = 0;
								// traitement pour boucler sur la liste de TOUS les Symptomes associés à UNE seule Pathologie
								while($mpt < $nb_sy[$kl] ){
									$list_sy[$tmp][$mpt]['RESULT_SY'] = $data_sy[$kl][$mpt]['desc'];

									$mpt++;
								}
								
								$tmp++;		
							}
						}
					}	


					if($list_patho != null){
						$smarty->assign('patho_res',$list_patho);
					}
					else{
						$msg[0]['RESULT_PATHO'] = "Pas de résultats trouvés pour cette recherche";
						$smarty->assign('patho_res',$msg);
					}

					if($list_sy != null){
						$smarty->assign('sy_res',$list_sy);	
						$smarty->assign('nb_sy',$nb_sy);	
					}
					else{
						$msg[0][0]['RESULT_SY'] = "Pas de résultats trouvés pour cette recherche";
						$smarty->assign('sy_res',$msg);
					}

				}

			Search::displaySearch($engine, $smarty);	

			$smarty->display(TPL_DIR."content_recherche.tpl");	
		}

	}
?>