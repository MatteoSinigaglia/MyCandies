<?php


namespace MyCandies\Entities;

use MyCandies\Table;

class Customer {

	private $id;
	private $first_name;
	private $last_name;
	private $email;
	private $telephone;
	private $password;
	private $sex;
	private $date_of_birth;

	private $TAddresses;
	private $TCustomerAddresses;

	public function __construct(Table $addresses, Table $customersAddresses) {
		$this->TAddresses = $addresses;
		$this->TCustomerAddresses = $customersAddresses;
	}

	public function getAddresses() {
		$addresses = $this->TCustomerAddresses->find(['where' => 'customer_id', 'value' => $this->id]);

		foreach ($addresses as $address) {
			$addresses = $this->TCustomerAddresses;
		}
	}
}