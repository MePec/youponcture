<?php

require_once("../application/config/config.php");



/*---------test temporaire SMARTY-------------*/
$smarty = new Smarty();

$smarty->assign('name','Pec');

$smarty->display(TPL_DIR."accueil_static_v2.tpl");
//$smarty->display(TPL_DIR."accueil_static.tpl");

/*---------- Fin du test SMARTY ---------------*/


?>