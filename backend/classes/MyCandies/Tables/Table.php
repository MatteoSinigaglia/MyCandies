<?php


namespace MyCandies\Tables;


use DB\dbh;

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
		$query = 'SELECT * FROM `'.$this->table.'` WHERE `'.$this->primaryKey.'` = :value';
		$parameters = [
			'value' => $value
		];

		$query = $this->dbh->query($query, $parameters);
		$query->fetchObject($this->className, $this->constructorArgs);
	}

	public function find(array $where = null, string $orderBy = null, string $groupBy = null, array $having = null, string $limit = null, string  $offset = null): array {
		$query = 'SELECT * FROM `'.$this->table.'`';
		$parameters = [];

		if (!empty($where)) {
			$query .= ' WHERE `' . $where['column'] . '` = :where';
			$parameters['where'] = $where['value'];
		}

		if (!empty($orderBy)) {
			$query .= ' ORDER BY `'.$orderBy.'`';
		}
		if (!empty($groupBy)) {
			$query .= ' GROUP BY `'.$groupBy.'`';
		}
		if (!empty($having)) {
			$query .= ' HAVING `'.$having['column'].'` = :having';
			$parameters['having'] = $having['value'];
		}
		if (!empty($limit)) {
			$query .= ' LIMIT `'.$limit.'`';
		}
		if (!empty($offset)) {
			$query .= ' OFFSET `'.$offset.'`';
		}

		$query = $this->dbh->query($query, $parameters);
		return $query->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs);
	}

	private function insert(array $fields) : string {
		
		$query = 'INSERT INTO `'.$this->table.'` (';
		$values = '';
		
		foreach ($fields as $key => $value) {
			$query .= '`'.$key.'`,';
			$values .= ':'.$key.',';
		}

		$query = rtrim($query, ',');
		$values = rtrim($values, ',');

		$query .= ') VALUES ('.$values.')';

		$fields = $this->processDates($fields);

		$this->dbh->query($query, $fields);

		return $this->dbh->getLastInsertId();
	}

	private function update(array $fields) {

		$query = 'UPDATE `'.$this->table.'` SET `';

		foreach ($fields as $key => $value) {
			$query .= '`'.$key.'` = :'.$key.',';
		}

//		Remove last ',' inserted in foreach statement
		$query = rtrim($query, ',');

		$query .= ' WHERE `'.$this->primaryKey.'` = :primaryKey';

		$fields['primaryKey'] = $fields[$this->primaryKey];

		$fields = $this->processDates($fields);

		$this->query($query, $fields);
	}

	public function delete(int $id) {
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

		$this->dbh->query($query, $parameters);
	}

	private function processDates(array $fields): array {

		define('DATE_FORMAT', 'YYYY-mm-dd');
		foreach ($fields as $key => $value) {
			if ($value instanceof \DateTime) {
				$fields[$key] = $value->format(DATE_FORMAT);
			}
		}
		return $fields;
	}

	public function save(array $record) {
		$entity = new $this->className(...$this->constructorArgs);

		try {
			if ($record[$this->primaryKey] == '') {
				$record[$this->primaryKey] = null;
			}
			$insertId = $this->insert($record);

			$entity->{$this->primaryKey} = $insertId;
		}
		catch (\PDOException $e) {
			$this->update($record);
		}

		foreach ($record as $key => $value) {
			if (!empty($value)) {
				$entity->$key = $value;
			}
		}

		return $entity;
	}
}