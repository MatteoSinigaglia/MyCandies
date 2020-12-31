<?php
//  TODO: Fix paths
if (!isset($_POST['submitSubscribe'])) {
	header('location: '.VIEW_PATH.'formCliente.html');
	die();
}

$valid = true;
$user = $_POST['user'];

//	TODO: Do static checks on inputs

$user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

require_once './classes/dbh.php';

use DB\dbh;

$dbh = new dbh();

try {
	$dbh->newUser($user);
	header('location: ../frontend/home.html');
	die();
} catch (InvalidArgumentException $e) {
//	TODO: already used email, handle properly
	header('location: ../frontend/formCliente.html');
	die();
} catch (Exception $e) {
	echo 'Exception'.$e;
}