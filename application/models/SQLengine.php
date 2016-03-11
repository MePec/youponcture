<?php

class Requetes{

	// 1ère fonction de Test PDO pour requete SQL
	/* fonction getSymptoms()
	 Description : permet de récupérer la liste de toutes les pathologies de la BDD
	 Paramètres : ...
	*/
	function getPathos() {
	$sql = "SELECT * FROM patho ;";

	$querry = $dbh->prepare($sql);
	$querry->execute();

	$result = $querry->fetch(PDO::FETCH_ASSOC);

	return $result;
	}

}

?>