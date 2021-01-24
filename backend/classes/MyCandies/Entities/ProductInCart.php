<?php


namespace MyCandies\Entities;

use MyCandies\Entities;

class ProductInCart {

	private $cart_id;
	private $product_id;
	private $quantity;

	public function __construct(int $source, array $data= []) {
		switch ($source) {
			case Entities\DB:
				break;
		}
	}

}