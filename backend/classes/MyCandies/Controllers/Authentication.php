<?php


namespace MyCandies\Controllers;


use DB\dbh;
use DB\Exceptions\DBException;
use MyCandies\Entities;
use MyCandies\Entities\User;
use MyCandies\Tables\Table;
use MyCandies\Exceptions\AuthException;

class Authentication {

	private $users;
	private $dbh;

	public function __construct() {
		session_start();

		require_once __DIR__.'/../../DB/dbh.php';
		$this->dbh = new dbh();

		require_once __DIR__.'/../Tables/Table.php';
		$this->users = new Table($this->dbh, 'Customers', 'id', User::class, [Entities\DB]);
	}

	public function isLoggedIn() : bool {
		if (empty($_SESSION['email']))
			return false;

		try {
			$this->dbh->connect();
			$user = $this->users->find(['column'=>'email', 'value'=>$_SESSION['email']]);
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}

		switch (count($user)<=>1) {
			case -1:
				return false;
			case 0:
				return $user[0]->getPassword() === $_SESSION['password'];
//			case 1:
//				throw new AuthException('Unexpected event occurred, please contact the site admins.', -100);
		}
	}

	public function logout() {
		unset($_SESSION);
		session_destroy();
		echo 'Logout';
	}
}