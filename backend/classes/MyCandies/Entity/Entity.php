<?php


namespace MyCandies\Entity;


abstract class Entity {

	protected $id;

	public function __construct(int $id) {
		$this->id = $id;
	}

	public function getValues() : array {
		$fields = [];
		foreach ($this as $key => $value) {
			$fields[$key] = $value;
		}
		return $fields;
	}
}