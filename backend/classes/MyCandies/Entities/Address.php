<?php


namespace MyCandies\Entities;


use MyCandies\Exceptions\EntityException;

require_once __DIR__.'/Entity.php';

class Address extends Entity {

	private $country;
	private $region;
	private $province;
	private $city;
	private $CAP;
	private $street;
	private $number;

//	public const REGISTER = 1;

	public function __construct(int $source, array $data) {
		$errors = array();
		try {
//			Ternary operator to remove server's warning
			parent::__construct($source, (isset($data['id']) ? $data['id'] : null));
		} catch (EntityException $e) {
			throw $e;
		}
		switch ($source) {
			case DB:
				break;
			case REGISTER:
//				var_dump($data);
				if (!isset($data['country']) || strlen($data['country']) < 1/* || regex check */) {
					$errors['country'] = 'Errore inserimento nazione';
//					throw new EntityException('', -11);
				}
				if (!isset($data['region']) || strlen($data['region']) < 1/* || regex check */) {
					$errors['region'] = 'Errore inserimento regione';
//					throw new EntityException('', -12);
				}
				if (!isset($data['province']) || strlen($data['province']) < 1/* || regex check */) {
					$errors['province'] = 'Errore inserimento provincia';
//					throw new EntityException('', -13);
				}
				if (!isset($data['city']) || strlen($data['city']) < 1/* || regex check */) {
					$errors['city'] = 'Errore inserimento comune';
//					throw new EntityException('', -14);
				}
				if (!isset($data['CAP']) || strlen($data['CAP']) < 1/* || regex check */) {
					$errors['CAP'] = 'Errore inserimento CAP';
//					throw new EntityException('', -15);
				}
				if (!isset($data['street']) || strlen($data['street']) < 1/* || regex check */) {
					$errors['street'] = 'Errore inserimento Indirizzo';
//					throw new EntityException('', -16);
				}
				if (!isset($data['number']) || strlen($data['number']) < 1/* || regex check */) {
					$errors['number'] = 'Errore inserimento civico';
//					throw new EntityException('', -17);
				}
				$this->country = $data['country'];
				$this->region = $data['region'];
				$this->province = $data['province'];
				$this->city = $data['city'];
				$this->CAP = $data['CAP'];
				$this->street = $data['street'];
				$this->number = $data['number'];

				break;
		}

		if (count($errors) > 0)
			throw new EntityException($errors, -1, '');
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
		foreach ($this as $key => $value) {
			array_push($columns, $key);
		}
		return $columns;
	}
}