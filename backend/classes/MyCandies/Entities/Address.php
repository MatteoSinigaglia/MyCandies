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

	public function __construct(int $source, array $data= null) {
		$errors = array();
		try {
			parent::__construct($source, (isset($data['id']) ? $data['id'] : null));
		} catch (EntityException $e) {
			throw $e;
		}
		switch ($source) {
			case DB:
				break;
			case REGISTER:
				if ($this->isNotValid('province', $data['province']))
					$errors['province'] = $this->getErrorMessage('province');

				if (!isset($data['city']) || strlen($data['city']) < 1)
					$errors['city'] = $this->getErrorMessage('city');

				if (!isset($data['CAP']) || strlen($data['CAP']) < 1)
					$errors['CAP'] = $this->getErrorMessage('CAP');

				if (!isset($data['street']) || strlen($data['street']) < 1)
					$errors['street'] = $this->getErrorMessage('street');

				if (!isset($data['number']) || strlen($data['number']) < 1)
					$errors['number'] = $this->getErrorMessage('number');

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

	private function isNotValid(string $field, $value) : bool {
		switch ($field) {
			case 'country':
				break;
			case 'region':
				break;
			case 'province':
				return (!isset($value) || strlen($value) < 1);
			case 'city':
				return (!isset($value) || strlen($value) < 1);
			case 'CAP':
				return (!isset($value) || strlen($value) != 5);
			case 'street':
				return (!isset($value) || strlen($value) < 1);
			case 'number':
				return (!isset($value) || strlen($value) < 1);
		}
	}

	private function getErrorMessage(string $field) : string {
		switch ($field) {
			case 'country':
				return '';
			case 'region':
				return '';
			case 'province':
				return 'Provincia non corretta';
			case 'city':
				return 'Comune non corretto';
			case 'CAP':
				return 'CAP non corretto';
			case 'street':
				return 'Via non corretta';
			case 'number':
				return 'Civico non corretto';
		}
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

	public function update(array $fields) {
		foreach ($fields as $key => $value) {
			if ($key != 'id' && $this->isNotValid($key, $value))
				$errors[$key] = $this->getErrorMessage($key);
		}

		if (isset($errors))
			throw new EntityException($errors, -1, 'Errore in fase di modifica dei dati dell\'indirizzo');
		else {
			foreach ($fields as $key => $value) {
				$this->$key = $value;
			}
		}
	}
}