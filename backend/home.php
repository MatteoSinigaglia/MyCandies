<?php

use MyCandies\Controllers\Authentication;

require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';

$auth = new Authentication();

$DOM = file_get_contents('../frontend/home.html');
//($auth->isLoggedIn()? '<a href="logout.php" id="loginButton" class="buttons">Logout</a>' : '<a href="formCliente.php" id="loginButton" class="buttons">Accedi</a>')
if  ($auth->isLoggedIn()) {
	$DOM = str_replace('<a_auth_state />', '<a href="logout.php" id="loginButton" class="buttons">Logout</a>', $DOM);
	$DOM = str_replace('<admin_fields />', ($auth->isAdmin()
		?
		'<li><a href="./prodotti_dashboard.php" name="dashboard_prodotti">Dashboard prodotti</a></li>'.
		'<li><a href="./dashboard_utenti.php" name="dashboard_utenti">Dashboard utenti</a></li>'
		:
		''), $DOM);
} else
	$DOM = str_replace('<a_auth_state />', '<a href="formCliente.php" id="loginButton" class="buttons">Accedi</a>', $DOM);

echo $DOM;
