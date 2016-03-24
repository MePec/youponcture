<?php
	class Search {
		/**
		 * Fonction displaySearch
		 * Permet d'afficher la page principale de recherches (sans traitements)
		 */
		public function displaySearch(Engine $engine, Smarty $smarty){

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
			$this->smarty->assign('meridiens',$list_merid);


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
		public function submitForm_KeywordSearch(Engine $engine, Smarty $smarty){
				if (isset($_POST['keywords']) && !empty($_POST['keywords']))
					$data_send = true;
				else{
					$data_send = false;
					header('Location: index.php?p=2'); //prévoir message d'erreur
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
							$list_patho_ky[$i]['PATHOS'] = $data_patho_ky[$i]['desc'];
						}
					}
					$smarty->assign('patho_ky',$list_patho_ky);
					//var_dump($list_patho_ky);

					// récupération des symptomes selon le mot-clé
					// $result_sympt_ky = $engine->getSymptoms_Keywords($key);
					// $data_sympt_ky = $result_sympt_ky['data'];
					// $nb_sympt_ky = $result_sympt_ky['nb'];

					// $list_sympt_ky = array();

					// if($nb_sympt_ky > 0) {								
					// 	for($i = 0; $i < $nb_sympt_ky; $i++){	
					// 		$list_sympt_ky[$i]['SYMPTOMS'] = $data_sympt_ky[$i]['desc'];
					// 	}
					// }
					// $smarty->assign('symptoms_ky',$list_sympt_ky);
					//var_dump($list_sympt_ky);

					// $datas = array(
					// 	          array('sympt' => $list_sympt_ky),
					// 	          array('pat' => $list_patho_ky)
					// 	        );
					// $smarty->assign('results',$datas);

				}		

			displaySearch($engine; $smarty);

			$smarty->display(TPL_DIR."content_recherche.tpl");		
		}	

		/**
		 * Fonction submitForm_MainRecherches
		 * Permet de calculer et d'afficher les résultats d'une requete de recherche par critère
		 */
		public function submitForm_MainRecherches(Engine $engine, Smarty $smarty){
				if (isset($_POST['type_patho']) && !empty($_POST['type_patho']) 
					&& isset($_POST['type_meridien']) && !empty($_POST['type_meridien']) 
					&& isset($_POST['caracteristiques_meridien']) && !empty($_POST['caracteristiques_meridien']) )
					$data_send = true;
				else{
					$data_send = false;
					header('Location: index.php?p=3');
					//prévoir message d'erreur
				}

				if($data_send == true){
					$categorie_patho = $_POST['type_patho'];
					$caracter = $_POST['caracteristiques_meridien'];
					$meridien = $_POST['type_meridien'];
					$data_meridien = $engine->getCodeMeridien($meridien);
					$meridien = $data_meridien['data'][0]['code'] ;

					$data_type_mer = $engine->getType_Merid($categorie_patho,$caracter);
					$type_mer = $data_type_mer['data'][0]['type_mer'];

					$result_path = $engine->getList_Patho($meridien,$type_mer);
					$data_path = $result_path['data'];
					$nb_path = $result_path['nb'];

					$list_patho = array();

					if($nb_path > 0) {						
						for($i = 0; $i < $nb_path; $i++){	
							$list_patho[$i]['RESULT_PATHO'] = $data_path[$i]['desc'];
						}
					}
					if($list_patho != null){
						$smarty->assign('patho_res',$list_patho);
					}
					else{
						$msg[0]['RESULT_PATHO'] = "Pas de résultats trouvés pour cette recherche";
						$smarty->assign('patho_res',$msg);
					}
				}

			displayRecherches($engine, $search);	

			$smarty->display(TPL_DIR."content_recherche.tpl");		
		}	
