<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '0');

// définitions des Constantes utilisées pour tout le site
define('BASE_DIR', dirname(dirname( dirname( __FILE__ ))).'/');
define('APP_DIR', BASE_DIR.'application/');
define('CONF_DIR', APP_DIR.'config/');
define('CTL_DIR', APP_DIR.'controllers/');
define('MDL_DIR', APP_DIR.'models/');
define('VIEW_DIR', APP_DIR.'views/');
define('TPL_DIR', APP_DIR.'templates/');
define('VDR_DIR', BASE_DIR.'vendors/');

// define('DB_DSN', 'mysql:dbname=youponcture_develop;host=localhost');
// define('DB_USER', 'root');
// define('DB_PASSWORD', '');

define('DB_DSN', 'mysql:dbname=youponcture_develop;host=localhost');
define('DB_USER', 'guest');
define('DB_PASSWORD', 'Y0uPonctur3');

// On inclut la classe Smarty
//require_once('Smarty.class.php');

//require_once("DB.class.php");
//require_once(MDL_DIR."Engine.class.php");

//require_once(VDR_DIR."magpierss/rss_fetch.inc");

?>
