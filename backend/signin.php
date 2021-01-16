<?php

use MyCandies\Controllers\Login;

if (!isset($_POST['submitLogin'])) {
	header('location: ../frontend/formCliente.html');
	die();
}

try {
	require_once __DIR__.'/classes/MyCandies/Controllers/Login.php';
	$signin = new Login($_POST['user']);
	echo 'Login'.PHP_EOL;
} catch (Exception $e) {
	echo $e;
}

try {
	$signin->login();
	header('location: ../frontend/home.html');
	die();
} catch (Exception $e) {
	echo $e;
}