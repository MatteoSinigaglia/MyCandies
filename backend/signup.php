<?php

use MyCandies\Controllers\Register;

require_once __DIR__ . DS;


//  TODO: Fix paths
if (!isset($_POST['submitSubscribe'])) {
	header('location: '.VIEW_PATH.'formCliente.html');
	die();
}

$signup = new Register($_POST['user'], $_POST['address']);

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