<?php


namespace MyCandies\Entities;


use MyCandies\Exceptions\EntityException;

require_once __DIR__.'/../Exceptions/EntityException.php';

class Entity {

	protected $id;

	public function __construct(int $id=null) {
		echo 'Entity';
		if (isset($id) && !is_int($id)) {
			throw new EntityException('The given id is illegal', -1);
		} else {
			$this->id = $id;
		}
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
}