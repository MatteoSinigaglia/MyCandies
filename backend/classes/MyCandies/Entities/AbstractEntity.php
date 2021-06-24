<?php

namespace MyCandies\Entities;

use DB\dbh;

abstract class AbstractEntity {

	protected $id;

	abstract protected function toAssociativeArray(): array;

	abstract public function insert(dbh $dbh): int;
}