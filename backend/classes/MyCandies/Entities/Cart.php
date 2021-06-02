<?php


namespace MyCandies\Entities;

use MyCandies\Exceptions\EntityException;

class Cart extends Entity {

	private $total;

	public function __construct(int $source, array $data= []) {
		parent::__construct($source, ($data['id'] ?? null));

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

	public function addPriceToTotal(float $price) {
		$this->total = round($this->total+$price, 2);
	}

	public function removePriceFromTotal(float $price) {
		$this->total = round($this->total - $price, 2);
	}

	public function getValues(int $source = null) : array {
		$fields = [];
		foreach ($this as $key => $value) {
			$fields[$key] = $value;
		}

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