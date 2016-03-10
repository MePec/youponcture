<?php

require_once("config.php");



/*---------test temporaire SMARTY-------------*/
$smarty = new Smarty();

$smarty->assign('name','Pec');

//$smarty->display("display/tpl/accueil_static.tpl");
$smarty->display(TPL_DIR."accueil_static.tpl");

/*---------- Fin du test SMARTY ---------------*/


?>