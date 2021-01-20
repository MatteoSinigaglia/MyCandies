<?php

require_once __DIR__.'/config/config.php';

use MyCandies\Controllers\Register;
use MyCandies\Exceptions\EntityException;

//  TODO: Fix paths
ob_start();
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
	echo 'Exception creating object'.$e->getMessage();
} catch (InvalidArgumentException $e) {
//	TODO: already used email, handle properly
	header('location: ./formCliente.php');
	die();
} catch (Exception $e) {
	echo 'Exception'.$e;
}