<?php

	require_once ('Home.class.php');

    class Web_Service {

    	/**
		 * Fonction Web_Service_modelDownload
		 * Permet de rediriger vers le Web_service qui telecharge le fichier modele XML
		 */
		public function Web_Service_modelDownload(){
			// gérer contenu de fichier exporté (readfile) : via Smarty???

			readfile("../application/models/pathologie.xml");

			header("Cache-Control: no-cache, must-revalidate");
			header("Cache-Control: post-check=0,pre-check=0");
			header("Cache-Control: max-age=0");
			header("Pragma: no-cache");
			header("Expires: 0");

			header("Content-Type: application/force-download");
			header("Content-disposition: attachment; filename=modele.xml");



			 // exemple pour consommer le WS à partir d'un autre service
			 // $livre = file_get_contents('http://...');
			 // $livre = json_decode(file_get_contents('http://.../'));
		}

		/**
		 * Fonction Web_Service
		 * Permet de rediriger vers le Web_service de vue du modèle
		 */
		public function Web_Service_modelView(){
			header("Location: ../application/models/pathologie.xml");
			// essayer de cacher adresse URL ?
		}

		/**
		 * Fonction Web_Service_modify
		 * Permet de rediriger vers le Web_service
		 */
		public function Web_Service_modify(Smarty $smarty){
			// pour exemple, permet d'ajouter un champs à la balise <pathologie>

			$add = $_GET['champs'];
		  
			// $patho_model = simplexml_load_file('../application/models/pathologie.xml');  
			$patho_model = simplexml_load_file('pathologie.xml');
			$file = "pathologie.xml";
			// $file = "../application/models/pathologie.xml"; => pour version finale

			// 2nde version : ajouter une balise à pathologie
			// $patho_model->pathologie->addChild($add);

			// 1ère version : ajoute attribut à balise existante <meridien>:
			// $nodeChildrens = $patho_model->pathologie->children();
			//$nodeChildrens['meridien']->addAttribute($add,"");
			$patho_model->pathologie->meridien->addAttribute($add,"");

			$patho_model->saveXML($file);
	
			// utilisation parseur de base php5 : simpleXML
			// parse de partie du fichier XML
		    // foreach ($patho_model->pathologie as $patho) {  
			   //  echo "Nom pathologie : $patho->nom\n"; 
			   //  echo "Type meridien : $patho->meridien['type']\n";   
		    // }  
			Home::displayHome($smarty);
		}

		/**
		 * Fonction Web_Service_Calculatrice
		 * Permet de rediriger vers le Web_service Calculatrice
		 */
		public function Web_Service_Calculatrice(Smarty $smarty){

			if(isset($_GET['param1']) && isset($_GET['param1'])){
				$p1 = $_GET['param1'];
				$p2 = $_GET['param2'];
				$sum = $p1 + $p2; 

				echo "Resultat addition : " ;
				echo $sum; 

				Home::displayHome($smarty);
			}	
			else
				echo 'Erreur';
		}

	}
?>