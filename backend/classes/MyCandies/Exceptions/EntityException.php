<?php


namespace MyCandies\Exceptions;

use Exception;
use Throwable;

/*
 * Codici di errore:
 *  -1  ->  id non intero
 *	-2  ->  id già assegnato
 *	-3  ->  first_name non corretto (nullo o non rispetta regex)
 *	-4  ->  last_name non corretto (nullo o non rispetta regex)
 *	-5  ->  email non corretta (nullo o non rispetta regex)
 *	-6  ->  password non corretta (nullo o non rispetta regex)
 *	-7  ->  password e confirmPassword non corrispondono
 *	-8  ->  data non corretta (nullo o non rispetta regex)
 *	-9  ->  utente minorenne
 *	-10 ->  cellulare non corretto (nullo o non rispetta regex)
 */

/**
 * Class EntityException
 * @package MyCandies\Exceptions
 */
class EntityException extends Exception {

	private $errors;

	public function __construct(array $errors, int $code = 0, string $message = "", Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
		$this->errors = $errors;
	}

	/**
	 * @return array
	 */
	public function getErrors(): array {
		return $this->errors;
	}

}