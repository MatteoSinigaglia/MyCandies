<?php


namespace MyCandies\Exceptions;

use Exception;
use Throwable;

class EntityException extends Exception {

	private $errors;

	public function __construct(array $errors, int $code = 0, string $message = "", Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
		$this->errors = $errors;
	}

	public function getErrors(): array {
		return $this->errors;
	}

}