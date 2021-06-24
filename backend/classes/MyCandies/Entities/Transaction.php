<?php

namespace MyCandies\Entities;

use DateTime;
use DB\dbh;
use DB\Exceptions\DBException;

class Transaction {

	private $customer_id;
	private $cart_id;
	private $datetime;
	private $address_id;

	static public function getAllTransactions(dbh $dbh): array {
		$transactionsArray = $dbh->findAll('Transactions');
		$transactions = [];
		foreach ($transactionsArray as $transactionData) {
			array_push($transactions, new Transaction($transactionData));
		}
		return $transactions;
	}

	public function __construct(array $data) {
		$this->customer_id = (isset($data['customer_id']) ? $data['customer_id'] : null);
		$this->cart_id = (isset($data['cart_id']) ? $data['cart_id'] : null);
		$this->address_id = (isset($data['address_id']) ? $data['address_id'] : null);
		$this->datetime = (isset($data['datetime']) ? $data['datetime'] : new DateTime());

	}

	protected function toAssociativeArray(): array {
		return [
			'customer_id'   =>  $this->customer_id,
			'cart_id'       =>  $this->cart_id,
			'datetime'      =>  $this->datetime->format('Y-m-d H:i:s'),
			'address_id'    =>  $this->address_id
		];
	}

	public function insert(dbh $dbh) {
		try {
			$dbh->insert('Transactions', $this->toAssociativeArray());
		} catch (DBException $e) {
			throw $e;
		}
	}

	public function getCustomerId(): int {
		return $this->customer_id;
	}

	public function getCartId(): int {
		return $this->cart_id;
	}

	public function getAddressId(): int {
		return $this->address_id;
	}

	public function getDatetime() {
		return $this->datetime;
	}
}