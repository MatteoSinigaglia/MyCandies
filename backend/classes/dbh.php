<?php

namespace DB;


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
		$this->port = '8080';
		$this->psw = 'vixxyf-jYtcyq-fyxwi5';
		$this->user = 'tw';
		$this->charset = 'utf8';
		$this->options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		];
	}

	private function connect() {
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

	private function disconnect() {
		$this->pdo = null;
	}

	/**
	 * @param string $sql
	 * @param array $parameters
	 * @return mixed
	 * @throws Exception
	 */
	private function query(string $sql, array $parameters = []) {
		$query = null;
		try {
			$query = $this->pdo->prepare($sql);
			$query->execute($parameters);
		} catch (PDOException $e) {
			throw new Exception('Exception in dbh::query()'.$e->getMessage(), $e->getCode());
		}
		return $query;
	}

//	TODO: consider turning function public, requires connection/disconnection and exception handling
	private function insert(string $table, array $fields) {
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
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}

	private function isEmailAvailable($email) {
		$sql = 'SELECT count(`id`) as `Users` FROM `Customers` WHERE `email` = :email';
		try {
			$result = $this->query($sql, ['email' => $email]);
			return $result->fetchColumn() != 0;
		} catch (Exception $e) {
			throw new Exception('Exception looking for email duplicates'.$e->getMessage(), 1);
		}
	}

	public function newUser($user) {
		try {
			$this->connect();

			if($this->isEmailAvailable($user['email'])) {
				throw new InvalidArgumentException('Email already in use', 1);
			}

			$this->pdo->beginTransaction();
			$table = 'Customers';

//			Take from $user only the Customers' table fields
			$customer = [
				'first_name' => $user['first_name'],
				'last_name' => $user['last_name'],
				'email' => $user['email'],
				'password' => $user['password']
			];
			$this->insert($table, $customer);
//            $userId = $this->pdo->lastInsertId();

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
}