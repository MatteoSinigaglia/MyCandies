<?php


require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';
require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';

use MyCandies\Controllers\Authentication;

$DOM = file_get_contents('../frontend/FAQ.html');

$auth = new Authentication();

if  ($auth->isLoggedIn()) {
	$DOM = str_replace('<a_auth_state />', '<a href="logout.php" id="loginButton" class="fa fa-sign-out buttons"><span> Logout</span></a>', $DOM);
} else {
	$DOM = str_replace('<a_auth_state />', '<a href="./formCliente.php" id="loginButton" class="fa fa-sign-in buttons"><span> Accedi</span></a>', $DOM);
}

echo $DOM;