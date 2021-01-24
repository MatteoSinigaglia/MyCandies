<?php

use MyCandies\Controllers\Authentication;
use MyCandies\Exceptions\EntityException;
use MyCandies\Exceptions\AuthException;

if (!isset($_POST['submitSubscribe'])) {
	header('location: ./formCliente.php');
	die();
}

try {

	require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';
	$auth = new Authentication();

//	Preposterous control
	if ($auth->isLoggedIn()) {
		header('location: ./home.php');
		die();
	}

	$auth->signUp($_POST['user'], $_POST['address']);
	header('location: ./home.php');
	die();

} catch (EntityException $e) {

//	Wrong input
	$errors = $e->getErrors();

} catch (AuthException $e) {

//	Wrong (email, password) due to not registered user wroong password or unexpected error
	$errors = $e->getSignUpError();

}

$DOM = file_get_contents('../frontend/formCliente.html');

require_once __DIR__.'/lib/functions.php';

$DOM = noSignInForm($DOM);

$fields = array_merge($_POST['user'], $_POST['address']);

foreach ($fields as $item => $value)
	$DOM = str_replace('<error_'.$item.' />', (isset($errors[$item]) ? '<strong class="formErrors">'.$errors[$item].'</strong>' : ''), $DOM);

$fields['password'] = $fields['confirmPassword'] = '';

foreach ($fields as $item => $value) {
	$DOM = str_replace('_'.$item.'_value', $value, $DOM);
}
var_dump($_POST['user']);

echo $DOM;