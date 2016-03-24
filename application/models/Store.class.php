<?php

class Store() {

	private $db;

  	public function __construct($db) {
       	$this->db = $db;		
  	}

	public function storeSubscript() {
		$sql = "INSERT INTO Users(name,location,email) VALUES (:name,:location,:email)";
		$query = this->db->prepare($sql);
		$query->bindValue(':name', $name);
		$query->bindValue(':location', $location);
		$query->bindValue(':email', $email);
		$result = $query->execute();

		if ($result == false) {
			$status = false;
			return $status;
		}
  	}

}

	  	