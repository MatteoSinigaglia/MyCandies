<?php


namespace MyCandies\Controllers;

use DB\Exceptions\DBException;
use Exception;
use DB\dbh;
use MyCandies\Tables\Table;
use MyCandies\Entities\User;
use MyCandies\Entities\Address;
use MyCandies\Entities\UsersAddresses;
use MyCandies\Exceptions\EntityException;
use MyCandies\Exceptions\RegisterException;

require_once __DIR__.'/Authentication.php';
require_once __DIR__.'/../Entities/User.php';
require_once __DIR__.'/../Entities/Address.php';
require_once __DIR__.'/../Entities/UsersAddresses.php';
require_once __DIR__.'/../Exceptions/EntityException.php';
require_once __DIR__.'/../Exceptions/RegisterException.php';
require_once __DIR__.'/../../DB/dbh.php';
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
			$this->user = new User($user);
			echo 'User';
			$this->address = new Address($address);
			echo 'Address';
			$this->userAddress = new UsersAddresses();
			echo 'UserAddress';

			$this->dbh = new dbh();
			echo 'dbh';
			$this->tUsers = new Table($this->dbh, 'Customers', 'id', PATH_TO_ENTITY.'User');
			echo 'tUser';
//				$T_users;
			$this->tAddresses = new Table($this->dbh, 'Addresses', 'id', PATH_TO_ENTITY.'Address');
			echo 'tAddress';
//				$T_addresses;
			$this->tUsersAddresses = new Table($this->dbh, 'CustomerAddresses', 'id', PATH_TO_ENTITY.'UserAddresses');
			echo 'tUserAddress';
//				$T_usersAddresses;
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
			throw $e;
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
			echo 'startTransaction';

//			array_slice_assoc() is only for testing while forms aren't 100% compatible
			require_once __DIR__.'/../../../lib/functions.php';
			$params = array_slice_assoc($this->user->getValues(), ['first_name', 'last_name', 'email', 'password']);
			foreach ($params as $k => $v) {
				echo $k.' => '.$v.' ';
			}
			$this->user->setId($this->tUsers->insert($params));
			echo 'userId';
			$this->address->setId($this->tAddresses->insert(array_slice_assoc($this->address->getValues(), ['province', 'city', 'CAP'])));
			echo 'addressId';
//			$this->userAddress->setCustomerId($this->user->getId());
//			$this->userAddress->setAddressId($this->address->getId());
//			$this->tUsersAddresses->insert($this->userAddress->getValues());
			$this->dbh->transactionCommit();
			echo 'commit';
		} catch (Exception $e) {
			$this->dbh->transactionRollback();
			throw $e;
		} finally {
			$this->dbh->disconnect();
		}
//	} catch (RegisterException $e) {
//throw $e;
//} catch (DBException $e) {
		}
}