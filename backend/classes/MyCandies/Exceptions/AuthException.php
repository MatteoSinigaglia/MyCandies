<?php


namespace MyCandies\Exceptions;

use Exception;

class AuthException extends Exception {

	public function getSignUpError() : array {
		return array('email' => $this->message);
	}

	public function getSignInError() : array {
		return array('login' => $this->message);
	}
}