<?php
    class DB {
		
        private static $instance = null;

        private static $dbh = null;

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
        public function __construct() {
            if(Self::$dbh == null){
                try {
                    Self::$dbh = new PDO($this->db_dsn, $this->db_user, $this->db_password);
                    Self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    Self::$dbh->exec('SET NAMES utf8');
                } catch (PDOException $e) {
                    echo 'Echec de la connexion : ' . $e->getMessage();
                }
            }
        }

        public static function &getDB() {
            if (Self::$instance == null) {
                Self::$instance = new Self();
            }
            return Self::$dbh;
        }
    }
?>