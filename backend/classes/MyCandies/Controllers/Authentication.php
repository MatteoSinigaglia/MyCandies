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

	private $dbh;

	private $users;
	private $admins;
	private $addresses;
	private $usersAddresses;


	public function __construct() {

		self::initSession();
		require_once __DIR__.'/../../DB/dbh.php';
		$this->dbh = new dbh();

		$this->initUsers();
	}

	static public function initSession() {
		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
		}
	}

	private function initUsers() {
		require_once __DIR__.'/../Tables/Table.php';
		$this->users = new Table($this->dbh, 'Customers', 'id', User::class, [Entities\DB]);
	}

	private function initAdmins() {
		require_once __DIR__.'/../Tables/Table.php';
		$this->admins = new Table($this->dbh, 'Admins', 'user_id', Admin::class, [Entities\DB]);
	}

	private function initAddresses() {
		require_once __DIR__.'/../Tables/Table.php';
		$this->addresses = new Table($this->dbh, 'Addresses', 'id', Address::class, [Entities\DB]);
	}

	private function initUsersAddresses() {
		require_once __DIR__.'/../Tables/Table.php';
		$this->usersAddresses = new Table($this->dbh, 'CustomersAddresses', 'id', UsersAddresses::class, [Entities\DB]);
	}

	private function initUser() {

		if (!isset($this->users))
			$this->initUsers();

		try {
			$this->dbh->connect();
			$this->user = $this->users->find(['column' => 'email', 'value' => $_SESSION['email']])[0];
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}
	}

	private function initAddress() {
		if (!isset($this->addresses))
			$this->initAddresses();
		if (!isset($this->usersAddresses))
			$this->initUsersAddresses();
		if (!isset($this->user))
			$this->initUser();

		try {
			$this->dbh->connect();
			$this->user = $this->users->find(['column' => 'email', 'value' => $_SESSION['email']])[0];
			$this->userAddress = $this->usersAddresses->find(['column' => 'customer_id', 'value' => $this->user->getId()])[0];
			$this->address = $this->addresses->findById($this->userAddress->getAddressId());
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}
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

		$this->initAddresses();
		$this->initUsersAddresses();
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

		if (isset($errors) && count($errors) > 0)
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

//      User is authenticated, the database data can now be stored in the class' field
		$this->user = $user;

		try {
			$this->dbh->connect();
			$isAdmin = $this->admins->findById($this->user->getId());
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}

		$_SESSION['email'] = $this->user->getEmail();

//		In the database is stored the hash of the password
		$_SESSION['password'] = $this->user->getPassword();
		$_SESSION['permissions'] = ($isAdmin ? 'admin' : 'user');
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
				$this->user = $user[0];
				return $this->user->getPassword() === $_SESSION['password'];
//			case 1:
//				throw new AuthException('Unexpected event occurred, please contact the site admins.', -1);
		}
	}

	public function isAdmin() : bool {
		return (isset($_SESSION['permissions']) ? $_SESSION['permissions'] == "admin" : false);
	}

	public function logout() {
		unset($_SESSION);
		session_destroy();
	}

	public function getUserData() : ?array {
		if (!isset($this->user))
			$this->initUser();
		return (isset($this->user) ? $this->user->getValues() : null);
	}

	public function getAddressData() : ?array {
		if (!isset($this->address)) {
			if (!isset($this->addresses))
				$this->initAddresses();
			if (!isset($this->usersAddresses))
				$this->initUsersAddresses();
			try {
				$this->dbh->connect();
				$this->user = $this->users->find(['column' => 'email', 'value' => $_SESSION['email']])[0];
				$this->userAddress = $this->usersAddresses->find(['column' => 'customer_id', 'value' => $this->user->getId()])[0];
				$this->address = $this->addresses->findById($this->userAddress->getAddressId());
			} catch (DBException $e) {
				echo $e;
			} finally {
				$this->dbh->disconnect();
			}
		}
		return (isset($this->address) ? $this->address->getValues() : null);
	}

	public function getData() : array {
		return $this->getUserData()+$this->getAddressData();
	}

	private function updateUserData(array $fields) {

		try {
			$this->dbh->connect();
			$this->users->update($fields);
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}
	}

	private function updateAddressData(array $fields) {

		try {
			$this->dbh->connect();
			$this->addresses->update($fields);
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}
	}

	public function updateData(array $newUserData, array $newAddressData) {

		if (!isset($this->user))
			$this->initUser();

		if (!isset($this->address))
			$this->initAddress();

		$modifiedUserData = $this->getModifiedFields($this->getUserData(), $newUserData);
		$modifiedAddressData = $this->getModifiedFields($this->getAddressData(), $newAddressData);

		try {
			$this->user->update($modifiedUserData);
		} catch (EntityException $e) {
			$errors = $e->getErrors();
		}

		try {
			$this->address->update($modifiedAddressData);
		} catch (EntityException $e) {
			$errors = (isset($errors) ? $errors + $e->getErrors(): $e->getErrors());
		}

		if (isset($errors))
//			Input data contains errors, no changes in database has been made
			throw new EntityException($errors, -1);

//		Input data is fine
		if (count($modifiedUserData) > 1)
			$this->updateUserData($modifiedUserData);
		if (count($modifiedAddressData) > 1)
			$this->updateAddressData($modifiedAddressData);

	}

	private function getModifiedFields($oldData, $newData) : ?array {
		foreach ($oldData as $key => $value) {
			if (isset($newData[$key]) && $value != $newData[$key]) {
				$modified[$key] = $newData[$key];
			}
		}
		$modified['id'] = $oldData['id'];
		return $modified;
	}

	private function getAdmins() : ?array {
		if (!isset($this->admins))
			$this->initAdmins();

		try {
			$this->dbh->connect();
			$admins = $this->admins->find();
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}
		return $admins;
	}
}