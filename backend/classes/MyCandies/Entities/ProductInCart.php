<?php


namespace MyCandies\Entities;

require_once __DIR__.DIRECTORY_SEPARATOR.'AbstractEntity.php';

use DB\dbh;
use DB\Exceptions\DBException;

class ProductInCart {

	private $cart_id;
	private $product_id;
	private $quantity;

	public function __construct(int $source, array $data= []) {
		switch ($source) {
			case DB:
			case SHOP_MANAGER:
				if (isset($data['cart_id'])) {
					$this->cart_id = $data['cart_id'];
				}
				if (isset($data['product_id'])) {
					$this->product_id = $data['product_id'];
				}
				if (isset($data['quantity'])) {
					$this->quantity = $data['quantity'];
				}
				break;
		}
	}

	public function insert(dbh $dbh) {
		try {
			$dbh->insert('ProductsInCarts', $this->toAssociativeArray());
		} catch (DBException $e) {
			throw $e;
		}
	}

	protected function toAssociativeArray(): array {
		return [
			'cart_id'       =>  $this->cart_id,
			'product_id'    =>  $this->product_id,
			'quantity'      =>  $this->quantity
		];
	}
}