<?php

namespace MyCandies\Entities;

use DB\dbh;

interface IEntity {

	public function insert(dbh $dbh): int;
//	public function update(dbh $dbh): void;
//	public function delete(dbh $dbh): void;
}