<?php

/* Ce fichier permet de configurer le lien avec la BDD */

class DBconnection{
	//$host = 'www.youponcture.sexidude.com'; //90.66.50.47	
	private $host = 'localhost';
	private $port = '3306';
	private $db = 'youponcture_develop';
	private $user = 'christophe';
	private $pass = 'christophe';
	private $charset = 'utf8';

	private $dbh;

	public function dbcon($host, $user, $pass, $db, $charset , $dbh){
		try
		{

			// Connexion à la BDD
		    $this->dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset='.$charset.'', 'root', '', array(PDO::ATTR_PERSISTENT => true));

		    // Configurations supplémentaires
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION); 

		}
		catch (Exception $e)

		{

		        die('Erreur : ' . $e->getMessage());

		}
	}
} // fin DBconnection



?>