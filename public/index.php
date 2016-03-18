<?php

	session_cache_limiter('private_no_expire, must-revalidate');
	session_start();	

	require_once("../application/config/config.php");

	require_once("../application/config/DB.class.php");
	require_once("../application/models/Engine.class.php");
	require_once("../application/controllers/Manager.class.php");

	// Récupération de la page
	if(isset($_GET['p']) && !empty($_GET['p']))
		$page = htmlspecialchars($_GET['p'], ENT_QUOTES);
	else
		$page = null;
	
	if(isset($_GET['q']) && !empty($_GET['q']))
		$section = htmlspecialchars($_GET['q'], ENT_QUOTES);
	else
		$section = null;

	$manager = new Manager($page,$section);














// /*-------------- test temporaire connexion BDD en PDO -----------------*/
// $classDB = new DB();
			
// $dbh = $classDB->getInstance();
// $engine = new Engine($dbh);

// /*---------- Fin du test Connexion BDD ---------------------------------*/

// //start_session();

// $smarty = new Smarty();

// $smarty->assign('name','Pec');

// /*--------- test temporaire Récupération données via Requetes SQL -------------*/
// $resultat_patho = $engine->getPathos();
// $data_patho = $resultat_patho['data'];
// $nb_patho = $resultat_patho['nb'];

// $resultat_merid = $engine->getMeridiens();
// $data_merid = $resultat_merid['data'];
// $nb_merid = $resultat_merid['nb'];

// $resultat_sympt = $engine->getSymptoms();
// $data_sympt = $resultat_sympt['data'];
// $nb_sympt = $resultat_sympt['nb'];

// // récupération résultats Recherche par mots-clé //
// $page = null;
// if (isset($_GET['p']) && !empty($_GET['p']))
// 	$page = htmlspecialchars($_GET['p'], ENT_QUOTES);
// else
// 	$page = null;

// // controle si envoi de formulaire de recherche
// if	($page == 2){
// 	$data_send = false;

// 	if (isset($_POST['keywords']) && !empty($_POST['keywords']))
// 		$data_send = true;
// 	else
// 		header('Location: index.php');

// 	if($data_send == true){
// 		$key = $_POST['keywords'];

// 		$result_sympt_ky = $engine->getSymptoms_Keywords($key);
// 		$data_sympt_ky = $result_sympt_ky['data'];
// 		$nb_sympt_ky = $result_sympt_ky['nb'];

// 		$list_sympt_ky = array();

// 		if($nb_sympt_ky > 0) {								
// 			for($i = 0; $i < $nb_sympt_ky; $i++){	
// 				$list_sympt_ky[$i]['SYMPTOMS'] = $data_sympt_ky[$i]['desci'];
// 			}
// 		}
// 		//var_dump($list_sympt_ky);
// 		$smarty->assign('symptoms_ky',$list_sympt_ky);

// 	}
// }		

/*---------- Fin du test Récupération données SQL ---------------*/


/*--------- test temporaire SMARTY-------------*/


//test pour page recherche : assigne tableau associatif
// Assignation Pathologie
//probleme avec SMARTY => assign qu'une fois ou écrase à chaque assignation ce qui crée pas un tableau!!! 

// $this->template->assign_block_vars('result_radio.content',

// $list_patho = array();
// $list_merid = array();
// $list_sympt = array();

// if($nb_patho > 0) {								
// 	for($i = 0; $i < $nb_patho; $i++){	
// 		$list_patho[$i]['PATHO_DESC'] = $data_patho[$i]['desc'];
// 	}
// }
// // else {
// // 	$smarty->assign('pathology',array());
// // }
// $smarty->assign('pathology',$list_patho);

// // Assignation Méridiens
// if($nb_merid > 0) {								
// 	for($i = 0; $i < $nb_merid; $i++){	
// 		$list_merid[$i]['MERID_DESC'] = $data_merid[$i]['nom'];
// 	}
// }
// $smarty->assign('meridiens',$list_merid);


// // Assignation Symptomes
// if($nb_sympt > 0) {								
// 	for($i = 0; $i < $nb_sympt; $i++){	
// 		$list_sympt[$i]['SYMPT_DESC'] = $data_sympt[$i]['desci'];
// 	}
// }
// $smarty->assign('symptoms',$list_sympt);

// //$smarty->display(TPL_DIR."content_accueil.tpl");
// $smarty->display(TPL_DIR."content_recherche.tpl");
// // $smarty->display(TPL_DIR."content_infos.tpl");

/*---------- Fin du test SMARTY ---------------*/




?>