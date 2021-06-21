<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';

use MyCandies\Controllers\Authentication;
use MyCandies\Controllers\ShopManager;

require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Authentication.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ShopManager.php';

$auth = new Authentication();
$shop = new ShopManager();

if (!$auth->isLoggedIn()) {
	header('location: .'.DS.'formCliente.php');
	die();
}

$cart = $shop->getCart();

if (!isset($cart)) {
	header('location: .'.DS.'carrello.php');
	die();
}

try{
	$shop->checkout($cart, $auth);
} catch (Exception $e) {
}
header('location: .'.DS.'carrello.php');
die();
