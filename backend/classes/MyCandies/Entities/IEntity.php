<?php

namespace MyCandies\Entities;

use DB\dbh;

interface IEntity {

	public function insert(dbh $dbh): int;
}