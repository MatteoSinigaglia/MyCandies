<?php


namespace MyCandies\Tables;


use DateTime;
use DB\dbh;
use DB\Exceptions\DBException;
use MyCandies\Entities\Address;
use MyCandies\Entities\Entity;
use MyCandies\Entities\User;
use MyCandies\Exceptions\EntityException;
use mysql_xdevapi\Exception;

require_once __DIR__.'/../../DB/dbh.php';
require_once __DIR__.'/../../DB/Exceptions/DBException.php';
require_once __DIR__.'/../Entities/Entity.php';
require_once __DIR__.'/../Entities/User.php';
require_once __DIR__.'/../Exceptions/EntityException.php';

class Table {

	private $dbh;
	private $table;
	private $primaryKey;
	private $className;
	private $constructorArgs;

	public function __construct(dbh $dbh, string $tableName, string $primaryKey, string $className = '\stdClass', array $constructorArgs = []) {
		$this->dbh = $dbh;
		$this->table = $tableName;
		$this->primaryKey = $primaryKey;
		$this->className = $className;
		$this->constructorArgs = $constructorArgs;
	}

//	private function query(string $sql, array $parameters = []) {
//		$query = $this->pdo->prepare($sql);
//		$query->execute($parameters);
//		return $query;
//	}

//  TODO: change fields to object

	/**
	 * @param string|null $field
	 * @param mixed|null $value
	 * @return int The count of elements in the table (with $field = $value if specified)
	 */
	public function total(string $field = null, $value = null) : int {
		$query = 'SELECT COUNT(*) FROM `'.$this->table.'`';
		$parameters =  [];

		if (!empty($field)) {
			$query .= ' WHERE `'.$field.'` = :value';
			$parameters = [
				'value' => $value
			];
		}

		$query = $this->dbh->query($query, $parameters);
		$row = $query->fetch();
		return $row[0];
	}

	public function findById(int $value) {
//		think how to handle pk with composite pks
		$query = 'SELECT * FROM `'.$this->table.'` WHERE `'.$this->primaryKey.'` = :value';
		$parameters = [
			'value' => $value
		];

		$query = $this->dbh->query($query, $parameters);
		$query->fetchObject($this->className, $this->constructorArgs);
	}

	public function find(array $where = null, string $orderBy = null, string $groupBy = null, array $having = null, string $limit = null, string  $offset = null) {
		try {
			$query = 'SELECT * FROM `' . $this->table . '`';
			$parameters = [];

			if (!empty($where)) {
				$query .= ' WHERE `' . $where['column'] . '` = :where';
				$parameters['where'] = $where['value'];
			}

			if (!empty($orderBy)) {
				$query .= ' ORDER BY `' . $orderBy . '`';
			}
			if (!empty($groupBy)) {
				$query .= ' GROUP BY `' . $groupBy . '`';
			}
			if (!empty($having)) {
				$query .= ' HAVING `' . $having['column'] . '` = :having';
				$parameters['having'] = $having['value'];
			}
			if (!empty($limit)) {
				$query .= ' LIMIT `' . $limit . '`';
			}
			if (!empty($offset)) {
				$query .= ' OFFSET `' . $offset . '`';
			}

			$query = $this->dbh->query($query, $parameters);
			return $query->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->className, $this->constructorArgs);
		} catch (\Exception $e) {
			echo $e;
		}
	}

	public function insert(object $entity) : string {
		try {

			require_once __DIR__.'/../../../lib/functions.php';
			$slice = [];
			if ($entity instanceof User) {
				$slice = ['first_name', 'last_name', 'email', 'password', 'telephone', 'birthdate'];
			} else if ($entity instanceof Address) {
				$slice = ['province', 'city', 'CAP', 'number'];
			} else {
				$slice = $entity->getColumns();
				echo 'Slice: ';
				var_dump($slice);
			}
			$fields = array_slice_assoc($entity->getValues(), $slice);


//			Prevents from inserting manually an id and leaves the responsibility to the DBMS
			if (isset($fields['id'])) {
				echo 'ID: '.$fields['id'];
				unset($fields['id']);
			}

			$parameters = $values = '';
			foreach ($fields as $key => $value) {
				$parameters .= '`' . $key . '`,';
				$values .= ':' . $key . ',';
			}

			$parameters = rtrim($parameters, ',');
			$values = rtrim($values, ',');

			$query = 'INSERT INTO `'.$this->table.'` ('.$parameters.') VALUES ('.$values.')';
			$fields = $this->processDates($fields);

			echo $query;
			foreach ($fields as $k => $v) {
				echo $k.' => '.$v.' ';
			}
			$this->dbh->query($query, $fields);

			if ($entity instanceof Entity) {
				$entity->setId($this->dbh->getLastInsertId());
			}
			return $this->dbh->getLastInsertId();
		} catch (DBException $e) {
			echo 'DBException';
			throw $e;
		} catch (EntityException $e) {
			echo 'EException';
			var_dump($entity);
			echo $e;
		} catch (Exception $e) {
			echo $e;
		}
	}

	private function update(array $fields) {

//		think how to handle pk with composite pks
		$query = 'UPDATE `'.$this->table.'` SET `';

		foreach ($fields as $key => $value) {
			$query .= '`'.$key.'` = :'.$key.',';
		}

//		Remove last ',' inserted in foreach statement
		$query = rtrim($query, ',');

		$query .= ' WHERE `'.$this->primaryKey.'` = :primaryKey';

		$fields['primaryKey'] = $fields[$this->primaryKey];

		$fields = $this->processDates($fields);

		try {
			$this->dbh->query($query, $fields);
		} catch (DBException $e) {
			throw $e;
		}
	}

	public function delete(int $id) {

//		think how to handle pk with composite pks
		$query = 'DELETE FROM `' . $this->table . '` WHERE `' . $this->primaryKey . '` = :id';
		$parameters = [
			'id' => $id
		];

		$this->query($query, $parameters);
	}

	public function deleteWhere(string $column, mixed $value) {
		$query = 'DELETE FROM `'.$this->table.'` WHERE `'.$column.'` = :value';
		$parameters = [
			'value' => $value
		];

		try {
			$this->dbh->query($query, $parameters);
		} catch (DBException $e) {
			throw $e;
		}
	}

	private function processDates(array $fields): array {

		defined('DATE_FORMAT') || define('DATE_FORMAT', 'YYYY-MM-DD');
		foreach ($fields as $key => $value) {
			if ($value instanceof DateTime) {
				$fields[$key] = $value->format(DATE_FORMAT);
			}
		}
		return $fields;
	}

	public function save(array $record) {

//		think how to handle pk with composite pks
		$entity = new $this->className(...$this->constructorArgs);

		try {
			if ($entity->getId() == '') {
				$entity->setId(null);
			}
//			if ($record[$this->primaryKey] == '') {
//				$record[$this->primaryKey] = null;
//			}
			$insertId = $this->insert($entity);

			$entity->{$this->primaryKey} = $insertId;
		}
		catch (DBException $e) {
			echo $e;
//			$this->update($record);
		}

		foreach ($record as $key => $value) {
			if (!empty($value)) {
				$entity->$key = $value;
			}
		}

		return $entity;
	}
}
