<?php
use MyCandies\Controllers\Authentication;

require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';

$auth = new Authentication();

if ($auth->isLoggedIn()) {
	header('location: ./home.php');
	die();
}

$DOM = file_get_contents('../frontend/formCliente.html');


if (isset($GLOBALS['errors']['email'])) {
	$DOM = str_replace('', $GLOBALS['errors']['email'], $DOM);
}

echo $DOM;

