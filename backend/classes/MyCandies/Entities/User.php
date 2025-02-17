<?php


namespace MyCandies\Entities;

use DB\dbh;
use DB\Exceptions\DBException;
use MyCandies\Exceptions\EntityException;

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

	static public function getFromId(dbh $dbh, int $id): ?User {
		try {
			return $dbh->findById('Customers', 'id', $id, User::class, [DB]);
		} catch (DBException $e) {
			return null;
		}
	}

	public function __construct(int $source, array $data=[]) {
		try {
			parent::__construct($source, (isset($data['id']) ? $data['id'] : null));
		} catch (EntityException $e) {
			throw $e;
		}
		switch ($source) {
			case DB:
				break;
			case REGISTER:
				if ($this->isNotValid('email', $data['email']))
					$errors['email'] = $this->getErrorMessage('email');

				if ($this->isNotValid('password', $data['password']))
					$errors['password'] = $this->getErrorMessage('password');

				if ($this->isNotValid('confirmPassword', $data['confirmPassword'], $data['password']))
					$errors['confirmPassword'] = $this->getErrorMessage('confirmPassword');

				if ($this->isNotValid('first_name', $data['first_name']))
					$errors['first_name'] = $this->getErrorMessage('first_name');

				if ($this->isNotValid('last_name', $data['last_name']))
					$errors['last_name'] = $this->getErrorMessage('last_name');

				if ($this->isNotValid('birthdate', $data['birthdate']))
					$errors['birthdate'] = $this->getErrorMessage('birthdate');

				if ($this->isNotValid('telephone', $data['telephone']))
					$errors['telephone'] = $this->getErrorMessage('telephone');

				$this->first_name = $data['first_name'];
				$this->last_name = $data['last_name'];
				$this->email = $data['email'];
				$this->password = $this->securePassword($data['password']);
		        $this->birthdate = date("Y-m-d", strtotime($data['birthdate']));
				$this->telephone = $data['telephone'];
				$this->gender = $this->encodeGender($data['gender']);
				break;
			case LOGIN:
				if ($this->isNotValid('email', $data['email']))
					$errors['login'] = 'L\'email inserita non è valida';
				$this->email = $data['email'];
				$this->password = $data['password'];
				break;
		}
		if (isset($errors))
			throw new EntityException($errors, -1);
	}

	private function isNotValid(string $field, $value, $optional = null) : bool {
		switch($field) {
			case 'email':
				return (!isset($value) || strlen($value) < 1 || !preg_match('/^([a-z0-9]+[_\.-]?)+@([\da-z\.-]+)\.([a-z\.]{2,6})$/', $value));
				break;
			case 'password':
				return (!isset($value) || strlen($value) < 4 || !preg_match('/.{4,20}/', $value));
				break;
			case 'confirmPassword':
				return (!isset($value) || ($value !== $optional) || !preg_match('/.{4,20}/', $value));
				break;
			case 'first_name': case 'last_name' :
				return (!isset($value) || strlen($value) < 2 || !preg_match('/^[A-Z][a-z]{2,20}(\s[A-Z][a-z]{2,20})?$/', $value));
				break;
			case 'telephone':
				return (!isset($value) || strlen($value) < 10 || !preg_match('/^\d{10}$/', $value));
				break;
			case 'birthdate':
				return (!isset($value) || strlen($value) < 10 || !preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value));
				break;
			default:
				return false;
				break;
		}
	}

	private function getErrorMessage(string $field) : string {
		switch ($field) {
			case 'first_name':
				return 'Nome non corretto';
				break;
			case 'last_name':
				return 'Cognome non corretto';
				break;
			case 'email':
				return 'La email inserita non é corretta';
				break;
			case 'password':
				return 'La password inserita non é corretta';
				break;
			case 'confirmPassword':
				return 'Le password non corrispondono';
				break;
			case 'telephone':
				return 'Telefono non corretto';
				break;
			case 'birthdate':
				return 'Data di nascita non corretta';
				break;
			default:
				return '';
				break;
		}
	}

	private function encodeGender(string $gender) : ?string {
		return (isset($gender) ? substr($gender, 0, 1) : null);
	}

	private function decodeGender(string $gender) : string {
		switch ($gender) {
			case 'M':
				return 'Maschio';
				break;
			case 'F':
				return 'Femmina';
				break;
			case 'A':
				return 'Altro';
				break;
			default:
				return '';
				break;
		}
	}

	private function securePassword(string $plainPassword) : string {
		return password_hash($plainPassword, PASSWORD_DEFAULT);
	}

	private function isUnderage(string $birthdate) : bool {

		return false;
	}

	public function getEmail() : string {
		return $this->email;
	}

	public function getPassword(): string {
		return $this->password;
	}

	public function getGender() {
		return $this->gender;
	}

	public function getValues(int $source = null) : array {
		$fields = [];
		foreach ($this as $key => $value) {
			$fields[$key] = $value;
		}
		if ($source !== DB) {
			$fields['birthdate'] = date('d/m/Y', strtotime($this->birthdate));
			$fields['gender'] = $this->decodeGender($this->gender);
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

	public function getFirstName() {
		return $this->first_name;
	}

	public function getLastName() {
		return $this->last_name;
	}

	public function update(array $fields) {
		foreach ($fields as $key => $value) {
			if ($key != 'id' && $this->isNotValid($key, $value))
				$errors[$key] = $this->getErrorMessage($key);
		}

		if (isset($errors))
			throw new EntityException($errors, -1, 'Errore in fase di modifica dei dati dell\'utente');
		else {
			foreach ($fields as $key => $value) {
				$this->$key = $value;
			}
		}
	}

}