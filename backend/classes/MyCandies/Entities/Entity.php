<?php


namespace MyCandies\Entities;

use MyCandies\Exceptions\EntityException;

require_once __DIR__.'/../Exceptions/EntityException.php';
require_once __DIR__ . '/sources.php';

class Entity {

	protected $id;

	public function __construct(int $source, $id=null) {
		if ($source === DB) {
			$this->id = (int)$id;
		} else if (isset($id)) {
			throw new EntityException(['id' => 'The given id is illegal'], -1);
		}
	}

	public function setId(int $id): void {
		if (isset($this->id) && $this->id !== 0) {
			throw new EntityException(['id' => 'Entity already has an id'], -2);
		}
		$this->id = $id;
	}

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
		foreach ($this as $key => $value) {
			array_push($columns, $key);
		}
		return $columns;
	}
}
