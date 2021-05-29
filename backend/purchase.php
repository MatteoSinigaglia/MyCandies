<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';

use MyCandies\Controllers\ShopManager;

$shop = new ShopManager();

$cart = $shop->getCart();

if (!isset($cart)) {
	header('location: .'.DS.'carrello.php');
	die();
}

$shop->checkout();

header('location: .'.DS.'home.php');
die();