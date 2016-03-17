<?php
	class Engine {
		private $db;
		
	  	/**
	   	 * Constructeur
	   	 * @param db : objet contenant la connexion à la BDD
	   	 */
	  	public function __construct($db){
	       	$this->db = $db;		
	  	}

	  	/**
	  	 * updatePassword
		 * Permet de mettre à jour le mot de passe de l'utilisateur
		 */
	  	public function updatePassword($login, $password){
	  		$sql  = 'UPDATE utilisateur
	  				 SET MDP = :MDP
	  				 WHERE Login = :Login';

			$query = $this->db->prepare($sql);	
			
			$query->bindValue(':Login', $login);
			$query->bindValue(':MDP', $password);

			$query->execute();	
	  	}

		/**
		 * getExportRow
		 * Permet de récupérer l'ensemble des valeurs que nous souhaitons exporter en CSV
		 * @result le résultat de la requête exécutée
		 */
		public function getExportRow(){
			$sql = 'SELECT DISTINCT p.Plaque,
									p.DPT,
									s.G2R,
									s.nom, 			
									f.Type_support,
									rt.Type_techno,
									s.X,
									s.Y
					FROM trans t
					LEFT JOIN site s ON s.G2R = t.G2R
					LEFT JOIN plaque p ON p.DPT = s.DPT		
					LEFT JOIN ref_support f ON f.ID_support = t.support
					LEFT JOIN ref_techno rt ON rt.ID_techno = t.techno_trans
					GROUP BY t.G2R
					ORDER BY t.G2R';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;				
		}

		/**
		 * getExportCol
		 * Permet de récupérer l'ensemble des valeurs que nous souhaitons exporter en CSV
		 * @result le résultat de la requête exécutée
		 */
		public function getExportCol($filtreSupport = false){
			$condition = null;
			
			if($filtreSupport)
				$condition = 'WHERE t.Support = 1 OR 
								    t.Support = 2 OR 
								    t.Support = 3 OR 
								    t.Support = 4 OR 
								    t.Support IS NULL';
		
			$sql = 'SELECT DISTINCT p.Plaque,
									p.DPT,
									s.G2R,
									s.nom, 			
									b.G2R_fils,
									b.G2R_pere
					FROM trans t
					JOIN site s ON s.G2R = t.G2R
					JOIN plaque p ON p.DPT = s.DPT
					JOIN beb b ON b.G2R_pere = t.G2R	
					'. $condition .'
					GROUP BY t.G2R
					ORDER BY t.G2R';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;				
		}
		
		/**
		 * getExport_PARD
		 * Permet de récupérer l'ensemble des valeurs PARD que nous souhaitons exporter en CSV
		 * @result le résultat de la requête exécutée
		 */
		public function getExport_PARD(){
			$sql =  'SELECT DISTINCT pa.type_PARD,
									 pa.G2R_PARD,
									 s.DPT,
									 s.nom,
									 p.Plaque,
									 repard.Etat AS Etat_PARD,
									 repeftts.Etat As Etat_PEFTTS
					FROM PARD pa
					LEFT JOIN site s ON s.G2R = pa.G2R_PARD
					LEFT JOIN plaque p ON p.DPT = s.DPT	
					LEFT JOIN ref_etat repard ON repard.ID_Etat = pa.Etat_PARD
					LEFT JOIN ref_etat repeftts ON repeftts.ID_Etat = pa.Etat_PEFTTS
					ORDER BY Type_PARD DESC, 
							 G2R_PARD';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;			
		}
		
		/**
		 * getExport_Existant
		 * Permet de récupérer l'ensemble des valeurs de la BDD Existant que nous souhaitons exporter en CSV
		 * @result le résultat de la requête exécutée
		 */
		public function getExport_Existant(){
			$sql = 'SELECT DISTINCT	*
					FROM bdd_existant.working_trans	wt
					ORDER BY CIRCUIT';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;		
		}
		
		/**
		 * getExport_Site_old - version BDD Cible
		 * Permet de récupérer l'ensemble des valeurs Site de la BDD Cible que nous souhaitons exporter en CSV
		 * @result le résultat de la requête exécutée
		 */
		 /*
		public function getExport_Site(){
			$sql = 'SELECT DISTINCT	*
					FROM BDD_CIBLE_TRANS.Site s
					ORDER BY G2R';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;		
		}
		*/
		
		/**
		 * getExport_Site
		 * Permet de récupérer l'ensemble des valeurs Site de la BDD Cible que nous souhaitons exporter en CSV
		 * @result le résultat de la requête exécutée
		 */
		public function getExport_Site(){
			$sql = 'SELECT DISTINCT	*
					FROM BDD_EXISTANT.working_rsu
					ORDER BY G2R';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;		
		}

		
		/**
		 * getExport_Radio
		 * Permet de récupérer l'ensemble des valeurs Radio de la BDD Existant que nous souhaitons exporter en CSV
		 * @result le résultat de la requête exécutée
		 */
		public function getExport_Radio(){
			$sql = 'SELECT DISTINCT	*
					FROM bdd_existant.working_radio	wr
					ORDER BY G2R';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;		
		}
		
		/**
		 * getExport_Equipements
		 * Permet de récupérer l'ensemble des valeurs Equipements de la BDD Existant que nous souhaitons exporter en CSV
		 * @result le résultat de la requête exécutée
		 */
		public function getExport_Equipements(){
			$sql = 'SELECT DISTINCT	*
					FROM bdd_existant.working_equipements we
					WHERE (Etat = "Réel" OR
						   Etat = "MHS" OR
						   Etat = "A Supprimer") AND G2R <> 0
					ORDER BY G2R ;';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;		
		}
		
		/**
		 * getExport_Iqlink
		 * Permet de récupérer l'ensemble des valeurs Iqlink de la BDD Existant que nous souhaitons exporter en CSV
		 * @result le résultat de la requête exécutée
		 */
		public function getExport_Iqlink(){
			$sql = 'SELECT DISTINCT	*
					FROM bdd_existant.working_iqlink wi
					WHERE Etat = "Réel"
					ORDER BY LOPID;';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;		
		}

		/**
		 * getExport_World
		 * Permet de récupérer l'ensemble des valeurs World de la BDD Existant que nous souhaitons exporter en CSV
		 * @result le résultat de la requête exécutée
		 */
		public function getExport_World(){
			$sql = 'SELECT DISTINCT	*
					FROM bdd_existant.working_world ww
					WHERE Etat = "Réelle"';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;		
		}
		
		/**
		 * getExport_PTC
		 * Permet de récupérer l'ensemble des valeurs RSU PTC de la BDD Existant que nous souhaitons exporter en CSV
		 * @result le résultat de la requête exécuée
		 */
		public function getExport_PTC(){
			$sql = 'SELECT DISTINCT	*
					FROM bdd_existant.working_ptc wp';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;	
		}

		/**
		 * getExport_Com_G2R
		 * Permet de récupérer l'ensemble des commentaires sur tous les G2R que nous souhaitons exporter en CSV
		 * @result le résultat de la requête exécutée
		 */
		public function getExport_Com_G2R(){
			$sql =  'SELECT DATE_FORMAT(h.Date_Com, "%Y-%m-%d") AS Date_Com,
							h.G2R,
							rch.Categorie AS CATEGORIE,
							u.NOM,
							u.PRENOM,
							h.Commentaires
					 FROM Historique_G2R h
					 LEFT JOIN ref_cat_historique_g2r rch ON h.Categorie = rch.ID
					 LEFT JOIN Utilisateur u ON u.Login = h.utilisateur
					 ORDER BY h.G2R ASC ; '; 
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;			
		}
		
		/**
		 * getExport_Com_Liens
		 * Permet de récupérer l'ensemble des commentaires sur tous les liens que nous souhaitons exporter en CSV
		 * @result le résultat de la requête exécutée
		 */
		public function getExport_Com_Liens(){
			$sql =  'SELECT DATE_FORMAT(h.Date_Com, "%Y-%m-%d") AS Date_Com,
							h.G2R_A,
							h.G2R_B,
							rch.Categorie AS CATEGORIE,
							u.NOM,
							u.PRENOM,
							h.Commentaires
					 FROM Historique_Liens h
					 LEFT JOIN ref_cat_historique_liens rch ON h.Categorie = rch.ID
					 LEFT JOIN Utilisateur u ON u.Login = h.utilisateur
					 ORDER BY h.G2R_A ASC ; '; 
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;			
		}
		
		/**
		 * getExport_PeuplementBDDCible
		 * Permet de récupérer l'ensemble des valeurs concernant le peuplement de la BDD Cible
		 * @result le résultat de la requête exécutée
		 */
		public function getExport_PeuplementBDDCible(){
			$sql = 'SELECT DISTINCT wr.G2R AS G2R,
									u.Prenom AS PRENOM,
									p.DPT AS DPT,
									s.NOM AS SITE
					FROM bdd_existant.working_radio AS wr
					LEFT JOIN bdd_cible_trans.site s ON s.G2R = wr.G2R
					LEFT JOIN bdd_cible_trans.plaque p ON p.DPT = s.DPT		
					LEFT JOIN bdd_cible_trans.utilisateur u ON u.Login = p.Responsable
					LEFT JOIN bdd_cible_trans.trans t ON t.G2R = wr.G2R
					WHERE wr.MODELE NOT LIKE "Bytel MOCN 4G" AND 
						  wr.MODELE NOT LIKE "Bytel MORAN" AND 
						  wr.MODELE NOT LIKE "VirtualBaieRANSharing" AND 
						  wr.MODELE NOT LIKE "165 nanoBTS" AND 
						  u.Prenom IS NOT NULL AND 
						  t.G2R IS NULL 
					ORDER BY wr.G2R ASC; ';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;		
		}
		
		/**
		 * getRapport_Peuplement
		 * Permet de récupérer l'ensemble des valeurs que nous souhaitons afficher pour l'état de peuplement de la BDD Cible
		 * @result le résultat de la requête exécutée
		 */
		public function getRapport_Peuplement(){
			$sql = 'SELECT DISTINCT COUNT(FINALE.G2R) AS G2R,
									FINALE.Prenom AS Prenom,
									COUNT(FINALE.G2R_TRANS) AS G2R_TRANS
					FROM
						(SELECT DISTINCT wr.G2R AS G2R,
										 u.Prenom AS Prenom,
										 t.G2R AS G2R_TRANS
						 FROM bdd_existant.working_radio AS wr
						 LEFT JOIN bdd_cible_trans.site s ON s.G2R = wr.G2R
						 LEFT JOIN bdd_cible_trans.plaque p ON p.DPT = s.DPT		
						 LEFT JOIN bdd_cible_trans.utilisateur u ON u.Login = p.Responsable
						 LEFT JOIN bdd_cible_trans.trans t ON t.G2R = wr.G2R
						 WHERE wr.MODELE NOT LIKE "Bytel MOCN 4G" AND 
							   wr.MODELE NOT LIKE "Bytel MORAN" AND 
							   wr.MODELE NOT LIKE "VirtualBaieRANSharing" AND 
							   wr.MODELE NOT LIKE "165 nanoBTS" AND 
							   u.Prenom IS NOT NULL) AS FINALE
					GROUP BY FINALE.Prenom;'; 
				
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;	
		}
		
		/**
		 * createTempRapportFH
		 * Permet de créer la table temporaire pour le traitement demandé
		 */
		public function createTempRapportFH($table1,$table2){	
			$sql = 'CREATE TABLE IF NOT EXISTS '. $table1 .'(
						PLAQUE VARCHAR(3),
						DPT INT(3),
						G2R INT(6),
						NOM VARCHAR(45),
						G2R_1 MEDIUMINT(6),
						G2R_2 MEDIUMINT(6), 
						CLE_FH VARCHAR(12), 
						INDEX CLE(CLE_FH))
					ENGINE=MEMORY; '; 
					
			$sql.= 'CREATE TEMPORARY TABLE IF NOT EXISTS '. $table2 .'(
						CLE_FH VARCHAR(12), 
						EQUIPEMENT VARCHAR(50), 
						ETAT CHAR(10), 
						INDEX CLE_FH(CLE_FH), 
						INDEX EQP(EQUIPEMENT), 
						INDEX ETAT(ETAT))
					ENGINE=MEMORY; '; 
				
			$query = $this->db->prepare($sql); 
			$query->execute();
		}
		
		/**
		 * insertData_TempExportCibleCol
		 * Permet d'insérer l'ensemble des valeurs de la BDD Cible exporté au format Colonne dans une table temporaire
		 * @result le résultat de la requête exécutée
		 */
		public function insertData_TempExportCibleCol($table, $p, $d, $g, $n, $g1, $g2, $cle){	
			$sql = 'INSERT INTO bdd_cible_trans.'. $table .'(PLAQUE,DPT,G2R,NOM,G2R_1,G2R_2,CLE_FH)
					VALUES (:PLAQUE,
							:DPT,
							:G2R,
							:NOM,
							:G2R1,
							:G2R2,
							:CLE) ; ';		

			$query = $this->db->prepare($sql);
			
			$query->bindValue(":PLAQUE", $p);
			$query->bindValue(":DPT", $d);
			$query->bindValue(":G2R", $g);
			$query->bindValue(":NOM", $n);
			$query->bindValue(":G2R1", $g1);
			$query->bindValue(":G2R2", $g2);
			$query->bindValue(":CLE", $cle);
			
			$query->execute();							
		}
		
		/**
		 * insertData_TempEquipement
		 * Permet d'insérer des valeurs de la BDD Cible exporté au format Colonne dans une seconde table temporaire (pour le traitement du rapport "Trans FH à lancer")
		 * @result le résultat de la requête exécutée
		 */
		public function insertData_TempEquipement($table){	
			$sql = 'INSERT INTO bdd_cible_trans.'. $table .'(CLE_FH,EQUIPEMENT,ETAT)
					 SELECT DISTINCT FINALE.CLE_FH,
									 FINALE.EQUIPEMENT,
									 FINALE.ETAT
					 FROM
						((SELECT * FROM Eqp)
						 UNION
						 (SELECT IQLINK.CLE_FH,
								 IQLINK.EQUIPEMENT,
								 "FH Legacy" AS ETAT
						  FROM bdd_existant.working_iqlink AS IQLINK
						  LEFT JOIN
							(SELECT * FROM Eqp) AS TBL_PQT ON TBL_PQT.CLE_FH = IQLINK.CLE_FH
						  WHERE IQLINK.Etat = "Réel" AND 
								IQLINK.EQUIPEMENT NOT LIKE "AL2+%" AND
								TBL_PQT.CLE_FH IS NULL)) AS FINALE;';	
			
			$query = $this->db->prepare($sql);
			$query->execute();
		}
		
		/**
		 * deleteTempRapportFH
		 * Permet de supprimer les tables temporaires (pour le traitement du rapport "Trans FH à lancer")
		 * @result le résultat de la requête exécutée
		 */
		public function deleteTempRapportFH($table1,$table2){	
			$sql = 'DROP TABLE IF EXISTS '. $table1 .'; '; 
			$sql.= 'DROP TABLE IF EXISTS '. $table2 .'; ';
			
			$query = $this->db->prepare($sql);
			$query->execute();
		}
		
		/**
		 * getRapport_FH
		 * Permet de récupérer l'ensemble des valeurs que nous souhaitons afficher pour l'état des FH à lancer
		 * @result le résultat de la requête exécutée
		 */
		public function getRapport_FH(){		
			$sql = 'SELECT DISTINCT r.Plaque AS Plaque,
									r.DPT AS DPT,
									r.G2R,
									r.Nom AS nom,
									r.CLE_FH,
									r.G2R_1 AS G2R_pere,
									r.G2R_2 AS G2R_fils,
									TBL_NB_SITES.NB_SITES,
									e.EQUIPEMENT,
									IFNULL(e.ETAT,"A créer") AS ETAT
					FROM bdd_cible_trans.tmp_rapport_exportCol r
					LEFT JOIN 
						(SELECT DISTINCT rap.CLE_FH,
										 COUNT(rap.G2R) AS NB_SITES
						 FROM bdd_cible_trans.tmp_rapport_exportCol rap
						 GROUP BY rap.CLE_FH) AS TBL_NB_SITES ON TBL_NB_SITES.CLE_FH = r.CLE_FH
					LEFT JOIN bdd_cible_trans.tmp_rapport_equipement e ON e.CLE_FH = r.CLE_FH
					WHERE r.G2R_1 <> r.G2R_2
					ORDER BY r.Plaque ASC,
							 r.G2R ASC,
							 r.CLE_FH ASC;';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;	
		}
		
		/**
		 * createTableRapportFH
		 * Permet de créer et de remplir une 
		 * @result le résultat de la requête exécutée
		 */
		public function createTableRapportFH($table,$g2r,$nb,$etat){		
			$sql = 'CREATE TABLE IF NOT EXISTS '. $table .'(
						G2R INT(6),
						NB_SITES INT(3),
						ETAT CHAR(10), 
						INDEX G2R(G2R),
						INDEX ETAT(ETAT))
					ENGINE=MEMORY; ';   
					
			$sql.= 'INSERT INTO bdd_cible_trans.'. $table .'(G2R,NB_SITES,ETAT)
					VALUES (:G2R,
							:NB_SITES,
							:ETAT) ; ';		

			$query = $this->db->prepare($sql);
			
			$query->bindValue(":G2R", $g2r);
			$query->bindValue(":NB_SITES", $nb);
			$query->bindValue(":ETAT", $etat);		

			$query->execute();
			
		}
		
		/**
		 * createTempRapportIP
		 * Permet de créer la table temporaire pour le traitement demandé
		 */
		public function createTempRapportIP($table){	
			$sql = 'CREATE TABLE IF NOT EXISTS '. $table .'(
						G2R INT(6),
						SUPPORT VARCHAR(20),
						PARD INT(6), 
						INDEX G2R(G2R) )
					ENGINE=MEMORY; '; 
				
			$query = $this->db->prepare($sql); 
			$query->execute();
		}
		
		/**
		 * insertData_TempPARD
		 * Permet d'insérer dans une table temporaire l'ensemble des valeurs que nous souhaitons concernant le PARD de chaque site
		 * @result le résultat de la requête exécutée
		 */
		public function insertData_TempPARD($table,$g2r,$pard,$support){		
			$sql = 'INSERT IGNORE INTO bdd_cible_trans.'. $table .'(G2R,SUPPORT,PARD)
					 VALUES  (:G2R,
							  :SUPP,
							  :PARD) ;';
									  
			
			$query = $this->db->prepare($sql);
			
			$query->bindValue(":G2R", $g2r);
			$query->bindValue(":PARD", $pard );
			$query->bindValue(":SUPP", $support);
			
			$query->execute();
		}
		
		/**
		 * getInfoG2R_All
		 * Permet de récupérer les informations associées à tous les G2R
		 * @result un tableau contenant : le résultat de la requête exécutée et le nombre d'enregistrements retournés
		 */
		public function getInfoG2R_All(){
			$sql = 'SELECT *
					FROM bdd_cible_trans.Trans t
					WHERE (t.Support <> "5" AND t.Support <> "6" AND t.Support AND "7" AND t.Support <> "9") ;';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;
		}
		
		/**
		 * getRapport_IP
		 * Permet de récupérer l'ensemble des valeurs que nous souhaitons afficher pour l'état du potentiel IP
		 * @result le résultat de la requête exécutée
		 */
		public function getRapport_IP(){	
			$sql = '(SELECT DISTINCT r.G2R_RADIO AS G2R,
									r.SUPPORT_TRANS AS SUPPORT_EXISTANT,
									r.PARD AS PARD_EXISTANT,
									IFNULL(TABLE_IP_A_LANCER.SUPPORT_CIBLE, r.SUPPORT_TRANS) AS SUPPORT_CIBLE,
									IFNULL(CHGT_PE.PARD_CIBLE, r.PARD) AS PARD_CIBLE,
									IFNULL(TABLE_IP_A_LANCER.ETAT, IFNULL(CHGT_PE.ETAT, "IP Réel")) AS ETAT									
					FROM bdd_existant.working_trans r
					LEFT JOIN
						(SELECT DISTINCT SITES_IP_REEL.G2R_RADIO,
										 SITES_IP_REEL.SUPPORT_EXISTANT,
										 SITES_IP_REEL.PARD_EXISTANT,
										 rs.Type_Support AS SUPPORT_CIBLE,
										 IF ((t.Support = "8" OR t.Support = "10" OR t.Support = "11" OR t.Support = "12") AND (SITES_IP_REEL.SUPPORT_EXISTANT = "FH" OR SITES_IP_REEL.SUPPORT_EXISTANT = "FTTH"),"IP à lancer",NULL) AS ETAT 
						 FROM
							(SELECT DISTINCT r.G2R_RADIO AS G2R_RADIO,
											 r.SUPPORT_TRANS AS SUPPORT_EXISTANT,
											 r.PARD AS PARD_EXISTANT
							 FROM bdd_existant.working_trans r
							 WHERE r.CIRCUIT_ETAT <> "Prévisionnel" AND 
								   r.TECHNO_TRANS = "IP" ) AS SITES_IP_REEL	
						 JOIN bdd_cible_trans.trans t ON t.G2R = SITES_IP_REEL.G2R_RADIO AND t.Support IS NOT NULL
						 JOIN bdd_cible_trans.ref_support AS rs ON rs.ID_Support = t.Support) AS TABLE_IP_A_LANCER ON TABLE_IP_A_LANCER.G2R_RADIO = r.G2R_RADIO
					LEFT JOIN
						(SELECT DISTINCT SITES_IP_REEL.G2R_RADIO,
										 SITES_IP_REEL.SUPPORT_EXISTANT,
										 SITES_IP_REEL.PARD_EXISTANT,
										 tmp.PARD AS PARD_CIBLE,
										 IF(SITES_IP_REEL.PARD_EXISTANT <> tmp.PARD AND SITES_IP_REEL.SUPPORT_EXISTANT <> "FTTS","Changement PE", NULL) AS ETAT
						 FROM
							(SELECT DISTINCT r.G2R_RADIO AS G2R_RADIO,
											 r.SUPPORT_TRANS AS SUPPORT_EXISTANT,
											 r.PARD AS PARD_EXISTANT
							 FROM bdd_existant.working_trans r
							 WHERE r.CIRCUIT_ETAT <> "Prévisionnel" AND 
								   r.TECHNO_TRANS = "IP") AS SITES_IP_REEL
						 JOIN bdd_cible_trans.tmp_rapportIP_pard  AS tmp ON tmp.G2R = SITES_IP_REEL.G2R_RADIO) AS CHGT_PE ON CHGT_PE.G2R_RADIO = r.G2R_RADIO			
					WHERE r.CIRCUIT_ETAT <> "Prévisionnel" AND 
						  r.TECHNO_TRANS = "IP" AND
						  r.G2R_RADIO <> "" )				   
				UNION
				(SELECT DISTINCT LISTE.G2R,
								 wt.SUPPORT_EXISTANT,
								 wt.PARD_EXISTANT,
								 rs.Type_Support AS SUPPORT_CIBLE,
								 tmp.PARD AS PARD_CIBLE,
								 LISTE.ETAT							
				 FROM
					((SELECT DISTINCT G2R,
									  "IP à lancer - avec PR" AS ETAT
						 FROM tmp_rapport_fh AS tmp
						 WHERE ETAT <> "FHP")
					  UNION
					  (SELECT DISTINCT tmp.G2R,
									   "IP à lancer - sans PR" AS ETAT
					   FROM tmp_rapport_fh AS tmp
					   LEFT JOIN
						(SELECT DISTINCT G2R,
										"IP à lancer - avec PR" AS ETAT
						 FROM tmp_rapport_fh AS tmp
						 WHERE ETAT <> "FHP") AS tmp_avec_pr ON tmp_avec_pr.G2R = tmp.G2R
					   WHERE tmp.ETAT = "FHP" AND 
							 tmp_avec_pr.G2R IS NULL)) AS LISTE
				 LEFT JOIN bdd_cible_trans.trans t ON t.G2R = LISTE.G2R AND t.Support IS NOT NULL
				 LEFT JOIN bdd_cible_trans.ref_support AS rs ON rs.ID_Support = t.Support
				 LEFT JOIN bdd_cible_trans.tmp_rapportIP_pard  AS tmp ON tmp.G2R = LISTE.G2R
				 LEFT JOIN 
					(SELECT DISTINCT G2R_RADIO AS G2R,
									 SUPPORT_TRANS AS SUPPORT_EXISTANT,
									 PARD AS PARD_EXISTANT
					 FROM `bdd_existant`.`working_trans`
					 WHERE CIRCUIT_ETAT <> "Prévisionnel") AS wt ON wt.G2R = LISTE.G2R
				 LEFT JOIN 
					(SELECT DISTINCT G2R_RADIO AS G2R,
									 SUPPORT_TRANS AS SUPPORT_EXISTANT,
									 PARD AS PARD_EXISTANT
					 FROM `bdd_existant`.`working_trans`
					 WHERE CIRCUIT_ETAT <> "Prévisionnel" AND
						   TECHNO_TRANS = "IP") AS wt_ip ON wt_ip.G2R = LISTE.G2R
				 WHERE wt_ip.G2R IS NULL )
				 UNION
				 (SELECT DISTINCT t.G2R,
								  wt.SUPPORT_EXISTANT,
								  wt.PARD_EXISTANT,
								  rs.Type_Support AS SUPPORT_CIBLE,
								  tmp.PARD AS PARD_CIBLE,
								  "IP à lancer" AS ETAT		
				  FROM bdd_cible_trans.trans t 
				  LEFT JOIN bdd_cible_trans.ref_support AS rs ON rs.ID_Support = t.Support
				  LEFT JOIN bdd_cible_trans.tmp_rapportIP_pard AS tmp ON tmp.G2R = t.G2R
				  LEFT JOIN 
					(SELECT DISTINCT G2R_RADIO AS G2R,
									 SUPPORT_TRANS AS SUPPORT_EXISTANT,
									 PARD AS PARD_EXISTANT
					 FROM `bdd_existant`.`working_trans`
					 WHERE CIRCUIT_ETAT <> "Prévisionnel") AS wt ON wt.G2R = t.G2R 
				  WHERE t.Support IN ("8","10","11","12"));'; 

			$query = $this->db->prepare($sql); 
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;	
		}
		
		/**
		 * deleteTempRapportIP
		 * Permet de supprimer les tables temporaires créées pour le traitement du rapport Potentiel IP
		 * @result le résultat de la requête exécutée
		 */
		public function deleteTempRapportIP($table){	
			$sql = 'DROP TABLE IF EXISTS '. $table .'; '; 

			
			$query = $this->db->prepare($sql);
			$query->execute();
		}
		
		/**
		 * deleteTmpTableRapport_FH
		 * Permet de supprimer les tables temporaires créées pour le traitement du rapport Potentiel IP
		 * @result le résultat de la requête exécutée
		 */
		public function deleteTmpTableRapport_FH($table){	
			$sql = 'DROP TABLE IF EXISTS '. $table .'; '; 
			
			$query = $this->db->prepare($sql);
			$query->execute();
		}	
		
		/**
		 * getInfo_Site
		 * Permet de récupérer l'ensemble des informations Site pour les G2R
		 * @result le résultat de la requête exécutée
		 */
		public function getInfo_Site($g2r){
			$sql =  'SELECT DISTINCT *
					 FROM BDD_EXISTANT.Working_rsu
					 WHERE G2R = :G2R ;'; 
			
			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R', $g2r);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;			
		}
		
		/**
		 * getConfigDimensionnement
		 * Permet de récupérer les informations de configuration générale pour réaliser le dimensionnement FH auto.
		 * @result le résultat de la requête exécutée
		 */
		public function getConfigDimensionnement(){
			$sql =  'SELECT *
					 FROM BDD_CIBLE_TRANS.config_dimensionnement ;'; 
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;			
		}
		

		/**
		 * getInfo_Crozon
		 * Permet de récupérer l'ensemble des informations Crozon pour le G2R en paramètre
		 * @result le résultat de la requête exécutée
		 */
		public function getInfo_Crozon($g2r){
			$sql =  'SELECT DISTINCT *
					 FROM BDD_CIBLE_TRANS.crozon 
					 WHERE G2R = :G2R ;'; 
			
			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R', $g2r);
			$query->execute();
			
			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;			
		}
		
		/**
		 * getDebit2022
		 * Permet de récupérer le débit dimensionnant à cible 2022 pour le G2R en paramètre
		 * @result le résultat de la requête exécutée
		 */
		public function getDebit2022($g2r){
			$sql =  'SELECT DEBIT
					 FROM dimensionnement 
					 WHERE G2R = :G2R'; 
			
			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R', $g2r);
			$query->execute();
			
			$result = $query->fetch(PDO::FETCH_ASSOC);
				
			return $result;			
		}
		
		/**
		 * getInfo_Attributs_Site
		 * Permet de récupérer l'ensemble des caractéristiques site pour le G2R en paramètre
		 * @result le résultat de la requête exécutée
		 */
		public function getInfo_Attributs_Site($g2r){
			$sql =  'SELECT DISTINCT *
					 FROM BDD_CIBLE_TRANS.attributs_site
					 WHERE G2R = :G2R'; 
			
			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R', $g2r);
			$query->execute();
			
			$result['data'] = $query->fetch(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
			
			return $result;			
		}
		
		
		/**
		 * getInfo_Radio
		 * Permet de récupérer l'ensemble des informations Radio pour le G2R en paramètre
		 * @result le résultat de la requête exécutée
		 */
		public function getInfo_Radio($g2r){
			$sql =  'SELECT DISTINCT *
					 FROM bdd_existant.Working_radio 
					 WHERE G2R = :G2R 
					 ORDER BY EQUIPEMENT ASC,
							  TECHNO ASC;'; 
			
			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R', $g2r);
			$query->execute();
			
			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;			
		}
		
		/**
		 * getInfo_iQlink
		 * Permet de récupérer l'ensemble des informations iQlink pour le segment en paramètre
		 * @param 
		 * @result le résultat de la requête exécutée
		 */
		public function getInfo_iQlink($g2rA, $g2rB, $etat){
			$sql = 'SELECT DISTINCT *
					FROM bdd_existant.working_iqlink
					WHERE ((G2R_A = :G2R_A AND G2R_B = :G2R_B) OR
						   (G2R_A = :G2R_B AND G2R_B = :G2R_A)) AND
						  ETAT = :ETAT
					ORDER BY LOPID ASC,
							 VERSION ASC';

			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R_A', $g2rA);
			$query->bindValue(':G2R_B', $g2rB);
			$query->bindValue(':ETAT', $etat);
			$query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;	
		}

		/**
		 * getInfo_RRCAPRRTRANS
		 * Permet de récupérer l'ensemble des informations RRCAP / RRTRANS pour le segment en paramètre
		 * @param 
		 * @result le résultat de la requête exécutée
		 */
		public function getInfo_RRCAPRRTRANS($g2rA, $g2rB, $etats){
			$sql = 'SELECT DISTINCT *
					FROM bdd_existant.working_liens AS liens
					WHERE ((G2R_1 = :G2R_A AND G2R_2 = :G2R_B) OR
						   (G2R_1 = :G2R_B AND G2R_2 = :G2R_A))';

			if(count($etats) > 1){
				$sql .= ' AND (';
			
				for($i = 0; $i < count($etats); $i++)
					$sql .= ' ETAT = :ETAT_'. $i .' OR';
					
				$sql = substr($sql, 0, strlen($sql) - 2);
				$sql .= ')';
			}
			else
				$sql .= ' AND ETAT = :ETAT';
				
			$sql .= ' ORDER BY liens.REFERENTIEL ASC,
							   liens.NOEUD_1 ASC';
				
			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R_A', $g2rA);
			$query->bindValue(':G2R_B', $g2rB);
			
			if(count($etats) > 1){
				for($i = 0; $i < count($etats); $i++)
					$query->bindValue(':ETAT_'. $i, $etats[$i]);
			}
			else
				$query->bindValue(':ETAT', $etats[0]);
				
			$query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;	
		}
		
		/**
		 * getInfo_World
		 * Permet de récupérer l'ensemble des informations World pour le segment en paramètre
		 * @param 
		 * @result le résultat de la requête exécutée
		 */
		public function getInfo_World($g2rA, $g2rB, $etat){
			$sql = 'SELECT DISTINCT *
					FROM bdd_existant.working_world
					WHERE ((G2R_A = :G2R_A AND G2R_B = :G2R_B) OR
						   (G2R_A = :G2R_B AND G2R_B = :G2R_A)) AND
						  ETAT = :ETAT';

			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R_A', $g2rA);
			$query->bindValue(':G2R_B', $g2rB);
			$query->bindValue(':ETAT', $etat);
			$query->execute();

			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;	
		}

		/**
		 * verifSites
		 * Permet de vérifier la présence de l'ensemble des sites spécificés en  paramètre
		 * @param tabSitses : tableau de sites pour lesquels nous souhaitons vérifier leur existence dans la BDD
		 * @result le résultat de la requête exécutée
		 */
		public function verifSites($tabSites){
			$sql = 'SELECT COUNT(G2R) nb_G2R 
					FROM Site 
					WHERE G2R IN ('. $tabSites .')';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->Fetch(PDO::FETCH_ASSOC);
				
			return $result;
		}
		
		/**
		 * getListG2R
		 * Permet de récupérer la liste des G2R saisis "semi-auto" ou "manuel"
		 * @result le résultat de la requête exécutée
		 */
		public function getListG2R(){
			$sql = 'SELECT DISTINCT p.Plaque,
									s.DPT,
									s.G2R, 
									s.nom,
									IF(t.Support IS NULL OR t.Techno_trans IS NULL, false, true) trans,
									IF(b.G2R_pere IS NULL, false, true) beb
					FROM trans t
					LEFT JOIN beb b ON b.G2R_pere = t.G2R
					LEFT JOIN site s ON s.G2R = t.G2R
					LEFT JOIN plaque p ON p.DPT = s.DPT
					ORDER BY p.Plaque ASC,
							 s.G2R ASC';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;
		}		

		/**
		 * getInfoG2R
		 * Permet de récupérer l'information associée au G2R saisi en paramètre
		 * @param g2r : G2R recherché
		 * @result un tableau contenant : le résultat de la requête exécutée et le nombre d'enregistrements retournés
		 */
		public function getInfoG2R($g2r){
			$sql = 'SELECT DISTINCT * 
					FROM Site s
					JOIN Trans t ON t.G2R = s.G2R
					LEFT JOIN Ref_support rs ON t.Support = rs.ID_Support
					LEFT JOIN Ref_techno rt ON t.Techno_trans = rt.ID_Techno
					WHERE s.G2R = :G2R';
			
			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R', $g2r);
			$query->execute();
			
			$result['data'] = $query->fetch(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;
		}

		/**
		 * getInfoPARD
		 * Permet de récupérer l'information associée au PARD saisi en paramètre
		 * @param pard : PARD recherché
		 * @result un tableau contenant : le résultat de la requête exécutée et le nombre d'enregistrements retournés
		 */		 
		public function getInfoPARD($pard){
			$sql = 'SELECT DISTINCT pa.Type_PARD,
									pa.G2R_PARD,
									s.Nom,
									repard.Etat AS Etat_PARD,
									repeftts.Etat AS Etat_PEFTTS
					FROM pard pa
					LEFT JOIN site s ON s.G2R = pa.G2R_PARD
					LEFT JOIN ref_etat repard ON pa.Etat_PARD = repard.ID_Etat
					LEFT JOIN ref_etat repeftts ON pa.Etat_PEFTTS = repeftts.ID_Etat
					WHERE pa.G2R_PARD = :G2R_PARD AND
						  s.G2R IS NOT NULL';		
			
			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R_PARD', $pard);
			$query->execute();
			
			$result['data'] = $query->fetch(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;
		}
		
		/**
		 * getInfoG2R_Existant
		 * Permet de récupérer l'information associée au G2R saisi en paramètre dans la BDD Existant
		 * @param g2r : G2R recherché
		 * @result un tableau contenant : le résultat de la requête exécutée et le nombre d'enregistrements retournés
		 */
		public function getInfoG2R_Existant($g2r){
			$sql = 'SELECT DISTINCT * 
					FROM bdd_existant.working_trans wt
					WHERE CIRCUIT Like "%'. $g2r .'%"
					ORDER BY CIRCUIT, 
							 EQUIPEMENT_RRCAP ; '; 
			
			$query = $this->db->prepare($sql);
			$query->execute();
				
			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;
		}
		
		/**
		 * getComment_G2R
		 * Permet de récupérer les commentaires pour le g2r spécifié en paramètre
		 * @param g2r : G2R 
		 * @result un tableau contenant : le résultat de la requête exécutée et le nombre d'enregistrements retournés
		 */
		public function getComment_G2R($g2r){
			$sql = 'SELECT DISTINCT h.DATE_COM,
									h.G2R,
									rch.CATEGORIE,
									h.COMMENTAIRES,
									u.NOM,
									u.PRENOM
					FROM historique_G2R h
					LEFT JOIN ref_cat_historique_g2r rch ON h.CATEGORIE = rch.ID
					LEFT JOIN Utilisateur u ON u.Login = h.utilisateur
					WHERE h.G2R Like "%'.$g2r.'%"
					ORDER BY DATE_COM DESC; ';
			
			$query = $this->db->prepare($sql);
			$query->execute();
				
			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;
		}
		
		/**
		 * getComment_Liens
		 * Permet de récupérer les commentaires pour le lien spécifié en paramètre
		 * @param g2r_a et g2r_b : G2R A et G2R B 
		 * @result un tableau contenant : le résultat de la requête exécutée et le nombre d'enregistrements retournés
		 */
		public function getComment_Liens($g2r_a,$g2r_b){
			$sql = 'SELECT DISTINCT h.DATE_COM,
									h.G2R_A,
									h.G2R_B,
									rch.CATEGORIE,
									h.COMMENTAIRES,
									u.NOM,
									u.PRENOM
					FROM historique_liens h
					LEFT JOIN ref_cat_historique_liens rch ON h.CATEGORIE = rch.ID
					LEFT JOIN Utilisateur u ON u.Login = h.utilisateur
					WHERE (h.G2R_A Like "%'.$g2r_a.'%" AND h.G2R_B Like "%'.$g2r_b.'%")
					OR (h.G2R_A Like "%'.$g2r_b.'%" AND h.G2R_B Like "%'.$g2r_a.'%")
					ORDER BY DATE_COM DESC; ';
			
			//prévoir car d'inversion
			
			$query = $this->db->prepare($sql);
			$query->execute();
				
			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
			$result['nb'] = $query->rowCount();
				
			return $result;
		}
		
		/**
		 * getUser
		 * Permet de récupérer le nom-prenom de l'utilisateur logué
		 * @param log : login
		 * @result un tableau contenant : le résultat de la requête exécutée et le nombre d'enregistrements retournés
		 */
		public function getUser($log){
			$sql = 'SELECT DISTINCT PRENOM,
									NOM			
					FROM BDD_CIBLE_TRANS.utilisateur u
					WHERE Login = "'.$log.'" ; '; 
			
			$query = $this->db->prepare($sql);
			$query->execute();
				
			$result['data'] = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;
		}
		
		/**
		 * insertCom_G2R
		 * Permet d'ajouter les données des commentaires dans la table correspondante pour un site
		 * @param $g2r,$user,$category,$date,$com : champs saisis par l'utilisateur
		 */
		public function insertCom_G2R($g2r, $user, $category, $date_time, $com){
			$sql = 'INSERT IGNORE INTO historique_G2R VALUES (:DATE, :G2R, :CATEGORY, :COM, :USER)';
			
			$query = $this->db->prepare($sql);	
			
			$query->bindValue(':G2R', $g2r);
			$query->bindValue(':USER', $user);	
			$query->bindValue(':CATEGORY', $category);
			$query->bindValue(':DATE', $date_time);	
			$query->bindValue(':COM', $com);	
			
			$query->execute();		
		}
		
		/**
		 * insertCom_Liens
		 * Permet d'ajouter les données des commentaires dans la table correspondante pour un lien
		 * @param $g2r_a,$g2r_b,$user,$category,$date,$com : champs saisis par l'utilisateur
		 */
		public function insertCom_Liens($g2r_a,$g2r_b,$user, $category, $date_time, $com){
			$sql = 'INSERT IGNORE INTO historique_liens VALUES (:DATE, :G2R_A, :G2R_B, :CATEGORY, :COM, :USER)';
			
			$query = $this->db->prepare($sql);	
			
			$query->bindValue(':G2R_A', $g2r_a);
			$query->bindValue(':G2R_B', $g2r_b);
			$query->bindValue(':USER', $user);	
			$query->bindValue(':CATEGORY', $category);
			$query->bindValue(':DATE', $date_time);	
			$query->bindValue(':COM', $com);	
			
			$query->execute();		
		}
		
		/**
		 * deleteComment_G2R
		 * Permet de supprimer un commentaire pour le g2r spécifié en paramètre
		 * @param g2r : G2R 
		 */
		public function deleteComment_G2R($g2r,$date_time){
			$sql = 'DELETE FROM historique_G2R
					WHERE historique_G2R.G2R = '.$g2r.' AND 
						  historique_G2R.Date_com = "'.$date_time.'"; '; 
			
			$query = $this->db->prepare($sql);
			$query->execute();
		}
				
		/**
		 * deleteComment_Liens
		 * Permet de supprimer un commentaire pour le g2r spécifié en paramètre
		 * @param g2r_a, g2r_b: G2R A et G2R B
		 */
		public function deleteComment_Liens($g2r_a,$g2r_b,$date_time){
			$sql = 'DELETE FROM historique_liens
					WHERE (historique_liens.G2R_A = '.$g2r_a.' AND 
						  historique_liens.G2R_B = '.$g2r_b.')
						  OR
						  (historique_liens.G2R_A = '.$g2r_b.' AND 
						  historique_liens.G2R_B = '.$g2r_a.') AND
						  historique_liens.Date_com = "'.$date_time.'"; '; 
			
			$query = $this->db->prepare($sql);
			$query->execute();
		}
		
		/**
		 * getTechno
		 * Permet de récupérer l'information associée aux tables Ref_support et Ref_techno
		 * @result un tableau contenant : le résultat de la requête exécutée 
		 */
		public function getTechno(){
			$sql = 'SELECT DISTINCT * 
					FROM Ref_techno
					ORDER BY ID_Techno ASC';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;
		}
		
		/**
		 * getEtat
		 * Permet de récupérer l'information associée aux tables Ref_etat
		 * @result un tableau contenant : le résultat de la requête exécutée
		 */
		public function getEtat(){
			$sql = 'SELECT DISTINCT *
					FROM ref_etat
					ORDER BY ID_Etat ASC';
					
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;
		}
		
		/**
		 * getFils
		 * Permet de récupérer les fils associés au père saisi en paramètre
		 * @param pere : père recherché
		 * @result le résultat de la requête exécutée
		 */		
		public function getFils($pere){
			$sql = 'SELECT DISTINCT * 
					FROM BEB 
					WHERE G2R_pere = :pere';
			
			$query = $this->db->prepare($sql);
			
			$query->bindValue(':pere', $pere);
			$query->execute();
			
			$result = $query->fetch(PDO::FETCH_ASSOC);
				
			return $result;		
		}
		
		/**
		 * getPere
		 * Permet de récupérer les pères associés au père saisi en paramètre
		 * @param fils : fils recherché
		 * @result le résultat de la requête exécutée
		 */		
		public function getPere($fils){
			$sql = 'SELECT DISTINCT * 
					FROM BEB 
					WHERE G2R_fils = :fils';
			
			$query = $this->db->prepare($sql);
			
			$query->bindValue(':fils', $fils);
			$query->execute();
			
			$result = $query->fetch(PDO::FETCH_ASSOC);
				
			return $result;		
		}

		/**
		 * getCles
		 * Permet de récupérer les clés associés aux pères saisis en paramètre
		 * @param tabSites : tableau de sites pour lesquels nous souhaitons récupérer les clés
		 * @result le résultat de la requête exécutée
		 */			
		public function getCles($tabSites){
			$sql = 'SELECT DISTINCT * 
					FROM BEB 
					WHERE G2R_pere IN ('. $tabSites .')';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->FetchAll(PDO::FETCH_KEY_PAIR);
				
			return $result;			
		}
		
		/**
		 * getBeB
		 * Permet de récupérer l'ensemble des BeB présents dans la BDD
		 * @result le résultat de la requête exécutée
		 */
		public function getBeB(){
			$sql = 'SELECT DISTINCT * 
					FROM BEB';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_KEY_PAIR);
				
			return $result;				
		}
	
		/**
		 * insertBeB
		 * Permet d'ajouter un enregistrement dans la table BeB
		 * @param pere, fils : champs saisis par l'utilisateur
		 */
		public function insertBeB($pere, $fils){
			$sql = 'INSERT INTO BEB VALUES (:PERE, :FILS)';
			
			$query = $this->db->prepare($sql);
			
			$query->bindValue(':PERE', $pere);
			$query->bindValue(':FILS', $fils);
						
			$query->execute();
		}
		
		/**
		 * updateBeB
		 * Permet de mettre à jour un enregistrement dans la table BeB
		 * @param pere, fils : champs saisis par l'utilisateur
		 */
		public function updateBeB($pere, $fils){
			$sql = 'UPDATE BEB SET G2R_fils = :FILS WHERE G2R_pere = :PERE';
			
			$query = $this->db->prepare($sql);
			$query->bindValue(':PERE', $pere);
			$query->bindValue(':FILS', $fils);
						
			$query->execute();
		}
	
		/**
		 * verifTrans
		 * Permet de vérifier la présence d'informations trans associées au G2R recherché
		 * @param g2r : G2R recherché
		 * @result le résultat de la requête exécutée
		 */
		public function verifTrans($g2r){
			$sql = 'SELECT COUNT(G2R) nb 
					FROM Trans 
					WHERE G2R = :G2R';
			
			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R', $g2r);
			$query->execute();
			
			$result = $query->Fetch(PDO::FETCH_ASSOC);
				
			return $result;		
		}
	
		/**
		 * insertTrans
		 * Permet d'ajouter des informations Trans dans la table associée
		 * @param g2r, support, techno : champs saisis par l'utilisateur
		 */
		public function insertTrans($g2r, $support, $techno){
			$sql = 'INSERT INTO Trans VALUES (:G2R, :SUPPORT , :TECHNO)';
			
			$query = $this->db->prepare($sql);	
			
			$query->bindValue(':G2R', $g2r);
			$query->bindValue(':SUPPORT', $support);	
			$query->bindValue(':TECHNO', $techno);	
			
			$query->execute();		
		}
	
		/**
		 * insertTransNotPresent
		 * Permet d'ajouter les informations Trans des G2R non saisis par l'utilisateur mais présents dans le BeB
		 * @param tabG2R : tableaux de sites à insérer
		 */
		public function insertTransNotPresent($tabG2R){
			$sql = 'INSERT INTO Trans (G2R)
					SELECT DISTINCT s.G2R 
					FROM Site s 
					LEFT JOIN Trans t ON t.G2R = s.G2R
					WHERE s.G2R IN ('.$tabG2R.') AND t.G2R IS NULL';
			
			$query = $this->db->prepare($sql);
			$query->execute();		
		}

		/**
		 * insert_attributs_site
		 * Permet d'insérer des attributs de site à un G2R particulier
		 * @param $nacelle : champs saisis par l'utilisateur via formulaire
		 */
		public function insert_attributs_site($g2r, $nacelle){
			$sql = 'INSERT INTO attributs_site VALUES (:G2R, :NACELLE)';
			
			$query = $this->db->prepare($sql);	
			
			$query->bindValue(':G2R', $g2r);
			$query->bindValue(':NACELLE', $nacelle);	
			
			$query->execute();		
		}
		
		/**
		 * update_attributs_site
		 * Permet de mettre à jour des attributs pour un site particulier
		 * @param nacelle : champs saisis par l'utilisateur via formulaire
		 */
		public function update_attributs_site($g2r, $nacelle){
			$sql = 'UPDATE attributs_site SET
						Nacelle = :NACELLE
					WHERE G2R = :G2R';
			
			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R', $g2r);
			$query->bindValue(':NACELLE', $nacelle); 
			$query->execute();			
		}
		
		/**
		 * updateTrans
		 * Permet de mettre à jour des informations Trans dans la table associée
		 * @param g2r, support, techno : champs saisis par l'utilisateur
		 */
		public function updateTrans($g2r, $support, $techno){
			$sql = 'UPDATE Trans SET
						Support = :SUPPORT, 
						Techno_trans = :TECHNO
					WHERE G2R = :G2R';
			
			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R', $g2r);
			$query->bindValue(':SUPPORT', $support); 
			$query->bindValue(':TECHNO', $techno);
			$query->execute();			
		}
	
		/**
		 * VerifPARD
		 * Permet de vérifier la présence du PARD spécifié en paramètre
		 * @param pard : PARD recherché
		 * @result le résultat de la requête exécutée
		 */
		public function verifPARD($pard){
			$sql = 'SELECT COUNT(G2R_PARD) nb_PARD 
					FROM PARD 
					WHERE G2R_PARD = :G2R_PARD';
			
			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R_PARD', $pard);
			$query->execute();
			
			$result = $query->Fetch(PDO::FETCH_ASSOC);
				
			return $result;		
		}

		/**
		 * verifG2R_attributs
		 * Permet de vérifier la présence du G2R spécifié en paramètre dans la table attributs_site
		 * @param g2r : G2R recherché
		 * @result le résultat de la requête exécutée
		 */
		public function verifG2R_attributs($g2r){
			$sql = 'SELECT COUNT(G2R) nb 
					FROM attributs_site
					WHERE G2R = :G2R';
			
			$query = $this->db->prepare($sql);
			$query->bindValue(':G2R', $g2r);
			$query->execute();
			
			$result = $query->Fetch(PDO::FETCH_ASSOC);
				
			return $result;		
		}
		
		/**
		 * getListPARD
		 * Permet de récupérer la liste des PARD saisis dans la BDD
		 * @result le résultat de la requête exécutée
		 */
		public function getListPARD($cpe = true){
			if(!$cpe)
				$condition = 'WHERE pa.TYPE_PARD <> "CPE"';
			else
				$condition = null;
		
			$sql =  'SELECT DISTINCT pa.Type_PARD,
									 pa.G2R_PARD,
									 s.DPT,
									 s.Nom,
									 p.Plaque,
									 repard.Etat AS Etat_PARD,
									 reftts.Etat AS Etat_PEFTTS
					FROM PARD pa
					LEFT JOIN ref_etat repard ON repard.ID_Etat = pa.Etat_PARD
					LEFT JOIN ref_etat reftts ON reftts.ID_Etat = pa.Etat_PEFTTS
					LEFT JOIN site s ON s.G2R = pa.G2R_PARD
					LEFT JOIN plaque p ON p.DPT = s.DPT 
					'. $condition .'
					ORDER BY Type_PARD DESC, 
							 G2R_PARD';
					 			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;
		}				
		
		/**
		 * insertPARD
		 * Permet d'ajouter un PARD dans la BDD
		 * @param valeurs à injecter
		 */
		public function insertPARD($pard, $type, $etatPARD, $etatPEFTTS){
			$sql = 'INSERT INTO PARD VALUES (:G2R, :TYPE, :ETAT_PARD, :ETAT_PEFTTS)';
			
			$query = $this->db->prepare($sql);
			
			$query->bindValue(':G2R', $pard);
			$query->bindValue(':TYPE', $type);
			$query->bindValue(':ETAT_PARD', $etatPARD);
			$query->bindValue(':ETAT_PEFTTS', $etatPEFTTS);
			
			$query->execute();				
		}	
		
		/**
		 * updatePARD
		 * Permet de mettre à jour un PARD dans la BDD
		 * @param valeurs à injecter
		 */
		public function updatePARD($pard, $type, $etatPARD, $etatPEFTTS){			
			$sql = 'UPDATE PARD SET Type_PARD = :TYPE,
									Etat_PARD = :ETAT_PARD,
									Etat_PEFTTS = :ETAT_PEFTTS									
					WHERE G2R_PARD = :G2R';			
			
			$query = $this->db->prepare($sql);
			
			$query->bindValue(':G2R', $pard);
			$query->bindValue(':TYPE', $type);
			$query->bindValue(':ETAT_PARD', $etatPARD);
			$query->bindValue(':ETAT_PEFTTS', $etatPEFTTS);
			
			$query->execute();				
		}	

		 /**
		 * updateSupportTechno
		 * Permet de mettre à jour les données SUPPORT/TECHNO du G2R stipulé, dans la BDD Cible
		 * @param valeurs à injecter
		 */
		public function updateSupportTechno($g2r, $support, $techno){			
			$sql = 'UPDATE TRANS SET Support = :SUPP,
									 Techno_trans = :TECHNO									
					WHERE G2R = :G2R
					AND :SUPP IS NOT NULL 
					AND :TECHNO	 IS NOT NULL';			
			
			$query = $this->db->prepare($sql);
			
			$query->bindValue(':G2R', $g2r);
			$query->bindValue(':SUPP', $support);
			$query->bindValue(':TECHNO', $techno);
			
			$query->execute();				
		}	
		
		/**
		 * getCoord
		 * Permet récupérer les coordonnées X et Y (lambert 2) du G2R en paramètre
		 * @param G2R 
		 */
		public function getCoord($g2r){			
			$sql = 'SELECT X,Y
					FROM BDD_EXISTANT.working_rsu
					WHERE G2R = "'. $g2r .'" ';
			
			$query = $this->db->prepare($sql);			
			$query->execute();		
			
			$result = $query->fetch(PDO::FETCH_ASSOC);
			
			return $result;			
		}	
		
		 /**
		 * getRefSupport
		 * Permet récupérer la référence associée aux données SUPPORT : conversion Nom en Chiffre (Référence)
		 * @param valeurs à injecter
		 */
		public function getRefSupport($support){			
			$sql = 'SELECT ID_Support 
					FROM ref_support
					WHERE Type_Support = "'. $support .'" ';
			
			$query = $this->db->prepare($sql);		
			$query->execute();		
			
			$result = $query->fetch(PDO::FETCH_ASSOC);
			
			return $result;			
		}	
		
		 /**
		 * getRefTechno
		 * Permet récupérer la référence associée aux données TECHNO : conversion Nom en Chiffre (Référence)
		 * @param valeurs à injecter
		 */
		public function getRefTechno($techno){			
			$sql = 'SELECT ID_Techno 
					FROM ref_techno
					WHERE Type_Techno = "'. $techno .'" ';	
			
			$query = $this->db->prepare($sql);		
			$query->execute();		
			
			$result = $query->fetch(PDO::FETCH_ASSOC);
			
			return $result;		
		}	
		
		/**
		 * deletePARD
		 * Permet de supprimer l'enregistrement PARD sélectionné
		 * @param pard : pard que nous souhaitons supprimer
		 */
		public function deletePARD($pard){
			$sql = 'DELETE FROM Pard WHERE G2R_PARD = :G2R_PARD';
			
			$query = $this->db->prepare($sql);
			
			$query->bindValue(':G2R_PARD', $pard);
			
			$query->execute();		
		}
	
		/**
		 * getSupport
		 * Permet de récupérer l'information associée aux tables Ref_support et Ref_techno
		 * @result un tableau contenant : le résultat de la requête exécutée 
		 */
		public function getSupport($cpe = null){		
			$sql = 'SELECT DISTINCT * 
					FROM Ref_support';
			
			if($cpe == 1)
				$sql .= ' WHERE CPE = "'. $cpe .'" ;';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;
		}
		
		/**
		 * getCategory_g2r
		 * Permet de récupérer l'information associée à la table Catégorie pour un site
		 * @result un tableau contenant : le résultat de la requête exécutée 
		 */
		public function getCategory_g2r(){		
			$sql = 'SELECT DISTINCT * 
					FROM ref_cat_historique_g2r';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;
		}
		
		/**
		 * getCategory_liens
		 * Permet de récupérer l'information associée à la table Catégorie pour un lien
		 * @result un tableau contenant : le résultat de la requête exécutée 
		 */
		public function getCategory_liens(){		
			$sql = 'SELECT DISTINCT * 
					FROM ref_cat_historique_liens';
			
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
				
			return $result;
		}
		/**
		 * verifPerm
		 * Permet de vérifier si l'utilisateur loggué a les droits pour modifier/ajouter des G2R sur la plaque désirée
		 * @param g2r : G2R à vérifier
		 * @login : login de l'utilisateur à vérifier
		 * @result le résultat de la requete
		 */
		public function verifPerm($g2r, $login){
			$sql = "SELECT COUNT(p.Plaque) nb_plaque
					FROM site s
				    JOIN plaque p ON p.DPT = s.DPT
					JOIN permission pr ON pr.Plaque = p.Plaque
					WHERE s.G2R = :G2R AND
						  pr.Login = :LOGIN";
			
			$query = $this->db->prepare($sql);
			
			$query->bindValue(':G2R', $g2r);
			$query->bindValue(':LOGIN', $login);
			
			$query->execute();
			
			$result = $query->Fetch(PDO::FETCH_ASSOC);
				
			return $result;		
		}	
		
		/**
		 * verifResponsable
		 * Permet de vérifier si l'utilisateur est un responsable de plaque
		 * @login : login de l'utilisateur à vérifier
		 * @result le résultat de la requete
		 */
		public function verifResponsable($login){
			$sql = "SELECT DISTINCT Login
					FROM bdd_cible_trans.permission p
					WHERE Login = :LOGIN ; "; 
			
			$query = $this->db->prepare($sql);			
			$query->bindValue(':LOGIN', $login);
			$query->execute();	
				
			return $query->rowCount();
		}	
				
		/**
		 * exportAutomatic
		 * Permet d'exporter (par tache planifiée) les liste des G2R-BEB et PARD
		 * @param $filename1 : chemin où s'enregistre le .csv exporté pour les G2R-BEB
		 * @$filename2 :  chemin où s'enregistre le .csv exporté pour les PARD
		 */
		public function exportAutomatic($filename1,$filename2) {
			//Export BEB de BDD Cible Trans
			$sql = "SELECT DISTINCT p.Plaque,
									s.G2R,
									s.nom,
									f.Type_support,
									rt.Type_techno
												
					INTO OUTFILE '$filename1'
					FIELDS TERMINATED BY ';' OPTIONALLY ENCLOSED BY '\"'
					LINES TERMINATED BY '\n'
					FROM trans t
					LEFT JOIN site s ON s.G2R = t.G2R
					LEFT JOIN plaque p ON p.DPT = s.DPT
					LEFT JOIN ref_support f ON f.ID_support = t.support
					LEFT JOIN ref_techno rt ON rt.ID_techno = t.techno_trans
					GROUP BY t.G2R
					ORDER BY t.G2R";
					
			$query = $this->db->prepare($sql);
			$query->execute();
			
			//Export PARD de BDD Cible Trans
			$sql =  "SELECT DISTINCT pa.type_PARD,
									pa.G2R_PARD,
									s.DPT,
									s.nom,
									p.Plaque
												
					INTO OUTFILE '$filename2'
					FIELDS TERMINATED BY ';' OPTIONALLY ENCLOSED BY '\"'
					LINES TERMINATED BY '\n'
					FROM PARD pa
					LEFT JOIN site s ON s.G2R = pa.G2R_PARD
					LEFT JOIN plaque p ON p.DPT = s.DPT
					ORDER BY Type_PARD DESC, 
							 G2R_PARD";
					
			$query = $this->db->prepare($sql);
			$query->execute();					
		}
		
		/**
		 * checkIdentify
		 * Permet de vérifier si l'utilisateur est loggué 
		 * @param $login : Login envoyé par le formulaire $_POST
		 * @param $MDP : MDP envoyé par le formulaire $_POST
		 * @result le résultat de la requete
		 */
		public function checkIdentify($login,$MDP){
			$sql = "SELECT DISTINCT Login, 
									MDP
					FROM  utilisateur
					WHERE Login = :LOGIN AND 
						  MDP = :MDP";
			
			$query = $this->db->prepare($sql);		
			
			$query->bindValue(':LOGIN', $login);
			$query->bindValue(':MDP', $MDP);
			
			$query->execute();
				
			return $query->rowCount();			
		}
		
		/**
		 * getLast_MAJ
		 * Permet de récupérer donnée Etat et MAJ de table Informations (de BDD Existant)
		 * @result le résultat de la requete
		 */
		public function getLast_MAJ(){
			$sql = "SELECT max(date_MAJ) 
					FROM bdd_existant.Informations 
					WHERE etat = 'OK' ; ";
			
			$query = $this->db->prepare($sql);		
			$query->execute();
			
			$result = $query->fetch(PDO::FETCH_ASSOC);
			
			return $result;			
		}
		
		/** 
		 * getEtatMAJaDate
		 * Récupère et vérifie si la MAJ est du jour même des référentiels (traitement "Importation" uniquement")
		 * @param categorie : catégorie recherchée
		 * @return le résultat de la requête exécutée
		 */
		public function getEtatMAJaDate($categorie){
			$dated = date("Y-m-d",strtotime("-1 days"));
			
			$sql = 'SELECT DISTINCT DATE_EXPORT_SOURCES,
									DATE_FIN,
									NB_ERREURS
					FROM Informations.TABLEAU AS TABLEAU
					WHERE TABLEAU.DATE_EXPORT_SOURCES NOT LIKE "0000-00-00" AND
						  TABLEAU.CATEGORIE LIKE "'. $categorie .'" AND
						  TABLEAU.AFFICHAGE LIKE "1" AND
						  (IF("'. $categorie .'" = "G2R_TRANS",TABLEAU.TRAITEMENT LIKE "Importation référentiel G2R Trans depuis IQLINK",TABLEAU.TRAITEMENT LIKE "Importation")) AND
						  TABLEAU.DATE_EXPORT_SOURCES LIKE "'. $dated .'" ;';
			
			$query = $this->db->prepare($sql);		
			$query->execute();
			
			$result = $query->fetch(PDO::FETCH_ASSOC);
			
			return $result;			
		}
		
		/** 
		 * getDerniereMAJReferentiels
		 * Récupère la date de dernière mise à jour des référentiels (traitement "Importation" uniquement")
		 * @param categorie : catégorie recherchée
		 * @return le résultat de la requête exécutée
		 */
		public function getDerniereMAJReferentiels($categorie){
			$sql = 'SELECT DISTINCT DATE_EXPORT_SOURCES,
									DATE_FIN,
									NB_ERREURS
					FROM Informations.TABLEAU AS TABLEAU
					WHERE TABLEAU.DATE_EXPORT_SOURCES NOT LIKE "0000-00-00" AND
						  TABLEAU.CATEGORIE LIKE "'. $categorie .'" AND
						  TABLEAU.AFFICHAGE LIKE "1" AND
						  (TABLEAU.TRAITEMENT LIKE "Importation" OR TABLEAU.TRAITEMENT LIKE "Importation référentiel G2R Trans depuis IQLINK") ;';
			
			$query = $this->db->prepare($sql);		
			$query->execute();
			
			$result = $query->fetch(PDO::FETCH_ASSOC);
			
			return $result;			
		}
		
		/**
		 * deleteSite
		 * Permet de supprimer les données de la table SITE
		 * @return le résultat de la requête exécutée
		 */
		public function deleteSite(){
			$sql = 'TRUNCATE TABLE site';

			$query = $this->db->prepare($sql);		
			$query->execute();
		}
		
		/**
		 * importSite
		 * Permet de mettre à jour les données de la table SITE
		 * @return le résultat de la requête exécutée
		 */
		public function importSite(){
			$sql = 'INSERT IGNORE INTO site
					SELECT DISTINCT rsu_site.CodeSite AS G2R,
									rsu_site.NomSite AS Nom,	
									rsu_adresse.CodeInseeCommune AS CODE_INSEE,
									rsu_adresse.CodeDepartementSFR AS DPT,
									rsu_adresse.CodePostal AS CODE_POSTAL,
									rsu_adresse.Ville AS COMMUNE,
									rsu_geolocalisation.LatitudeManuelle AS LATITUDE,
									rsu_geolocalisation.LongitudeManuelle AS LONGITUDE,
									rsu_geolocalisation.LongitudeLambert2e * 1000 AS X,
									rsu_geolocalisation.LatitudeLambert2e * 1000 AS Y
					FROM france_r_rsu.rsu_site AS rsu_site
					LEFT JOIN france_r_rsu.rsu_adresse AS rsu_adresse ON rsu_site.sysId = rsu_adresse.SiteSysId
					LEFT JOIN france_r_rsu.rsu_geolocalisation AS rsu_geolocalisation ON rsu_adresse.GeolocalisationSysId = rsu_geolocalisation.sysId 
					LEFT JOIN france_r_rsu.rsu_regionsfr AS rsu_regionsfr ON rsu_site.RegionSfr = rsu_regionsfr.sysId 
					WHERE (rsu_regionsfr.NomCourtRegionSfr = "MAR") OR 
						  (rsu_regionsfr.NomCourtRegionSfr = "TOU") OR 
						  (rsu_regionsfr.NomCourtRegionSfr = "LYO");';
		
			$query = $this->db->prepare($sql);
			$query->execute();
		}
		
		/**
		 * checkDataRSU
		 * Vérifie si des données sont présentes sous RSU
		 * @return le nombre d'enregistrements
		 */
		public function checkDataRSU(){
			$sql = 'SELECT DISTINCT *    
					FROM france_r_rsu.rsu_site;';
		
			$query = $this->db->prepare($sql);
			$query->execute();
			
			$result = $query->rowCount();	
			
			return $result;	
		}		
	}
?>