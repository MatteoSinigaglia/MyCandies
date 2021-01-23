<?php


namespace MyCandies\Controllers;

use DB\Exceptions\DBException;
use Exception;
use DB\dbh;
use MyCandies\Tables\Table;
use MyCandies\Entities;
use MyCandies\Entities\User;
use MyCandies\Entities\Address;
use MyCandies\Entities\UsersAddresses;
use MyCandies\Exceptions\EntityException;
use MyCandies\Exceptions\RegisterException;

require_once __DIR__.'/Authentication.php';
require_once __DIR__.'/../Entities/sources.php';
require_once __DIR__.'/../Entities/User.php';
require_once __DIR__.'/../Entities/Address.php';
require_once __DIR__.'/../Entities/UsersAddresses.php';
require_once __DIR__.'/../Exceptions/EntityException.php';
require_once __DIR__.'/../Exceptions/RegisterException.php';
require_once __DIR__.'/../../DB/dbh.php';
require_once __DIR__.'/../../DB/Exceptions/DBException.php';
require_once __DIR__.'/../Tables/Table.php';

defined('PATH_TO_ENTITY') || define('PATH_TO_ENTITY', __DIR__.'/../Entities/');

class Register extends Authentication {

	private $tUsers;
	private $tAddresses;
	private $tUsersAddresses;
	private $user;
	private $address;
	private $userAddress;
	private $dbh;

	/**
	 * Register constructor.
	 * @param array $user
	 * @param array $address
	 */
	public function __construct(array $user, array $address) {
		parent::__construct();
		$errors = array();
		try {
			$this->user = new User(Entities\REGISTER, $user);
		} catch (EntityException $e) {
			$errors = $e->getErrors();
		}

		try {
			$this->address = new Address(Entities\REGISTER, $address);
		} catch (EntityException $e) {
			$errors = array_merge($errors, $e->getErrors());
		}

		if (count($errors) > 0)
			throw new EntityException($errors, -1, 'Errori');
		$this->userAddress = new UsersAddresses(Entities\REGISTER);

		$this->dbh = new dbh();
		$constructorArgs = [Entities\DB];
		$this->tUsers = new Table($this->dbh, 'Customers', 'id', User::class, $constructorArgs);
		$this->tAddresses = new Table($this->dbh, 'Addresses', 'id', Address::class, $constructorArgs);
		$this->tUsersAddresses = new Table($this->dbh, 'CustomersAddresses', 'id', UsersAddresses::class, $constructorArgs);

	}

	/**
	 * @return bool
	 */
	private function valid() : bool {

		$email = [
			'column'    =>  'email',
			'value'     =>  $this->user->getEmail()
		];
		try {
			$this->dbh->connect();
			if (count($this->tUsers->find($email)) > 0) {
				throw new RegisterException('Email già in uso', -1);
			}
		} catch (Exception $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}
		return true;
	}

	/**
	 * @throws RegisterException
	 */
	public function registration() {
		try {
			$this->valid();
			$this->dbh->connect();
			$this->dbh->transactionStart();

			$this->userAddress->setCustomerId($this->tUsers->insert($this->user));
			$this->userAddress->setAddressId($this->tAddresses->insert($this->address));
			$this->tUsersAddresses->insert($this->userAddress);

			$this->dbh->transactionCommit();
		} catch (DBException $e) {
			$this->dbh->transactionRollback();
			throw new RegisterException('Unable to register user: '.$e, -1);
		} finally {
			$this->dbh->disconnect();
		}

		session_regenerate_id();

		$_SESSION['email'] = $this->user->getEmail();
		$_SESSION['password'] = $this->user->getPassword();
	}
}