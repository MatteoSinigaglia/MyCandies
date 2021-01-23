<?php


namespace MyCandies\Controllers;


use DB\dbh;
use DB\Exceptions\DBException;
use MyCandies\Entities;
use MyCandies\Entities\Admin;
use MyCandies\Entities\User;
use MyCandies\Tables\Table;

class Administration {

	private $users;
	private $dbh;

	public function __construct() {

		require_once __DIR__.'/../../DB/dbh.php';
		$this->dbh = new dbh();

		require_once __DIR__.'/../Tables/Table.php';
		$this->users = new Table($this->dbh, 'Customers', 'id', User::class, [Entities\DB]);
	}

	public function getUsers() : array {
		try {
			$this->dbh->connect();
			$users = $this->users->find();
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}
		foreach ($users as $key => $user)
			if ($user->getEmail() == $_SESSION['email']) {
				unset($users[$key]);
				break;
			}
		return $users;
	}

	public function deleteUser(string $userEmail) {
		try {
			$this->dbh->connect();
			$this->users->deleteWhere('email', $userEmail);
		} catch (DBException $e) {
			throw $e;
		} finally {
			$this->dbh->disconnect();
		}
	}

	public function makeAdmin(string $userEmail) {
		require_once __DIR__.'/../Entities/Admin.php';
		$admins = new Table($this->dbh, 'Admins', 'user_id', Admin::class);
		try {
			$this->dbh->connect();
			$newAdmin = $this->users->find(['column' => 'email', 'value' => $userEmail]);
			$admins->insert(new Admin($newAdmin[0]->getId()));
		} catch (DBException $e) {

		} finally {
			$this->dbh->disconnect();
		}
	}
}