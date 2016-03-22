<?php

require_once("../application/controllers/CheckValues.class.php");

print "Login ?: ".CheckValues::checkLogin("Maxb018")."<br/>";
print "Pwd ?: ".CheckValues::checkPwd("Test19ce")."<br/>";
print "E-mail ?: ".CheckValues::checkEmail("maxime.brunet@cpe.fr")."<br/>";


?>