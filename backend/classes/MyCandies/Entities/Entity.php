<?php


namespace MyCandies\Entities;

use MyCandies\Exceptions\EntityException;

require_once __DIR__.'/../Exceptions/EntityException.php';

class Entity {

	public const DB = 0;
	public const CONTROLLER = 1;

	protected $id;

	public function __construct(int $source, mixed $id=null) {
		if ($source === self::CONTROLLER && isset($id) && !is_int($id)) {
			throw new EntityException('The given id is illegal', -1);
		}
		echo 'Id: '.$id.' _';
		$this->id = (int)$id;
	}

	/**
	 * @param int $id
	 * @throws EntityException Throws an exception if the id is already settable
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