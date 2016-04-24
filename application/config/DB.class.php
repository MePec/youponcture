<?php
    class DB {
		private $dbh = null;

        // private $db_dsn =  'mysql:dbname=youponcture_develop;host=localhost';
        // private $db_user = 'root';
        // private $db_password = '';

        private $db_dsn =  'mysql:dbname=youponcture_develop;host=localhost';
        private $db_user = 'guest';
        private $db_password = 'Y0uPonctur3';

		/**
	   	 * Constructeur
	   	 * Ouverture de la connexion à la BDD.
	   	 */
        public function getInstance() {
            if($this->dbh == null){
                try {
                    $this->dbh = new PDO($this->db_dsn, $this->db_user, $this->db_password);
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