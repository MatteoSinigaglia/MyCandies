<?php


namespace MyCandies\Entities;


use MyCandies\Exceptions\EntityException;

require_once __DIR__.'/Entity.php';

class UsersAddresses {

	private $customer_id;
	private $address_id;

	public const REGISTER = 1;

	public function __construct(int $source, array $data=[]) {
		try{
//			parent::__construct($source, $data['id']);

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

		} catch (EntityException $e) {
			throw $e;
		}
	}

	/**
	 * @param mixed $customer_id
	 */
	public function setCustomerId($customer_id): void {
		$this->customer_id = $customer_id;
	}

	/**
	 * @param mixed $address_id
	 */
	public function setAddressId($address_id): void {
		$this->address_id = $address_id;
	}

	public function getValues() : array {
		$fields = [];
		foreach ($this as $key => $value) {
			$fields[$key] = $value;
		}
		return $fields;
	}

	public function getColumns() : array {
		$columns = array();
		foreach ($this as $column) {
			array_push($columns, $column);
		}
		return $columns;
	}
}