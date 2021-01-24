<?php


namespace MyCandies\Entities;

use MyCandies\Entities;
use MyCandies\Exceptions\EntityException;

class Cart extends Entity {

	private $total;

	public function __construct(int $source, array $data= []) {
		parent::__construct($source, (isset($data['id']) ? $data['id'] : null));

		switch ($source) {
			case Entities\DB:
				break;
		}

	}
}