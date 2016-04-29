<?php
	//session_cache_limiter('private_no_expire, must-revalidate');
	session_start();	

	require_once("../application/config/config.php");
	require_once(CTL_DIR."CheckValues.class.php");
	require_once(CTL_DIR."/Manager.class.php");

	// Récupération de la page
	if(isset($_GET['p']) && CheckValues::checkIsAlphaNum($_GET['p']))
		$page = htmlspecialchars($_GET['p'], ENT_QUOTES);
	else
		$page = null;
	
	if(isset($_GET['q']) && CheckValues::checkIsAlphaNum($_GET['q']))
		$section = htmlspecialchars($_GET['q'], ENT_QUOTES);
	else
		$section = null;

	$manager = new Manager($page,$section);
?>