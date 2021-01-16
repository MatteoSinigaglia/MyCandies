<?php

require_once __DIR__.'/config/config.php';

use MyCandies\Controllers\Register;
use MyCandies\Exceptions\EntityException;

//  TODO: Fix paths
if (!isset($_POST['submitSubscribe'])) {
	header('location: ../frontend/formCliente.html');
	die();
}
try {
	require_once __DIR__.'/classes/MyCandies/Controllers/Register.php';
	$signup = new Register($_POST['user'], $_POST['address']);
} catch (EntityException $e) {
	echo 'Exception creating object'.$e->getMessage();
}
echo 'Created';
try {
	$signup->registration();
	header('location: ../frontend/home.html');
	die();
} catch (InvalidArgumentException $e) {
//	TODO: already used email, handle properly
	header('location: ../frontend/formCliente.html');
	die();
} catch (Exception $e) {
	echo 'Exception'.$e;
}