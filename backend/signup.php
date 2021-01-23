<?php

require_once __DIR__.'/config/config.php';

use MyCandies\Controllers\Register;
use MyCandies\Exceptions\EntityException;

//  TODO: Fix paths
if (!isset($_POST['submitSubscribe'])) {
	header('location: ./formCliente.php');
	die();
}

try {
	require_once __DIR__.'/classes/MyCandies/Controllers/Register.php';
	$signup = new Register($_POST['user'], $_POST['address']);
	$signup->registration();
	header('location: ./home.php');
	die();
} catch (EntityException $e) {
//	echo 'Exception creating object'.$e->getMessage();
//	var_dump($e->getErrors());
	$errors = $e->getErrors();
//	var_dump($errors);
} catch (InvalidArgumentException $e) {
//	TODO: already used email, handle properly
	header('location: ./formCliente.php');
	die();
} catch (Exception $e) {
	echo 'Exception'.$e;
}

$DOM = file_get_contents('../frontend/formCliente.html');

require_once __DIR__.'/lib/functions.php';

$DOM = noSignInForm($DOM);

foreach (array_merge($_POST['user'],$_POST['address']) as $item => $value)
	$DOM = str_replace('<error_'.$item.' />', (isset($errors[$item]) ? "<strong class=\"formErrors\">$errors[$item]</strong>" : ''), $DOM);

//foreach ($_POST['address'] as $item => $value)
//	$DOM = str_replace('<error_'.$item.'/>', (isset($errors[$item]) ? '<ul class=\"failure\">'.$errors[$item].'</ul>' : ''), $DOM);
//
//$DOM = str_replace('<error_signup_email>', (isset($errors['email']) ? '<ul class=\"failure\">'.$errors['email'].'</ul>' : ''), $DOM);
//
//$DOM = str_replace('<error_signup_password>', (isset($errors['email']) ? '<ul class=\"failure\">'.$errors['password'].'</ul>' : ''), $DOM);
//$DOM = str_replace('<error_signup_email>', (isset($errors['email']) ? '<ul class=\"failure\">'.$errors['confirmPassword'].'</ul>' : ''), $DOM);

$fields = array_merge($_POST['user'], $_POST['address']);

$fields['password'] = $fields['confirmPassword'] = '';

foreach ($fields as $item => $value) {
	$DOM = str_replace('_'.$item.'_value', $value, $DOM);
}

//foreach ($_POST['address'] as $item => $value) {
//	$DOM = str_replace('_'.$item.'_value', $value, $DOM);
//}

echo $DOM;