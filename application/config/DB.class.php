<?php
    class DB {
		private $dbh = null;

        private $host = 'localhost';
        private $port = '3306';
        private $db = 'youponcture_develop';
        private $user = 'christophe';
        private $pass = 'christophe';
        private $charset = 'utf8';

		/**
	   	 * Constructeur
	   	 * Ouverture de la connexion à la BDD.
	   	 */
        public function getInstance() {
            if($this->dbh == null){
                try {
                    $this->dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
                    //$this->dbh = new PDO('mysql:host='.$host.';dbname='.$db.'', 'root', '', array(PDO::ATTR_PERSISTENT => true));
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