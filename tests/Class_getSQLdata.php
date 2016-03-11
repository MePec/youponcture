<?php 

include '../application/config/config.php';
include '../application/models/SQLengine.php';


$SQLdb = new BaseModel();
//$SQLdb->dbcon($host, $user, $pass, $db, $charset , $dbh);
//$db = $SQLdb->$dbh;


$req = new Requetes();
$resultat = $req->getPathos();



print_r($resultat);




?>