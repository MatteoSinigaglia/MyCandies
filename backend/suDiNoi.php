<?php

$DOM = file_get_contents('../frontend/suDiNoi.html');

require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';

use MyCandies\Controllers\Authentication;

$auth = new Authentication();

//Header
if  ($auth->isLoggedIn()) {
	$DOM = str_replace('<a_auth_state />', '<a href="logout.php" id="loginButton" class="fa fa-sign-out buttons"><span xml:lang="en"> Logout</span></a>', $DOM);
} else
	$DOM = str_replace('<a_auth_state />', '<a href="./formCliente.php" id="loginButton" class="fa fa-sign-in buttons"><span> Accedi</span></a>', $DOM);

//  Menu setup
$DOM = str_replace('<dashboard />', ($auth->isAdmin()
	?
	'<li><a href="../backend/inserisciProdotto.php">Gestione</a></li>'
	:
	''), $DOM);

echo $DOM;