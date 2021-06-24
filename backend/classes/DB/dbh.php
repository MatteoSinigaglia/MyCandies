<?php

namespace DB;


use DB\Exceptions\DBException;
use PDO;
use Exception;
use PDOException;
use InvalidArgumentException;

require_once __DIR__.'/Exceptions/DBException.php';

class dbh {
	private $host;
	private $db;
	private $user;
	private $psw;
	private $charset;
	private $options;

	private $pdo;

	public function __construct() {
		$this->host = 'localhost';
		$this->db = 'MyCandies';
		$this->user = 'root';
        $this->psw = '';
		$this->charset = 'utf8mb4';
		$this->options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		];
	}

	/**
	 * Instantiate a connection with the represented database
	 * @throws DBException upon error creating the connection to the database
	 */
	public function connect() {
		try {

			$dsn = 'mysql:host='.$this->host.';dbname='.$this->db.';charset='.$this->charset.';';
			$this->pdo = new PDO($dsn, $this->user, $this->psw, $this->options);
		} catch (PDOException $e) {

			$output = 'Unable to connect to the database: '.$e->getMessage().' in '.$e->getFile().':'.$e->getLine();
			$this->pdo = null;
			throw new DBException($output, $e->getCode());

		}
	}

	public function disconnect() {
		$this->pdo = null;
	}

	/**
	 * @param string $sql
	 * @param array $parameters
	 * @return mixed
	 * @throws DBException
	 */
	public function query(string $sql, array $parameters = []) {
		try {
			$query = $this->pdo->prepare($sql);
			$query->execute($parameters);
			return $query;
		} catch (PDOException $e) {
			$output = 'Unable to execute the given query: '.$e->getMessage().' in '.$e->getFile().':'.$e->getLine();
			throw new DBException($output, $e->getCode());
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function getLastInsertId() : int {
		return $this->pdo->lastInsertId();
	}

	public function find(string $table, string $column, $value) {
		$query = 'SELECT * FROM `'.$table.'` WHERE `'.$column.'`=:value';

		$parameters = [
			'value' => $value
		];

		try {
			$query = $this->query($query, $parameters);
			return $query->fetchAll();
		} catch (DBException $e) {
			throw $e;
		}
	}

	public function findById(string $table, string $id, int $value, $className, $args =[]) {
		$query = 'SELECT * FROM `'.$table.'` WHERE `'.$id.'` = :value';
		$parameters = [
			'value' => $value
		];

		try {
			$query = $this->query($query, $parameters);
		} catch (DBException $e) {
			throw $e;
		}
		return $query->fetchObject($className, $args);
	}

	public function findAll(string $table) {
		$query = 'SELECT * FROM `'.$table.'`';
		try {
			$query = $this->query($query);
			return $query->fetchAll();
		} catch (DBException $e) {
			throw $e;
		}
	}

	public function insert(string $table, array $fields) : int {
		$parameters = $values = '';
		foreach ($fields as $key => $value) {
			$parameters .= '`'.$key.'`,';
			$values .= ':'.$key.',';
		}
		$parameters = rtrim($parameters, ',');
		$values = rtrim($values, ',');

		$query = 'INSERT INTO `'.$table.'` ('.$parameters.') VALUES ('.$values.')';
		error_log($query);
		try {
			$this->query($query, $fields);
			return $this->pdo->lastInsertId();
		} catch (Exception $e) {
			throw new DBException($e->getMessage(), $e->getCode());
		}
	}

	public function update(string $table, string $id, array $fields) {

		$query = 'UPDATE `'.$table.'` SET ';

		foreach ($fields as $key => $value) {
		    error_log('Array: '.$key.' => '.$value);
			if($key != $id)
				$query .= '`'.$key.'`=:'.$key.',';
		}

		$query = rtrim($query, ',');

		$query .= ' WHERE `'.$id.'`=:'.$id.'';

		error_log($query);

		try {
			$this->query($query, $fields);
		} catch (Exception $e) {
			throw new DBException($e->getMessage(), $e->getCode());
		}
	}

	public function transactionStart() {
		try {
			$this->pdo->beginTransaction();
		} catch (PDOException $e) {
			$output = 'Error starting transaction: '.$e->getMessage().' in '.$e->getFile().':'.$e->getLine();
			throw new DBException($output, $e->getCode());
		}
	}

	public function transactionCommit() {
		try {
			$this->pdo->commit();
		} catch (PDOException $e) {
			$output = 'Error committing transaction: '.$e->getMessage().' in '.$e->getFile().':'.$e->getLine();
			throw new DBException($output, $e->getCode());
		}
	}

	public function transactionRollback() {
		$this->pdo->rollback();
	}
}
