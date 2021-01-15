<?php

namespace DB;


use DB\Exceptions\TransactionException;
use PDO;
use Exception;
use PDOException;
use InvalidArgumentException;

class dbh {
	private $host;
	private $db;
	private $user;
	private $port;
	private $psw;
	private $charset;
	private $options;

	private $pdo;

	public function __construct() {
		$this->host = 'localhost';
		$this->db = 'MyCandies';
		$this->port = '3306';
		$this->psw = 'root';
		$this->user = 'root';
		$this->charset = 'utf8';
		$this->options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		];
	}

	public function connect() {
		try {

			$dsn = 'mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->db.';charset='.$this->charset.';';
			$this->pdo = new PDO($dsn, $this->user, $this->psw, $this->options);
			echo 'Connesso ';
		} catch (PDOException $e) {

			$output = 'Unable to connect to the database: '.$e->getMessage().' in '.$e->getFile().':'.$e->getLine();
			$this->pdo = null;
			throw new Exception($output, (int)$e->getCode());

		}
	}

	public function disconnect() {
		$this->pdo = null;
	}

	/**
	 * @param string $sql
	 * @param array $parameters
	 * @return mixed
	 * @throws Exception
	 */
	public function query(string $sql, array $parameters = []) {
		$query = $this->pdo->prepare($sql);
		$query->execute($parameters);
		return $query;
	}

	public function getLastInsertId() : int {
		return $this->pdo->lastInsertId();
	}

	public function find(string $table, string $column, mixed $value) {
		$query = 'SELECT * FROM `'.$table.'` WHERE `'.$column.'`=:value';

		$parameters = [
			'value' => $value
		];

		$query = $this->$query($query, $parameters);
		return $query->fetchAll();
	}

	public function insert(string $table, array $fields) : int {
//		Should check if duplicate and return the already present id/insert the element
		$parameters = $values = '';
		foreach ($fields as $key => $value) {
			$parameters .= '`'.$key.'`,';
			$values .= ':'.$key.',';
		}
		$parameters = rtrim($parameters, ',');
		$values = rtrim($values, ',');

		$query = 'INSERT INTO `'.$table.'` ('.$parameters.') VALUES ('.$values.')';
		echo $query;
		try {
			$this->query($query, $fields);
			return $this->pdo->lastInsertId();
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}

	public function newUser(array $user) {
		try {
			$this->connect();


			if ($this->find('Customers', 'email', $user['email']) > 0) {
				throw new InvalidArgumentException('Email already in use', 1);
			}

			$this->pdo->beginTransaction();
//			$query = 'CALL insertUser(:first_name,:last_name,:email,:telephone,:password,:sex,:date_of_birth,:country,'.
//				':region,:province,:city,:CAP,:street,:street_number)';

//			$this->query($query, $user);
			$table = 'Customers';

//			Take from $user only the Customers' table fields
			$customer = [
				'first_name' => $user['first_name'],
				'last_name' => $user['last_name'],
				'email' => $user['email'],
				'password' => $user['password']
			];
			$this->insert($table, $customer);
            $userId = $this->pdo->lastInsertId();

			$this->pdo->commit();

		} catch (InvalidArgumentException $e) {
			echo 'Invalid Argument';
			throw $e;

		} catch (PDOException $e) {
			echo 'rollback';
			$this->pdo->rollback();
			throw $e;

		} catch (Exception $e) {
			echo 'exception';
			throw $e;

		} finally {
			$this->disconnect();
		}
	}

	public function transactionStart() {
		try {
			$this->pdo->beginTransaction();
		} catch (PDOException $e) {
			throw new TransactionException('Error starting transaction: '.$e->getMessage(), -1);
		}
	}

	public function transactionCommit() {
		try {
			$this->pdo->commit();
		} catch (PDOException $e) {
			throw new TransactionException('No active transaction, unable to commit', -2);
		}
	}

	public function transactionRollback() {
		$this->pdo->rollback();
	}
}