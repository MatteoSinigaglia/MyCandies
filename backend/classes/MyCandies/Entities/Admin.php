<?php


namespace MyCandies\Entities;


use DB\dbh;

class Admin {

	private $user_id;

	public function __construct(int $id=null) {
		$this->user_id = $id;
	}

    /**
     * @param dbh $dbh
     * @return Admin[]
     * @throws \DB\Exceptions\DBException
     */
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

    /**
     * @return int|null
     */
    public function getUserId(): ?int {
        return $this->user_id;
    }
}