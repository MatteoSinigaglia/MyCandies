<?php


namespace MyCandies\Controllers;


use DB\dbh;
use DB\Exceptions\DBException;
use MyCandies\Entities;
use MyCandies\Entities\Entity;
use MyCandies\Entities\User;
use MyCandies\Exceptions\LoginException;
use MyCandies\Tables\Table;
use MyCandies\Exceptions\EntityException;

defined('PATH_TO_ENTITY') || define('PATH_TO_ENTITY', __DIR__.'/../Entities/');

require_once __DIR__.'/Authentication.php';
require_once __DIR__.'/../Tables/Table.php';
require_once __DIR__.'/../Entities/User.php';
require_once __DIR__.'/../Entities/sources.php';
require_once __DIR__.'/../Exceptions/EntityException.php';
require_once __DIR__.'/../Exceptions/RegisterException.php';
require_once __DIR__.'/../Exceptions/LoginException.php';
require_once __DIR__.'/../../DB/dbh.php';


class Login extends Authentication {

	private $user;
	private $tUser;
	private $dbh;

	/**
	 * Login constructor.
	 * @param array $user
	 */
	public function __construct(array $user) {
		try {
			parent::__construct();
			$this->user = new User(Entities\LOGIN, $user);
			$this->dbh = new dbh();
			$this->tUser = new Table($this->dbh, 'Customers', 'id', User::class, [Entities\DB]);

		} catch (EntityException $e) {
			throw new LoginException('Error occurred during login'.$e, -1);
		}
	}

	public function login() {
		try {
			$this->dbh->connect();
			$user = $this->tUser->find(
				[   'column' => 'email',
					'value' => $this->user->getEmail()
				]);

		} catch (DBException $e) {
			echo $e;
		} catch (EntityException $e){
//			Utente non registrato
				throw new LoginException('L\'email e/o la password inserite sono errate');
			} finally {
			$this->dbh->disconnect();
		}

		if (count($user) < 1)
			throw new LoginException('User not registered', -1);

		if (count($user) > 1)
			throw new DBException('Unexpected event please try again later', -3);
		else
			$user = $user[0];

		if (!password_verify($this->user->getPassword(), $user->getPassword())) {
			throw new LoginException('L\'email e/o la password inserite sono errate', -2);
		}

		session_regenerate_id();

//		UID: encrypted user id using md5
		$_SESSION['email'] = $user->getEmail();

//		In the database is stored the hash of the password
		$_SESSION['password'] = $user->getPassword();

//		echo $user->getFirstName().' '.$user->getLastName().' logged in';

	}
}