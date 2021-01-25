<?php

require_once '..'.DIRECTORY_SEPARATOR.'paths_index.php';

use MyCandies\Controllers\ShopManager;

$shop = new ShopManager();

$cart = $shop->getCart();

if (!isset($cart)) {
	header('locattion: .'.DS.'carrello.php');
	die();
}

$shop->checkout();

header('location: .'.DS.'home.php');
die();