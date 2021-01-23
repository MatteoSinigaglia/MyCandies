<?php

use MyCandies\Controllers\Authentication;


require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';
$auth = new Authentication();

if (!$auth->isLoggedIn() || $auth->isAdmin()) {
	header('location: ./home.php');
	die();
}

$DOM = file_get_contents('../frontend/areaPersonale.html');

require_once __DIR__.'/lib/functions.php';
$DOM = noModifyForm($DOM);

echo $DOM;
