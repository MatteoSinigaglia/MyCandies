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
		try {
			parent::__construct($source, $data['id']);
		} catch (EntityException $e) {
			throw $e;
		}
//
//		if (!isset($data['country']) /* || regex check */) {
//			throw new EntityException('', -11);
//		}
//		if (!isset($data['region']) /* || regex check */) {
//			throw new EntityException('', -12);
//		}
//		if (!isset($data['province']) /* || regex check */) {
//			throw new EntityException('', -13);
//		}
//		if (!isset($data['city']) /* || regex check */) {
//			throw new EntityException('', -14);
//		}
//		if (!isset($data['CAP']) /* || regex check */) {
//			throw new EntityException('', -15);
//		}
//		if (!isset($data['street']) /* || regex check */) {
//			throw new EntityException('', -16);
//		}
//		if (!isset($data['number']) /* || regex check */) {
//			throw new EntityException('', -17);
//		}
		$this->country = $data['country'];
		$this->region = $data['region'];
		$this->province = $data['province'];
		$this->city = $data['city'];
		$this->CAP = $data['CAP'];
		$this->street = $data['street'];
		$this->number = $data['number'];
	}

	public function getValues() : array {
		$fields = [];
		foreach ($this as $key => $value) {
			$fields[$key] = $value;
		}
		return $fields;
	}

}