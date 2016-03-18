<?php 

include '../application/config/config.php';
include '../application/models/SQLengine.php';


//$SQLdb = new BaseModel();
//$SQLdb->dbcon($host, $user, $pass, $db, $charset , $dbh);
//$db = $SQLdb->$dbh;


$req = new Requetes();
$resultat = $req->getPathos();

// $result_fh = $this->engine->getInfo_iQlink($g2rA, $g2rB, "Réel");
// $data_fh = $result_fh['data'];
// $nb_fh = $result_fh['nb'];


print_r($resultat);




?>