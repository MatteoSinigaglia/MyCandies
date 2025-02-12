<?php

use MyCandies\Controllers\Administration;
use MyCandies\Controllers\Authentication;

require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';
$auth = new Authentication();

if (!isset($_SERVER['HTTP_REFERER']) || !$auth->isAdmin()) {
	header('location: ./home.php');
	die();
}

require_once __DIR__.'/classes/MyCandies/Controllers/Administration.php';
try {
	$admin = new Administration();
	$admin->makeAdmin($_GET['email']);
} catch (Exception $e) {
	echo $e;
}
header('location: ./utenti_dashboard.php');
die();
