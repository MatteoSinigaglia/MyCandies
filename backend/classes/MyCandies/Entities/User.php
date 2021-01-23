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

				if ($this->isNotValidEmail($data['email']))
					$errors['email'] = 'La email inserita non é corretta';

				if ($this->isNotValidPassword($data['password']))
					$errors['password'] = 'La password inserita non é corretta';

				if ($this->isNotValidConfirmPassword($data['confirmPassword'], $data['password']))
					$errors['confirmPassword'] = 'Le password non corrispondono';

				if ($this->isNotValidFirstName($data['first_name']))
					$errors['first_name'] = 'Nome non corretto';

				if ($this->isNotValidLastName($data['last_name']))
					$errors['last_name'] = 'Cognome non corretto';

				if ($this->isNotValidBirthdate($data['birthdate']))
					$errors['birthdate'] = 'Data di nascita non corretta';

				if ($this->isNotValidTelephone($data['telephone']))
					$errors['telephone'] ='Telefono non corretto';

				$this->first_name = $data['first_name'];
				$this->last_name = $data['last_name'];
				$this->email = $data['email'];
				$this->password = $this->securePassword($data['password']);
		        $this->birthdate = date("Y-m-d", strtotime($data['birthdate']));
				$this->telephone = $data['telephone'];
				$this->gender = (isset($data['gender']) ? substr($data['gender'], 0, 1) : null);
				break;

			case LOGIN:
//			Controls only in email
				if ($this->isNotValidEmail($data['email']))
					$errors['login'] = 'L\'email inserita non è valida';
				$this->email = $data['email'];
				$this->password = $data['password'];
				break;
		}
		if (isset($errors))
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

	private function isNotValidEmail($email) : bool {
		return !isset($email) || strlen($email) < 4 /*|| regex check*/;
	}

	private function isNotValidPassword($password) : bool {
		return !isset($password) || strlen($password) < 4 /*|| regex check*/;
	}

	private function isNotValidConfirmPassword($confirmPassword, $password) : bool {
		return !isset($confirmPassword) || ($confirmPassword !== $password) /*|| regex check*/;
	}

	private function isNotValidFirstName($first_name) : bool {
		return !isset($first_name) || strlen($first_name) < 1 /*|| regex check*/;
	}

	private function isNotValidLastName($last_name) : bool {
		return !isset($last_name) || strlen($last_name) < 1 /*|| regex check*/;
	}

	private function isNotValidBirthdate($birthdate) : bool {
		return !isset($birthdate) || strlen($birthdate) < 10 /*|| regex check*/;
	}

	private function isNotValidTelephone($telephone) : bool {
		return !isset($telephone) || strlen($telephone) < 10 /*|| regex check*/;
	}

	private function isNotValid(string $field, $value) : bool {
		switch($field) {
			case 'first_name':
				return $this->isNotValidFirstName($value);
			case 'last_name':
				return $this->isNotValidLastName($value);
			case 'telephone':
				return $this->isNotValidTelephone($value);
			case 'birthdate':
				return $this->isNotValidBirthdate($value);
		}
	}

	private function getErrorMessage(string $field) : string {
		switch ($field) {
			case 'first_name':
				return 'Nome non valido';
			case 'last_name':
				return 'Cognome non valido';
			case 'email':
				return 'Email non valida';
			case 'password':
				return 'Password non valida';
			case 'confirmPassword':
				return 'Le password non corrispondono';
			case 'telephone':
				return 'Telefono non valido';
			case 'birthdate':
				return 'Data non valida';
		}
	}

	private function decodeGender($gender): string {
		switch ($gender) {
			case 'M':
				return 'Maschio';
			case 'F':
				return 'Femmina';
			case 'A':
				return 'Altro';
		}
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