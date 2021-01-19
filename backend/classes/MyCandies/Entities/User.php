<?php


namespace MyCandies\Entities;

use MyCandies\Exceptions\EntityException;

require_once __DIR__.'/Entity.php';
require_once __DIR__.'/../Exceptions/EntityException.php';

class User extends Entity {

	private $first_name;
	private $last_name;
	private $email;
	private $password;
	private $telephone;
	private $sex;
	private $birthdate;

//	public const REGISTER = 1;
//	public const LOGIN = 2;

	public function __construct(int $source, array $data=[]) {
		try {
			parent::__construct($source, $data['id']);
	//		Only values check, no control on consistency between data and db;
//			if (isset($data['id'])) {
//				if (is_int($data['id'])) {
//					$this->id = $data['id'];
//				} else {
//					throw new EntityException('Invalid id', -1);
//				}
//			}
			switch ($source) {
				case DB:

					break;
				case REGISTER:

					if (!isset($data['email']) /*|| regex check*/) {
						throw new EntityException('La email inserita non é corretta', -5);
					}
					if (!isset($data['password']) /*|| regex check*/) {
						throw new EntityException('La password inserita non é corretta', -6);
					}

					if (!isset($data['first_name']) /*|| regex check*/) {
						throw new EntityException('Nome non corretto', -3);
					}
					if (!isset($data['last_name']) /*|| regex check*/) {
						throw new EntityException('Cognome non corretto', -4);
					}
					if ($data['password'] !== $data['confirmPassword']) {
						throw new EntityException('Le password non corrispondono', -7);
					}
					$this->first_name = $data['first_name'];
					$this->last_name = $data['last_name'];
					$this->email = $data['email'];
					$this->password = $this->securePassword($data['password']);
//			$this->birthdate = $data['birthdate'];
//			$this->telephone = $data['telephone'];
					break;
				case LOGIN:
					$this->email = $data['email'];
					$this->password = $data['password'];
					break;
			}

//			TODO: remove comment when added in form
//			if (!isset($data['birthdate']) /*|| regex check*/) {
//				throw new EntityException('Formato data non corretto. Inserire (DD-MM-YYYY).', -8);
//			}
//			if ($this->isUnderage($data['birthdate'])) {
//				throw new EntityException('Utente minorenne non consentito', -9);
//			}
//			if (!isset($data['telephone']) /*|| regex check*/) {
//				throw new EntityException('Cellulare non corretto', -10);
//			}



		} catch (EntityException $e) {
			throw $e;
		}
	}

	private function securePassword(string $plainPassword) : string {
		return password_hash($plainPassword, PASSWORD_DEFAULT);
	}

	private function isUnderage(string $birthdate) : bool {

		return false;
	}

	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @return string The user's crypted password
	 */
	public function getPassword(): string {
		return $this->password;
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
		foreach ($this as $column) {
			array_push($columns, $column);
		}
		return $columns;
	}

	/**
	 * @return mixed
	 */
	public function getFirstName() {
		return $this->first_name;
	}
}