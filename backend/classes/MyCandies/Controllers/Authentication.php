<?php


namespace MyCandies\Controllers;


use DB\dbh;
use DB\Exceptions\DBException;
use MyCandies\Entities;
use MyCandies\Entities\User;
use MyCandies\Entities\Admin;
use MyCandies\Entities\Address;
use MyCandies\Entities\UsersAddresses;
use MyCandies\Tables\Table;
use MyCandies\Exceptions\EntityException;
use MyCandies\Exceptions\AuthException;

require_once __DIR__.'/../Exceptions/AuthException.php';

class Authentication {

	private $user;
	private $address;
	private $userAddress;

	private $users;
	private $admins;
	private $addresses;
	private $usersAddresses;

	private $dbh;

//	private $tUsers;
//	private $tAddresses;
//	private $tUsersAddresses;

	public function __construct() {
		session_start();

		require_once __DIR__.'/../../DB/dbh.php';
		$this->dbh = new dbh();

		require_once __DIR__.'/../Tables/Table.php';
		$this->users = new Table($this->dbh, 'Customers', 'id', User::class, [Entities\DB]);
	}

	/**
	 * @return bool
	 */
	private function validEmail() {

		$email = [
			'column'    =>  'email',
			'value'     =>  $this->user->getEmail()
		];
		try {
			$this->dbh->connect();
			if (count($this->users->find($email)) > 0)
				$emailError = true;
			else
				$emailError = false;
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}
		if ($emailError)
			throw new AuthException('Email già in uso', -1);
	}

	private function registrationSetup(array $user, array $address) {
		$source = Entities\REGISTER;
		$constructorArgs = [Entities\DB];

		$errors = array();

		try {
			$this->user = new User($source, $user);
		} catch (EntityException $e) {
			$errors = array_merge($errors, $e->getErrors());
		}

		try {
			$this->address = new Address($source, $address);
		} catch (EntityException $e) {
			$errors = array_merge($errors, $e->getErrors());
		}

		if (count($errors) > 0)
			throw new EntityException($errors, -1, 'Errori in fase di setup della registrazione');
		$this->userAddress = new UsersAddresses($source);

		$this->addresses = new Table($this->dbh, 'Addresses', 'id', Address::class, $constructorArgs);
		$this->usersAddresses = new Table($this->dbh, 'CustomersAddresses', 'id', UsersAddresses::class, $constructorArgs);
	}

	private function registration() {

		try {
			$this->validEmail();
			$this->dbh->connect();
			$this->dbh->transactionStart();

			$this->userAddress->setCustomerId($this->users->insert($this->user));
			$this->userAddress->setAddressId($this->addresses->insert($this->address));
			$this->usersAddresses->insert($this->userAddress);

			$this->dbh->transactionCommit();
		} catch (DBException $e) {
			$this->dbh->transactionRollback();
			throw new AuthException('Unable to register user: '.$e, -1);
		} catch (AuthException $e) {
			throw $e;
		} finally {
			$this->dbh->disconnect();
		}

		session_regenerate_id();

		$_SESSION['email'] = $this->user->getEmail();
		$_SESSION['password'] = $this->user->getPassword();
	}

	public function signUp(array $user, array $address) {

		$errors = array();
		try {
			$this->registrationSetup($user, $address);
			$this->registration();
		} catch (EntityException | AuthException $e) {
			throw $e;
		}

	}

	private function loginSetup(array $user) {
		try {
			$this->user = new User(Entities\LOGIN, $user);
		} catch (EntityException $e) {
			$errors = $e->getErrors();
		}

		if (count($errors) > 0)
			throw new EntityException($errors, -1, 'Errori in fase di setup del login');

		$this->admins = new Table($this->dbh, 'Admins', 'user_id', Admin::class, [Entities\DB]);
	}

	private function login() {
		try {
			$this->dbh->connect();
			$user = $this->users->find(
				[   'column' => 'email',
					'value' => $this->user->getEmail()
				]);
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}

		switch (count($user) <=> 1) {
			case -1:
				throw new AuthException('User not registered', -1);
			case 0:
				$user = $user[0];
				break;
			case 1:
				throw new AuthException('Unexpected event please try again later', -1);
		}

		if (!password_verify($this->user->getPassword(), $user->getPassword())) {
			throw new AuthException('L\'email e/o la password inserite sono errate', -2);
		}

		session_regenerate_id();
		try {
			$this->dbh->connect();
			$isAdmin = $this->admins->findById($user->getId());
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}

//		UID: encrypted user id using md5
		$_SESSION['email'] = $user->getEmail();

//		In the database is stored the hash of the password
		$_SESSION['password'] = $user->getPassword();

		$_SESSION['permissions'] = ($isAdmin ? 'user' : 'admin');
//		echo $user->getFirstName().' '.$user->getLastName().' logged in';
	}

	public function signIn(array $user) {
		try {
			$this->loginSetup($user);
			$this->login();
		} catch (EntityException | AuthException $e) {
			throw $e;
		}
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

	public function isAdmin() : bool {
		return $_SESSION['permissions'] == 'admin';
	}

	public function logout() {
		unset($_SESSION);
		session_destroy();
		echo 'Logout';
	}
}