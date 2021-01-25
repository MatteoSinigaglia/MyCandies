<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';

use MyCandies\Controllers\Authentication;
use MyCandies\Controllers\ShopManager;

if(!isset($_SERVER['HTTP_REFERER'])) {
	header('location: .' . DS . 'home.php');
	die();
}

require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Authentication.php';
$auth = new Authentication();

require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ShopManager.php';
$shop = new ShopManager();

$shop->decreaseProductQuantity((int)$_GET['id']);

header('location: .'.DS.'carrello.php');
die();