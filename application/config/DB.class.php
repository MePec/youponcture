<?php
    class DB {
		private $dbh = null;

		/**
	   	 * Constructeur
	   	 * Ouverture de la connexion à la BDD.
	   	 */
        public function getInstance() {
            if($this->dbh == null){
                try {
                    $this->dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
                    $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->dbh->exec('SET NAMES utf8');
                } catch (PDOException $e) {
                    echo 'Echec de la connexion : ' . $e->getMessage();
                }
            }
			
			return $this->dbh;
        }
    }
?>