<?php

require_once("../application/config/config.php");
require_once(CTL_DIR."CheckValues.class.php");
require_once(CTL_DIR."Manager.class.php");


print "Login ?: ".CheckValues::checkLogin("Maxb018")."<br/>";
print "Pwd ?: ".CheckValues::checkPwd("Test19ce")."<br/>";
print "E-mail ?: ".CheckValues::checkEmail("maxime.brunet@cpe.fr")."<br/>";

$manager = new Manager(null,null);


?>