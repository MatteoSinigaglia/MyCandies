<?php

use MyCandies\Controllers\Authentication;
use MyCandies\Exceptions\EntityException;
use MyCandies\Exceptions\AuthException;

if (!isset($_POST['submitLogin'])) {
	header('location: ./formCliente.php');
	die();
}

$errors = array();

try {
	require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';
	$auth = new Authentication();

	if ($auth->isLoggedIn()) {
		header('location: ./home.php');
		die();
	}

	$auth->signIn($_POST['user']);
	header('location: ./home.php');
	die();
} catch (EntityException $e) {
	$errors = $e->getErrors();
} catch (AuthException $e) {
	$errors = $e->getSignInError();
}

$DOM = file_get_contents('../frontend/formCliente.html');

require_once __DIR__.'/lib/functions.php';

$DOM = noSignUpForm($DOM);

$DOM = str_replace('<error_login />', (isset($errors['login']) ? '<strong class="formErrors">'.$errors['login'].'</strong>' : ''), $DOM);

$DOM = str_replace('_login_value', $_POST['user']['email'], $DOM);

echo $DOM;
