<?php


namespace MyCandies\Entities;

use MyCandies\Exceptions\EntityException;
//use MyCandies\Entities;

require_once __DIR__.'/../Exceptions/EntityException.php';
require_once __DIR__ . '/sources.php';

class Entity {

	protected $id;

	/**
	 * Entity constructor.
	 * @param int $source
	 * @param mixed|null $id
	 * @throws EntityException
	 */
	public function __construct(int $source, $id=null) {
		if ($source === DB) {
			$this->id = (int)$id;
		} else if (isset($id)) {
			throw new EntityException(['id' => 'The given id is illegal'], -1);
		}
	}

	/**
	 * @param int $id Represents the id
	 * @throws EntityException Throws an exception if the id is already set
	 */
	public function setId(int $id): void {
		if (isset($this->id)) {
			throw new EntityException(['id' => 'Entity already has an id'], -2);
		}
		$this->id = $id;
	}

	/**
	 * @return int Returns the entity's id
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return array Returns all entity's attributes as an associative array
	 */
	public function getValues() : array {
		$fields = [];
		foreach ($this as $key => $value) {
			$fields[$key] = $value;
		}
		return $fields;
	}

	/**
	 * @return array Returns all entity's attributes names
	 */
	public function getColumns() : array {
		$columns = array();
		foreach ($this as $key => $value) {
			array_push($columns, $key);
		}
		return $columns;
	}
}
