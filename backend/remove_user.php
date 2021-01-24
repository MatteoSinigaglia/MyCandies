<?php

use MyCandies\Controllers\Administration;
use MyCandies\Controllers\Authentication;

require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';
$auth = new Authentication();

if (!isset($_SERVER['HTTP_REFERER']) || !$auth->isAdmin()) {
	header('location: ./home.php');
	die();
}

try {
	if ($_GET['email'] != $_SESSION['email']) {
		require_once __DIR__.'/classes/MyCandies/Controllers/Administration.php';
		$admin = new Administration();
		$admin->deleteUser($_GET['email']);
		header('location: ./dashboard_utenti.php');
		die();
	}
} catch (Exception $e) {
	echo $e;
}