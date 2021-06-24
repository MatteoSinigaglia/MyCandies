<?php


namespace MyCandies\Entities;


use DB\dbh;

class Admin {

	private $user_id;

	public function __construct(int $id=null) {
		$this->user_id = $id;
	}

	static public function selectAll(dbh $dbh) {
	    return $dbh->findAll('Admins');
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

    public function getUserId(): ?int {
        return $this->user_id;
    }
}