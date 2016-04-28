<?php

	require_once(MDL_DIR."Engine.class.php");

	class Search {
		
		private $list_patho;
		private $list_merid;
		private $list_sympt;
		private $list_patho_ky;
		private $list_patho_main;
		private $list_sympt_main;


		/**
		 * Constructeur
		 */
		public function __construct(){
			$this->list_patho = Engine::requestList(Engine::PATHOLOGIES);
			$this->list_merid = Engine::requestList(Engine::MERIDIENS);
			$this->list_sympt = Engine::requestList(Engine::SYMPTOMS);
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

		/**
		 * Fonction PathosForKeywords()
		 * Getter de l'attribut list_patho_ky
		 */
		public function getPathosForKeywords(){
			return $this->list_patho_ky;
		}

		/**
		 * Fonction getPathosForMain()
		 * Getter de l'attribut list_patho_main
		 */
		public function getPathosForMain(){
			return $this->list_patho_main;
		}

		/**
		 * Fonction getSymptsForMain()
		 * Getter de l'attribut list_sympt_main
		 */
		public function getSymptsForMain(){
			return $this->list_sympt_main;
		}

		/**
		 * Fonction submitForm_KeywordSearch
		 * Permet d'afficher les résultats d'une requete de recherche par mot clés
		 */
		public function submitForm_KeywordSearch(){
						
			if(isset($_POST['keywords']) && CheckValues::checkIsAlphaNumWithSpace($_POST['keywords'])){
				$key = $_POST['keywords'];

				$this->list_patho_ky = Engine::requestPathos_Keywords($key);

				if($this->list_patho_ky != null){
					return $this->list_patho_ky;
				}
				else{
					$this->list_patho_ky[0]['PATHOS'] = 'Pas de résultats trouvés pour cette recherche';
					$this->list_patho_ky[0]['SYMPT'] = 'Pas de résultats trouvés pour cette recherche';
					return $this->list_patho_ky;		
				}

			}else{
				header('Location: index.php?p=4');
				exit;
			}	
		}	

		/**
		 * Fonction submitForm_MainRecherches
		 * Permet de calculer et d'afficher les résultats d'une requete de recherche par critère
		 */
		public function submitForm_MainSearch(){
				
			if (isset($_POST['type_patho']) && CheckValues::checkName($_POST['type_patho']) &&
				isset($_POST['type_meridien']) && !empty($_POST['type_meridien']) &&
				isset($_POST['caracteristiques_meridien']) && CheckValues::checkName($_POST['caracteristiques_meridien'])
				){

				foreach ($_POST['type_meridien'] as $value) {
					
					if(CheckValues::checkName($value)){

						$categorie_patho = $_POST['type_patho'];
						$caracter = $_POST['caracteristiques_meridien'];
						$type = $_POST['type_meridien'];
						$data_send = true;

					} else {
						$data_send = false;
						header('Location: index.php?p=4');
					}

				}

			} else {
				$data_send = false;
				header('Location: index.php?p=4');
			}


			if($data_send == true){
				
				if($caracter == "default"){
					$type_mer = Engine::requestType_Merid_Default($categorie_patho);
				}
				else {
					$type_mer = Engine::requestType_Merid($categorie_patho,$caracter);
				}

				$meridien = Engine::requestCodeMeridien($type);

				// pour selection multiple Meridien
				$list_patho = Engine::requestList_Patho($meridien,$type_mer);
				// traitement pour récupérer les symptomes associés
				$list_sympt = Engine::requestList_SymptomsByPatho($meridien,$type_mer);


				$k = 0;
				$nb_results = sizeof($list_patho);
				for($i = 0;$i < $nb_results;$i++) {
					if(sizeof($list_patho[$i]) > 0){
						for($j = 0; $j < sizeof($list_patho[$i]); $j++){				
							$this->list_patho_main[$k]['RESULT_PATHO'] = $list_patho[$i][$j]['desc'];
							$l = 0;
							// traitement pour boucler sur la liste de TOUS les Symptomes associés à UNE seule Pathologie
							while($l < sizeof($list_sympt[$i])){
								$this->list_sympt_main[$k][$l]['RESULT_SY'] = $list_sympt[$i][$l]['desc'];

								$l++;
							}
							
							$k++;		
						}
					}
				}	


				if($this->list_patho_main == null){
					$this->list_patho_main[0]['RESULT_PATHO'] = "Pas de résultats trouvés pour cette recherche";
				}

				if($this->list_sympt_main == null){
					$this->list_sympt_main[0][0]['RESULT_SY'] = "Pas de résultats trouvés pour cette recherche";
				}

			}

		}

	}
?>