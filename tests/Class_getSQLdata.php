<?php 

require '../application/config/config.php';
require '../application/models/SQLengine.php';



$SQLeng = new Requetes();
$resultat = $SQLeng->getPathos();

print_r($resultat);

 // while($row = $req->fetch()) {    
 //      echo '<a href="membre-'.$row['id'].'.html">'.$row['pseudo'].'</a><br/>';    
 // } 



?>