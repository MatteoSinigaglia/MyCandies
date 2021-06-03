<?php

use MyCandies\Controllers\Authentication;
use MyCandies\Entities\Address;
use MyCandies\Entities\User;
use MyCandies\Exceptions\EntityException;

if (!isset($_POST['confirm'])) {
	header('location: ./home.php');
	die();
}

require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';
$auth = new Authentication();

try {
	$auth->updateData($_POST['user'], $_POST['address']);
} catch (EntityException $e) {
	$errors = $e->getErrors();
}

$DOM = file_get_contents('../frontend/areaPersonale.html');

//  Header setup
$DOM = str_replace('<a_auth_state />', '<a href="logout.php" id="loginButton" class="fa fa-sign-out buttons"> Logout</a>', $DOM);

if (!isset($errors)) {
	$DOM = str_replace('<success_on_modify />', '<strong class="formSuccess">Dati dell\'utente modificati con successo</strong>', $DOM);

	require_once __DIR__.'/lib/functions.php';

	require_once __DIR__.'/classes/MyCandies/Entities/User.php';
	require_once __DIR__.'/classes/MyCandies/Entities/Address.php';

	$items = array_merge(getProperties(User::class), getProperties(Address::class));
	$DOM = noFormErrors($DOM, $items);

	$userData = $auth->getUserData();

	$DOM = insertValues($DOM, $userData);

	$addressData = $auth->getAddressData();

	$DOM = insertValues($DOM, $addressData);


} else {

	$fields = $_POST['user'] + $_POST['address'];

	foreach ($fields as $item => $value) {
		$DOM = str_replace('<error_' . $item . ' />', (isset($errors[$item]) ? '<strong class="formErrors">' . $errors[$item] . '</strong>' : ''), $DOM);
		$DOM = str_replace('_' . $item . '_value', $value, $DOM);
	}

}
	echo $DOM;
