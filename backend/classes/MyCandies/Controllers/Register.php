<?php


namespace MyCandies\Controllers;

use DB\Exceptions\TransactionException;
use Exception;
use InvalidArgumentException;
use DB\dbh;
use MyCandies\Entity\Address;
use MyCandies\Exceptions\EntityException;
use MyCandies\Tables\Table;
use MyCandies\Entity\User;


class Register {

	private $T_users;
	private $T_addresses;
	private $T_usersAddresses;
	private $user;
	private $address;
	private $userAddress;
	private $dbh;

	private const PATH_TO_ENTITY = '.'.DS.'..'.DS.'Entity'.DS;
	public function __construct(array $user, array $address) {
//	                            Table $T_users, Table $T_addresses, Table $T_usersAddresses) {
		try {
			$this->user = new User($user);
			$this->address = new Address($address);

			$this->dbh = new dbh();
			$this->T_users = new Table($this->dbh, 'Customers', 'id', self::PATH_TO_ENTITY.'User');
//				$T_users;
			$this->T_addresses = new Table($this->dbh, 'Addresses', 'id', self::PATH_TO_ENTITY.'Address');
//				$T_addresses;
			$this->T_usersAddresses = new Table($this->dbh, 'CustomerAddresses', 'id', self::PATH_TO_ENTITY.'UserAddresses');
//				$T_usersAddresses;
		} catch (EntityException $e) {
			throw $e;
		}
	}

//	TODO: Move in entity
	private function isValidData() : bool {
//		TODO: static checks on fields with regexp
		if ($this->user['password'] != $this->user['confirmPassword']) {
			return false;
		}

		try {
			$this->dbh->connect();
			if ($this->dbh->find('Customers', 'email', $this->user['email']) > 0) {
				$valid = false;
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

			$this->dbh->connect();
			$this->dbh->transactionStart();
//			array_slice_assoc() is only for testing while forms aren't 100% compatible

			$this->user['customer_id'] = $this->dbh->insert('Customers', array_slice_assoc($this->user, ['first_name', 'last_name', 'email', 'password']));
			$this->user['address_id'] = $this->dbh->insert('Addresses', array_slice_assoc($this->user, ['province', 'city', 'CAP']));
			$this->dbh->insert('CustomersAddresses', array_slice_assoc($this->user, ['id', 'address_id']));
			$this->dbh->transactionCommit();

		} catch (TransactionException $e) {

		} catch (Exception $e) {
			$this->dbh->transactionRollback();
		} finally {
			$this->dbh->disconnect();
		}
	}

	private function securePassword() {
		$this->user['password'] = password_hash($this->user['password'], PASSWORD_DEFAULT);
	}
}