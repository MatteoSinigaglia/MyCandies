<?php


namespace MyCandies\Entity;


class Address extends Entity {

	private $country;
	private $region;
	private $province;
	private $city;
	private $CAP;
	private $street;
	private $number;

	public function __construct(array $data) {
		parent::__construct($data['id']);

	}

}