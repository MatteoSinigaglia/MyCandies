<?php


namespace MyCandies\Controllers;


use DB\dbh;
use DB\Exceptions\DBException;
use MyCandies\Entities\Entity;
use MyCandies\Entities\User;
use MyCandies\Tables\Table;
use MyCandies\Exceptions\EntityException;

defined('PATH_TO_ENTITY') || define('PATH_TO_ENTITY', __DIR__.'/../Entities/');

require_once __DIR__.'/Authentication.php';
require_once PATH_TO_ENTITY.'/User.php';
require_once __DIR__.'/../Exceptions/EntityException.php';
require_once __DIR__.'/../Exceptions/RegisterException.php';
require_once __DIR__.'/../../DB/dbh.php';
require_once __DIR__.'/../Tables/Table.php';


class Login extends Authentication {

	private $user;
	private $tUser;
	private $dbh;

	public function __construct(array $user) {
		try {
			parent::__construct();
			$this->user = new User(Entity::CONTROLLER, $user);
			$this->dbh = new dbh();
			$this->tUser = new Table($this->dbh, 'Customers', 'id', 'MyCandies\Entities\User');
		} catch (EntityException $e) {
			echo $e;
		}
	}

	public function login() {
		try {
			$this->dbh->connect();
			$dbData = $this->tUser->find(
				[   'column' => 'email',
					'value' => $this->user->getEmail()
				]);

			foreach ($dbData as $user) {
//				echo $user['id'].'    ';
				var_dump($user);
			}
		} catch (DBException $e) {

		} finally {
			$this->dbh->disconnect();
		}
	}
}