<?php

use MyCandies\Controllers\Authentication;

require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';

$auth = new Authentication();

$DOM = file_get_contents('../frontend/home.html');

if  ($auth->isLoggedIn()) {
	$DOM = str_replace('<a_auth_state />', '<a href="logout.php" id="loginButton" class="fa fa-sign-out buttons"><span xml:lang="en"> Logout</span></a>', $DOM);

} else
	$DOM = str_replace('<a_auth_state />', '<a href="./formCliente.php" id="loginButton" class="fa fa-sign-in buttons"><span> Accedi</span></a>', $DOM);

$DOM = str_replace('<navbar />', ($auth->isAdmin()
	?
	'<li><a href="../backend/listaProdotti.php">Droghe</a></li>'.
	'<li><a href="../backend/areaPersonale.php">Area personale</a></li>'.
	'<li><a href="../backend/FAQ.php"><abbr title="Frequently Asked Questions" xml:lang="en">FAQ</abbr></a></li>'.
	'<li><a href="../backend/inserisciProdotto.php">Gestione</a></li>'
	:
	'<li><a href="../backend/listaProdotti.php">Droghe</a></li>'.
	'<li><a href="../backend/areaPersonale.php">Area personale</a></li>'.
	'<li><a href="../backend/FAQ.php"><abbr title="Frequently Asked Questions" xml:lang="en">FAQ</abbr></a></li>'), $DOM);

echo $DOM;
