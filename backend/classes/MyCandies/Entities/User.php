<?php


namespace MyCandies\Entities;

use DateTime;
use Exception;
use MyCandies\Exceptions\EntityException;
use ReflectionClass;

require_once __DIR__.'/Entity.php';
require_once __DIR__.'/../Exceptions/EntityException.php';

class User extends Entity {

	private $first_name;
	private $last_name;
	private $email;
	private $password;
	private $telephone;
	private $gender;
	private $birthdate;

//	public const REGISTER = 1;
//	public const LOGIN = 2;

	public function __construct(int $source, array $data=[]) {
		$errors = array();
		try {
//			Ternary operator to remove server's warning
			parent::__construct($source, (isset($data['id']) ? $data['id'] : null));

		} catch (EntityException | Exception $e) {
			throw $e;
		}
//	        Switch input control depending on the source of the data
		switch ($source) {
//				No controls needed, database should be is consistent, or controls in every field to verify consistency
			case DB:

				break;

//				Controls in every field + consistency between password and confirmPassword
			case REGISTER:

				if (!isset($data['email']) || strlen($data['email']) < 1/*|| regex check*/) {
					$errors['email'] = 'La email inserita non é corretta';
//						throw new EntityException('La email inserita non é corretta', -5);
				}
				if (!isset($data['password']) || strlen($data['email']) < 4 /*|| regex check*/) {
					$errors['password'] = 'La password inserita non é corretta';
//						throw new EntityException('La password inserita non é corretta', -6);
				}
				if ($data['password'] !== $data['confirmPassword']) {
					$errors['confirmPassword'] = 'Le password non corrispondono';
//						throw new EntityException('Le password non corrispondono', -7);
				}
				if (!isset($data['first_name']) || strlen($data['first_name']) < 1 /*|| regex check*/) {
					$errors['first_name'] = 'Nome non corretto';
//						throw new EntityException('Nome non corretto', -3);
				}
				if (!isset($data['last_name']) || strlen($data['last_name']) < 1 /*|| regex check*/) {
					$errors['last_name'] = 'Cognome non corretto';
//						throw new EntityException('Cognome non corretto', -4);
				}
				if (!isset($data['birthdate']) || strlen($data['birthdate']) < 10  /*|| regex check*/) {
					$errors['birthdate'] = 'Data di nascita non corretta';
//						throw new EntityException('Data di nascita non corretta', -4);
				}
				if (!isset($data['telephone']) || strlen($data['telephone']) < 1  /*|| regex check*/) {
					$errors['telephone'] ='Telefono non corretto';
//						throw new EntityException('Telefono non corretto', -4);
				}

				$this->first_name = $data['first_name'];
				$this->last_name = $data['last_name'];
				$this->email = $data['email'];
				$this->password = $this->securePassword($data['password']);
		        $this->birthdate = date("Y-m-d", strtotime($data['birthdate']));
				$this->telephone = $data['telephone'];
				$this->gender = (isset($data['gender']) ? substr($data['gender'], 0, 1) : null);
				break;

//				Controls only in email and password
			case LOGIN:

				if (!isset($data['email']) || strlen($data['email']) < 1 /* regex checks*/)
					$errors['login'] = 'L\'email inserita non è valida';
				$this->email = $data['email'];
				$this->password = $data['password'];
				break;
		}
		if (count($errors) > 0)
			throw new EntityException($errors, -1);
	}

	private function securePassword(string $plainPassword) : string {
		return password_hash($plainPassword, PASSWORD_DEFAULT);
	}

	private function isUnderage(string $birthdate) : bool {

		return false;
	}

	/**
	 * @return string
	 */
	public function getEmail() : string {
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
		foreach ($this as $key => $value) {
			array_push($columns, $key);
		}
		return $columns;
	}

	/**
	 * @return mixed
	 */
	public function getFirstName() {
		return $this->first_name;
	}

	/**
	 * @return mixed
	 */
	public function getLastName() {
		return $this->last_name;
	}
}