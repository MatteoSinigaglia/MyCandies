<?php


namespace MyCandies\Entities;

use DateTime;
use Exception;
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
			$errors = array();
//			Ternary operator to remove server's warning
			parent::__construct($source, (isset($data['id']) ? $data['id'] : null));

//	        Switch input control depending on the source of the data
			switch ($source) {
//				No controls needed, database should be is consistent, or controls in every field to verify consistency
				case DB:

					break;

//				Controls in every field + consistency between password and confirmPassword
				case REGISTER:

					if (!isset($data['email']) /*|| regex check*/) {
//						array_push($GLOBALS['errors'], 'La email inserita non é corretta');
						throw new EntityException('La email inserita non é corretta', -5);
					}
					if (!isset($data['password']) /*|| regex check*/) {
						throw new EntityException('La password inserita non é corretta', -6);
					}
					if ($data['password'] !== $data['confirmPassword']) {
						throw new EntityException('Le password non corrispondono', -7);
					}

					if (!isset($data['first_name']) /*|| regex check*/) {
						throw new EntityException('Nome non corretto', -3);
					}
					if (!isset($data['last_name']) /*|| regex check*/) {
						throw new EntityException('Cognome non corretto', -4);
					}
					if (!isset($data['birthdate'] /*|| regex check*/)) {
						throw new EntityException('Data di nascita non corretta', -4);
					}
					if (!isset($data['telephone'] /*|| regex check*/)) {
						throw new EntityException('Telefono non corretto', -4);
					}

//			TODO: remove comment when added in form
//			        if (!isset($data['birthdate']) /*|| regex check*/) {
//				        throw new EntityException('Formato data non corretto. Inserire (DD-MM-YYYY).', -8);
//			        }
//			        if ($this->isUnderage($data['birthdate'])) {
//				        throw new EntityException('Utente minorenne non consentito', -9);
//			        }
//			        if (!isset($data['telephone']) /*|| regex check*/) {
//				        throw new EntityException('Cellulare non corretto', -10);
//			        }

					$this->first_name = $data['first_name'];
					$this->last_name = $data['last_name'];
					$this->email = $data['email'];
					$this->password = $this->securePassword($data['password']);
			        $this->birthdate = date("Y-m-d", strtotime($data['birthdate']));
					$this->telephone = $data['telephone'];
					break;

//				Controls only in email and password
				case LOGIN:
					$this->email = $data['email'];
					$this->password = $data['password'];
					break;
			}
		} catch (EntityException | Exception $e) {
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