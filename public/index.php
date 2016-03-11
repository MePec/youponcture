<?php

require_once("../application/config/config.php");
require_once("../application/models/SQLengine.php");


//start_session();

/*---------test temporaire SMARTY-------------*/
$smarty = new Smarty();

$smarty->assign('name','Pec');

//test pour page recherche : assigne tableau associatif
$smarty->assign(array(
	'PATHO' => $resultat[0],
	'SYMPT' => $resultat[1]
	));

//$smarty->display(TPL_DIR."content_accueil.tpl");
$smarty->display(TPL_DIR."content_recherche.tpl");


/*---------- Fin du test SMARTY ---------------*/


?>