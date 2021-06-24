<?php


namespace MyCandies\Entities;


use DB\dbh;
use MyCandies\Exceptions\EntityException;

require_once __DIR__.'/Entity.php';

class UsersAddresses {

	private $customer_id;
	private $address_id;

	public const REGISTER = 1;

	static public function getFromUserId(dbh $dbh, int $userId): array {
		$usersAddressesArrays = $dbh->find('CustomersAddresses', 'customer_id', (string)$userId);

		$usersAddresses = [];
		foreach ($usersAddressesArrays as $usersAddressesElement) {
			array_push($usersAddresses, new UsersAddresses(DB, [
				'customer_id'   =>  $userId,
				'address_id'    =>  $usersAddressesElement['address_id']
			]));
		}
		return $usersAddresses;
	}
	public function __construct(int $source, array $data=[]) {

		switch ($source) {
			case REGISTER:

			case DB:
				if (isset($data['customer_id'])) {
					$this->customer_id = $data['customer_id'];
				}
				if (isset($data['address_id'])) {
					$this->address_id = $data['address_id'];
				}
				break;
		}
	}

	public function setCustomerId($customer_id): void {
		$this->customer_id = $customer_id;
	}

	public function setAddressId($address_id): void {
		$this->address_id = $address_id;
	}

	public function getCustomerId() {
		return $this->customer_id;
	}

	public function getAddressId() {
		return $this->address_id;
	}

	public function getValues() : array {
		$fields = [];
		foreach ($this as $key => $value) {
			$fields[$key] = $value;
		}
		var_dump($fields);
		return $fields;
	}

	public function getColumns() : array {
		$columns = array();
		foreach ($this as $key => $value) {
			array_push($columns, $key);
		}
		return $columns;
	}

}