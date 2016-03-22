<?php

require_once("../application/controllers/CheckValues.class.php");

print "Login ?: ".CheckValues::checkLogin(" Maxb018")."<br/>";
print "Pwd ?: ".CheckValues::checkPwd("Test19ce");


?>