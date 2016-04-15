<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '0');

// définitions des Constantes utilisées pour tout le site
define("TPL_DIR", "../application/templates/");

// define('DB_DSN', 'mysql:dbname=youponcture_develop;host=localhost');
// define('DB_USER', 'root');
// define('DB_PASSWORD', '');

define('DB_DSN', 'mysql:dbname=youponcture_develop;host=localhost');
define('DB_USER', 'guest');
define('DB_PASSWORD', 'Y0uPonctur3');

// On inclut la classe Smarty
require_once('Smarty.class.php');

require_once("DB.class.php");
require_once("../application/models/Engine.class.php");

require_once("../vendors/magpierss/rss_fetch.inc");

?>
