<?php

use MyCandies\Controllers\Authentication;
use MyCandies\Entities\User;
use MyCandies\Entities\Address;


require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';
$auth = new Authentication();

if (!$auth->isLoggedIn()) {
	header('location: ./formCliente.php');
	die();
}

$DOM = file_get_contents('../frontend/areaPersonale.html');

$DOM = str_replace('<a_auth_state />', '<a href="logout.php" id="loginButton" class="fa fa-sign-out buttons"><span xml:lang="en"> Logout</span></a>', $DOM);

$DOM = str_replace('<dashboard />', ($auth->isAdmin()
	?
	'<li><a href="../backend/inserisciProdotto.php">Gestione</a></li>'
	:
	''), $DOM);

$DOM = str_replace('<success_on_modify />', '', $DOM);

require_once __DIR__.'/lib/functions.php';

require_once __DIR__.'/classes/MyCandies/Entities/User.php';
require_once __DIR__.'/classes/MyCandies/Entities/Address.php';

$items = array_merge(getProperties(User::class), getProperties(Address::class));
$DOM = noFormErrors($DOM, $items);

$userData = $auth->getUserData();

$DOM = insertValues($DOM, $userData);

$addressData = $auth->getAddressData();

$DOM = insertValues($DOM, $addressData);

echo $DOM;
