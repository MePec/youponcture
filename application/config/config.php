<?php

// définitions des Constantes utilisées pour tout le site
define("TPL_DIR", "../application/templates/");

define('DB_DSN', 'mysql:dbname=youponcture_develop;host=localhost');
define('DB_USER', 'guest');
define('DB_PASSWORD', 'Y0uPonctur3');

// define('DB_DSN', 'mysql:dbname=youponcture_develop;host=');
// define('DB_USER', 'christophe');
// define('DB_PASSWORD', 'christophe');

// Style Windows
//define('SMARTY_DIR', 'c:/Program Files (x86)/Smarty/libs/');

// On inclut la classe Smarty
//require_once(SMARTY_DIR . 'Smarty.class.php');

require_once('Smarty.class.php');

require_once("DB.class.php");
require_once("../application/models/Engine.class.php");


?>
