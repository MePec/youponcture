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
        private function __construct() {
            if(self::$dbh == null){
                try {
                    self::$dbh = new PDO($this->db_dsn, $this->db_user, $this->db_password);
                    self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    self::$dbh->exec('SET NAMES utf8');
                } catch (PDOException $exception) {
                    trigger_error("Echec de la connexion : " . $exception->getMessage(), E_USER_WARNING);
                }
            }
        }


        /**
         * /!\ Deprecated
         * Fonction de recupération de l'objet PDO de l'instance DB.
         */
        public static function &getDBH() {
            if (self::$instance == null) {
                self::$instance = new self();
            }
            return self::$dbh;
        }

        /**
         * Fonction de recupération de l'instance DB.
         *
         */
        public static function &getInstance() {
            if (self::$instance == null) {
                self::$instance = new self();
            }
            return self::$instance;
        }


        /**
         * Fonction de préparation des requetes avec l'objet PDO
         *
         */
        public function prepareRequestInDB($sql){
            return self::$dbh->prepare($sql); 
        }
    }
?>