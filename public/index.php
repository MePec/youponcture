<?php
	//session_cache_limiter('private_no_expire, must-revalidate');
	session_start();	

	require_once("../application/config/config.php");

	//require_once("../application/config/DB.class.php");
	//require_once("../application/models/Engine.class.php");
	require_once(CTL_DIR."/Manager.class.php");

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
?>