<?php


namespace MyCandies\Controllers;


class Authentication {
	public function __construct() {
		session_start();
	}

	public function isLogged() : bool {
		return false;
	}
}