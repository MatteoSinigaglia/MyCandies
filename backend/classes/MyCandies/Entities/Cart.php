<?php


namespace MyCandies\Entities;

use MyCandies\Entities;
use MyCandies\Exceptions\EntityException;

class Cart extends Entity {

	private $total;

	public function __construct(int $source, array $data= []) {
		parent::__construct($source, (isset($data['id']) ? $data['id'] : null));

		switch ($source) {
			case DB:
				break;
			case SHOP_MANAGER:
				$this->total = (isset($data['total']) ? (int)$data['total'] : 0);
				break;
		}

	}

	/**
	 * @return int
	 */
	public function getTotal(): float {
		return $this->total;
	}

	public function addToTotal(float $price) {
		$this->total += $price;
	}

	public function removeFromTotal(float $price) {
		$this->total -= $price;
	}
}