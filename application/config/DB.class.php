<?php
    class DB {
        private $db = null;
		
		/**
	   	 * Constructeur
	   	 * Ouverture de la connexion à la BDD.
	   	 */
        public function getInstance() {
            if($this->db == null){
                try {
                    $this->db = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
                    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    //$this->db->exec('SET NAMES utf8');
                } catch (PDOException $e) {
                    echo 'Echec de la connexion : ' . $e->getMessage();
                }
            }
			
			return $this->db;
        }
    }
?>