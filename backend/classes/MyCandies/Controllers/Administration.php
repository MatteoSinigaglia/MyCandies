<?php


namespace MyCandies\Controllers;


use DB\dbh;
use MyCandies\Tables\Table;

class Administration {

	private $users;
	private $dbh;

	public function __construct() {

		require_once __DIR__.'/../../DB/dbh.php';
		$this->dbh = new dbh();

		require_once __DIR__.'/../Tables/Table.php';
		$this->users = new Table($this->dbh, '');
	}
}