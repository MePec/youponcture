<?php

require_once("../application/config/config.php");

start_session();

/*---------test temporaire SMARTY-------------*/
$smarty = new Smarty();

$smarty->assign('name','Pec');

$smarty->display(TPL_DIR."content_accueil.tpl");

/*---------- Fin du test SMARTY ---------------*/


?>