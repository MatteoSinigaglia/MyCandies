<?php

namespace MyCandies\Entities;

use DB\dbh;
use DB\Exceptions\DBException;
use MyCandies\Entities;
use MyCandies\Exceptions\EntityException;

class Cart {

	private $total;

	static public function getFromId(dbh $dbh, int $id): ?Cart {
		try {
			return $dbh->findById('Carts', 'id', $id, Cart::class, [[DB => true]]);
		} catch (DBException $e) {
			return null;
		}
	}

	public function __construct(array $data= []) {
		if (!isset($data[DB])) {
			$this->id       =   (isset($data['id']) ? (int)$data['id'] : null);
			$this->total    =   (isset($data['total']) ? (int)$data['total'] : 0);
		}
	}

	public function getTotal(): float {
		return $this->total;
	}

	public function getId(): ?int {
		return $this->id;
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

	public function insert(dbh $dbh): int {
		try {
			return $dbh->insert('Carts', $this->toAssociativeArray());
		} catch (DBException $e) {
			throw $e;
		}
	}

	protected function toAssociativeArray(): array {
		return [
			'id'    =>  $this->id,
			'total' =>  $this->total
		];
	}
}