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
	private $gender;
	private $birthdate;

	public function __construct(int $source, array $data=[]) {
		try {
//			Ternary operator to remove server's warning
			parent::__construct($source, (isset($data['id']) ? $data['id'] : null));

		} catch (EntityException $e) {
			throw $e;
		}
//	        Switch set of input controls depending on the source of the data
		switch ($source) {

			case DB:
//			No controls needed, database is consistent
				break;

			case REGISTER:
//			Controls in every field + consistency between password and confirmPassword

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
//			Controls only in email
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
				return (!isset($value) || strlen($value) < 1 /*|| regex check*/);
				break;
			case 'password':
				return (!isset($value) || strlen($value) < 4 /*|| regex check*/);
				break;
			case 'confirmPassword':
				return (!isset($value) || ($value !== $optional) /*|| regex check*/);
				break;
			case 'first_name':
				return (!isset($value) || strlen($value) < 2 /*|| regex check*/);
				break;
			case 'last_name':
				return (!isset($value) || strlen($value) < 2 /*|| regex check*/);
				break;
			case 'telephone':
				return (!isset($value) || strlen($value) < 10 /*|| regex check*/);
				break;
			case 'birthdate':
				return (!isset($value) || strlen($value) < 10 /*|| regex check*/);
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
		$fields['birthdate'] = date('d-m-Y', strtotime($this->birthdate));
		$fields['gender'] = $this->decodeGender($this->gender);

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