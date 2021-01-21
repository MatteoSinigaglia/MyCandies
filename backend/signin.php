<?php

use DB\Exceptions\DBException;
use MyCandies\Controllers\Login;
use MyCandies\Exceptions\LoginException;

if (!isset($_POST['submitLogin'])) {
	header('location: ../frontend/formCliente.html');
	die();
}

try {
	require_once __DIR__.'/classes/MyCandies/Controllers/Login.php';
	$signin = new Login($_POST['user']);
	echo 'Login';
	$signin->login();
	echo 'Logged in';
	header('location: ./home.php');
	die();
} catch (LoginException $e) {
	throw $e;
} catch (DBException $e) {
}