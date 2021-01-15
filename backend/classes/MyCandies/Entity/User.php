<?php


namespace MyCandies\Entity;

use MyCandies\Exceptions\EntityException;

require_once __DIR__ . 'Entity.php';

class User extends Entity {

	private $first_name;
	private $last_name;
	private $email;
	private $password;
	private $telephone;
	private $sex;
	private $date_of_birth;

	public function __construct(array $data) {
		try {
			parent::__construct($data['id']);
		} catch (\Exception $e) {

		}

//		Only values check, no control on consistency between data and db;
		if (isset($data['id'])) {
			if (is_int($data['id'])) {
				$this->id = $data['id'];
			} else {
				throw new EntityException('Invalid id', -1);
			}
		}
//		else new user
		if (!isset($data['first_name']) || false) {
			throw new EntityException('Invalid name');
		}
		$this->first_name = $data['first_name'];
		$this->last_name = $data['last_name'];
	}

	private function securePassword() {
		$this->password = password_hash($this->password, PASSWORD_DEFAULT);
	}
}