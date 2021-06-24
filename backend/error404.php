<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'Authentication.php';

use \MyCandies\Controllers\Authentication;

$DOM = file_get_contents(VIEW_PATH . DS . "error404.html");

$auth = new Authentication();

if  ($auth->isLoggedIn()) {
    $DOM = str_replace('<a_auth_state />', '<a href="logout.php" id="loginButton" class="fa fa-sign-out buttons"><span xml:lang="en"> Logout</span></a>', $DOM);
} else
    $DOM = str_replace('<a_auth_state />', '<a href="./formCliente.php" id="loginButton" class="fa fa-sign-in buttons"><span> Accedi</span></a>', $DOM);

$DOM = str_replace('<dashboard />', ($auth->isAdmin()
    ?
    '<li><a href="../backend/inserisciProdotto.php">Gestione</a></li>'
    :
    ''), $DOM);

echo $DOM;