<?php
use MyCandies\Controllers\Authentication;

require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';

$auth = new Authentication();

if ($auth->isLoggedIn()) {
	header('location: ./home.php');
	die();
}

$DOM = file_get_contents('../frontend/formCliente.html');

require_once __DIR__.'/lib/functions.php';

$DOM = noSignUpForm($DOM);
$DOM = noSignInForm($DOM);

echo $DOM;

