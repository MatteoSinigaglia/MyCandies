<?php


namespace MyCandies\Entities;

use MyCandies\Exceptions\EntityException;
//use MyCandies\Entities;

require_once __DIR__.'/../Exceptions/EntityException.php';
require_once __DIR__ . '/sources.php';

class Entity {

//	public const DB = 0;

	protected $id;

	public function __construct(int $source, mixed $id=null) {
		if ($source !== DB && isset($id) && !is_int($id)) {
			throw new EntityException('The given id is illegal', -1);
		}
		echo 'Id: '.$id;
		$this->id = (int)$id;
	}

	/**
	 * @param int $id
	 * @throws EntityException Throws an exception if the id is already set
	 */
	public function setId(int $id): void {
		if (isset($this->id)) {
			throw new EntityException('Entities already has an id', -2);
		}
		$this->id = $id;
	}

	/**
	 * @return int Returns the entity's id
	 */
	public function getId(): int {
		return $this->id;
	}

	public function getValues() : array {
		$fields = [];
		foreach ($this as $key => $value) {
			$fields[$key] = $value;
		}
		return $fields;
	}

	public function getColumns() : array {
		$columns = array();
		foreach ($this as $column) {
			array_push($columns, $column);
		}
		return $columns;
	}
}