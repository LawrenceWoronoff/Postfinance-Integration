<?php

/**
 * Class de gestions de base de donnée MYSQL avec PDO
 * @author TOMASINA PIERRE - Meta-Gen (sora@meta-gen.com)
 */
class DBmysqli
{
	/**
	 * @var objet this class
	 */
	static private $_instance;

	/**
	 * @var objet connexion PDO
	 */
	private $DBH = null;

	/**
	 * @var objet requête PDO Statement
	 */
	private $STMT = null;

	/**
	 * @var boolean défini si une transaction est active ou pas
	 */
	public $Transaction = false;

	/**
	 * Constructeur de class
	 */
	public function __construct()
	{
		$this->connexion();
		$this->Transaction = false;
	}

	/**
	 * Etablie une connexion persistante et la set dans la variable $dbh
	 * @return void;
	 */
	public function connexion()
	{
		$dsn = 'mysql:host=localhost;dbname=masmisso_misso';
		$user = 'masmisso_dbusr';
		$password = '-B7vLITpoL2_';

		try {
			$this->DBH = new PDO($dsn, $user, $password, array(PDO::ATTR_PERSISTENT => true));
			$this->DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->DBH->query("SET NAMES 'utf8';");
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Méthode d'instance static Sigleton pour garder la même instance de class à chaque appel
	 * @return objet l'instance de la class courrante
	 */
	public static function getInstance()
	{
		if (is_null(self::$_instance))
		{
			self::$_instance = new DBmysqli();
		}
		return self::$_instance;
	}

	/**
	 * Retourne l'objet PDO connecté à la base
	 */
	public function get_dbh()
	{
		return $this->DBH;
	}

	/**
	 * Retourne l'objet Statement
	 */
	public function get_stmt()
	{
		return $this->STMT;
	}

	/**
	 * Réinisialise l'objet Statement à NULL
	 * @infos tré utile après l'execution de requêtes préparé car ca vide l'object
	 */
	public function rez_stmt()
	{
		$this->STMT = null;
	}

	/**
	 * Escape string by quote PDO
	 */
	public function quote($str)
	{
		return $this->DBH->quote($str);
	}

	/**
	 * Démarre une transactions PDO MYSQL
	 */
	public function begin()
	{
		$this->DBH->beginTransaction();
		$this->Transaction = true;
	}

	/**
	 * Commit une transaction et re passe en mode autocommit
	 */
	public function commit()
	{
		$this->DBH->commit();
		$this->Transaction = false;
	}

	/**
	 * Annule les requêtes éxecuté durant la transaction et re passe en mode autocommit
	 */
	public function rollBack()
	{
		$this->DBH->rollBack();
		$this->Transaction = false;
	}

	/**
	 * Retourne le dernier id inséré en base de donnée
	 */
	public function lastInsertId()
	{
		return $this->DBH->lastInsertId();
	}

	/**
	 * Declanche une requête qui retourne simplement le nombre de ligne, pour INSERT, UPDATE, DELETE
	 * @param string requête SQL
	 * @return Int | Arrêt du script si Erreur Exception
	 */
	public function exec($sql)
	{
		$r = false;
		try {
			$r = $this->DBH->exec($sql);
		}
		catch (Exception $e)
		{
			$this->err($e);
		}
		return $r;
	}

	/**
	 * Declanche une requête qui retourne un object PDO STATEMENT pour y traiter le résultat
	 * @param string requête SQL
	 * @return Objet Statement | Arrêt du script si Erreur Exception
	 */
	public function query($sql)
	{
		$r = false;
		try {
			$this->STMT = $this->DBH->query($sql);
			$r = $this->STMT;
		}
		catch (Exception $e)
		{
			$this->err($e);
		}
		return $r;
	}

	/**
	 * Declanche une requête préparé
	 * @param string requête SQL préparé
	 * @return Boolean TRUE | Arrêt du script si Erreur Exception
	 */
	public function prepare($sql)
	{
		$r = false;
		try {
			$this->STMT = $this->DBH->prepare($sql);
			$r = true;
		}
		catch (Exception $e)
		{
			$this->err($e);
		}
		return $r;
	}

	/**
	 * Exécute une requête préparé
	 * @param array données de la requête adapté pour la req préparé précédement
	 * @return Boolean TRUE si réussi ou FALSE sinon | Arrêt du script si Erreur Exception
	 */
	public function executePrepare(array $data)
	{
		$r = false;
		try {
			$r = $this->STMT->execute($data);
		}
		catch (Exception $e)
		{
			$this->err($e);
		}
		return $r;
	}

	/**
	 * Éxecute une requête SQL et récupère les valeur avec un fetch
	 * @param string requête SQL
	 * @param string Fetch_Style
	 * @return Array | Arrêt du script si Erreur Exception
	 */
	public function fetch($sql, $fetch_style = null)
	{
		$r = false;
		$this->query($sql);
		if ($fetch_style !== null)
			$r = $this->STMT->fetch($fetch_style);
		else
			$r = $this->STMT->fetch();
		$this->rez_stmt();
		return $r;
	}

	/**
	 * Éxecute une requête SQL et récupère les valeur avec un fetchAll
	 * @param string requête SQL
	 * @param string Fetch_Style
	 * @param int index de la colone à retourner si présent
	 * @return Array | Arrêt du script si Erreur Exception
	 */
	public function fetchAll($sql, $fetch_style = null, $column_index = null)
	{
		$r = false;
		$this->query($sql);
		if ($fetch_style !== null && $column_index !== null)
			$r = $this->STMT->fetchAll($fetch_style, $column_index);
		elseif ($fetch_style !== null && $column_index === null)
			$r = $this->STMT->fetchAll($fetch_style);
		else
			$r = $this->STMT->fetchAll();
		$this->rez_stmt();
		return $r;
	}

	/**
	 * Gestions erreur du try {} catch{}
	 */
	private function err($e)
	{
		$msg = "Une erreur s'est produite. Nous tentons de la résoudre dans les plus brefs délais. Vous pourrez bientôt réessayer.";
		if ($this->Transaction)
			$this->rollBack();
		$errMSG = $e->getMessage() . ($this->STMT !== null ? ' ET errorInfo'.$this->STMT->errorInfo() : '');
		throw new Exception("$msg [--] $errMSG");
	}

}

?>