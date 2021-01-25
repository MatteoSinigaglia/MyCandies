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
		'     <li><a href="../backend/riepilogoAcquisti_dashboard.php">Riepilogo acquisti</a></li>
                <li><a href="../backend/utenti_dashboard.php">Utenti</a></li>
                <li><a href="../backend/inserisciProdotto.php">Inserisci prodotto</a></li>
                <li><a href="../backend/prodotti_dashboard.php">Lista prodotti</a></li>
                <li><a href="../backend/insertCharacteristics.php">Inserisci caratteristiche</a></li>
                '
		:
		'   <li><a href="../backend/listaProdotti.php">Droghe</a></li>
            <li><a href="../backend/areaPersonale.php">Area personale</a></li>
            <li><a href="../backend/FAQ.php"><abbr title="Frequently Asked Questions" xml:lang="en">FAQ</abbr></a></li>'), $DOM);
} else
	$DOM = str_replace('<a_auth_state />', '<a href="formCliente.php" id="loginButton" class="buttons">Accedi</a>', $DOM);

echo $DOM;
