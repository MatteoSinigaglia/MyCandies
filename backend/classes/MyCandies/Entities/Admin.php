<?php


namespace MyCandies\Entities;


class Admin {

	private $user_id;

	public function __construct(int $id=null) {
		$this->user_id = $id;
	}

	public function getColumns() : array {
		$columns = array();
		foreach ($this as $key => $value) {
			array_push($columns, $key);
		}
		return $columns;
	}

	public function getValues() : array {
		$fields = [];
		foreach ($this as $key => $value) {
			$fields[$key] = $value;
		}
		return $fields;
	}
}