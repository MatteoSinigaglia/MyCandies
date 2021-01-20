<?php

use MyCandies\Controllers\Authentication;

require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';

$auth = new Authentication();

$DOM = file_get_contents('../frontend/home.html');

if ($auth->isLoggedIn())
	$DOM = str_replace('<a_auth_state />', '<a href="logout.php" id="loginButton" class="buttons">Logout</a>', $DOM);
else
	$DOM = str_replace('<a_auth_state />', '<a href="formCliente.php" id="loginButton" class="buttons">Accedi</a>', $DOM);

echo $DOM;
