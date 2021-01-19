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

	public function __construct(array $user, array $address) {
		parent::__construct();
		try {
			$this->user = new User(Entities\REGISTER, $user);
			$this->address = new Address(Entities\REGISTER, $address);
			$this->userAddress = new UsersAddresses(Entities\REGISTER);

			$this->dbh = new dbh();
			$constructorArgs = [Entities\DB];
			$this->tUsers = new Table($this->dbh, 'Customers', 'id', User::class, $constructorArgs);
			$this->tAddresses = new Table($this->dbh, 'Addresses', 'id', Address::class, $constructorArgs);
			$this->tUsersAddresses = new Table($this->dbh, 'CustomerAddresses', 'id', UsersAddresses::class, $constructorArgs);
		} catch (EntityException $e) {
			echo $e;
		}
	}

	private function valid() : bool {
//		TODO: checks on db, user's email already used

		try {
			$this->dbh->connect();
			echo 'valid';
			if ($this->tUsers->find(['email', $this->user->getEmail()]) > 0) {
				throw new RegisterException('Email già in uso', -1);
			}
		} catch (Exception $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}
		return true;
	}

	public function registration() {
		try {
//			$this->valid();
			$this->dbh->connect();
			$this->dbh->transactionStart();

//			array_slice_assoc() is only for testing while forms aren't 100% compatible
//			require_once __DIR__.'/../../../lib/functions.php';
//			$params = array_slice_assoc($this->user->getValues(), ['first_name', 'last_name', 'email', 'password']);
//			$this->user->setId($this->tUsers->insert($this->user));
//			$add = array_slice_assoc($this->address->getValues(), ['province', 'city', 'CAP']);
//			$this->address->setId($this->tAddresses->insert($this->address));

			$this->userAddress->setCustomerId($this->tUsers->insert($this->user));
			$this->userAddress->setAddressId($this->tAddresses->insert($this->address));
			$this->tUsersAddresses->insert($this->userAddress);

			$this->dbh->transactionCommit();
		} catch (DBException $e) {
			$this->dbh->transactionRollback();
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}
	}
}