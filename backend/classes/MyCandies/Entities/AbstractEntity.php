<?php

namespace MyCandies\Entities;

use DB\dbh;

abstract class AbstractEntity {

	protected $id;

	abstract protected function toAssociativeArray(): array;

	abstract public function insert(dbh $dbh): int;
//	abstract public function update(dbh $dbh): void;
//	abstract public function delete(dbh $dbh): void;
}