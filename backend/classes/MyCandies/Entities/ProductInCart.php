<?php


namespace MyCandies\Entities;

class ProductInCart {

	private $cart_id;
	private $product_id;
	private $quantity;

	public function __construct(int $source, array $data= []) {
		switch ($source) {
			case DB:
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

}